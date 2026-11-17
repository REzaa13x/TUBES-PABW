<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CampaignSubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        DB::purge('sqlite'); Cache::flush(); Storage::fake('public'); Http::preventStrayRequests();
        // The HTTP kernel may resolve its limiter before per-test config changes.
        // Bind an isolated limiter instead of sharing the developer's cache store.
        $this->app->instance(\Illuminate\Cache\RateLimiter::class, new \Illuminate\Cache\RateLimiter(Cache::store('array')));
        \Illuminate\Support\Facades\RateLimiter::clearResolvedInstance(\Illuminate\Cache\RateLimiter::class);
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email'); $t->string('password'); $t->string('role'); $t->timestamps();
        });
        Schema::create('campaigns', function (Blueprint $t) {
            $t->id(); $t->string('title'); $t->text('description'); $t->decimal('target_amount', 15, 2);
            $t->decimal('current_amount', 15, 2)->default(0); $t->string('image')->nullable();
            $t->date('end_date'); $t->unsignedBigInteger('user_id'); $t->string('slug')->unique();
            $t->string('status')->default('pending'); $t->string('kategori');
            foreach (['yayasan','jenis_penerima','whatsapp','penyaluran','lokasi','provinsi','kabupaten','kecamatan','kode_pos'] as $field) $t->string($field)->nullable();
            $t->text('alamat_lengkap')->nullable(); $t->timestamps();
        });
        (require database_path('migrations/2026_11_17_000002_add_campaign_submission_location.php'))->up();
        Schema::create('admin_notifications', function (Blueprint $t) {
            $t->id(); $t->string('title'); $t->text('message');
            foreach (['category','priority','icon','type','action_label','action_url'] as $field) $t->string($field)->nullable();
            $t->unsignedBigInteger('related_campaign_id')->nullable(); $t->unsignedBigInteger('related_user_id')->nullable();
            $t->json('metadata')->nullable(); $t->timestamps();
        });
    }

    private function user(string $role = 'donatur'): User
    {
        return User::forceCreate(['name' => 'Pengaju', 'email' => uniqid().'@example.test', 'password' => 'test-password', 'role' => $role]);
    }

    private function payload(): array
    {
        return [
            'title' => 'Renovasi Sekolah', 'description' => 'Penggalangan dana renovasi sekolah warga.',
            'target_amount' => 10000000, 'end_date' => now()->addWeek()->toDateString(),
            'kategori' => 'Pendidikan', 'yayasan' => 'Sekolah Warga', 'jenis_penerima' => 'Komunitas',
            'recipient_description' => 'Para siswa membutuhkan ruang belajar yang aman.',
            'whatsapp' => '081234567890', 'penyaluran' => 'Langsung',
            'lokasi' => 'Bandung', 'alamat_lengkap' => 'Jalan Pendidikan, Bandung',
            'provinsi' => 'Jawa Barat', 'kabupaten' => 'Bandung', 'kecamatan' => 'Coblong',
            'kode_pos' => '40132', 'latitude' => '-6.8912345', 'longitude' => '107.6101234',
            'image' => UploadedFile::fake()->image('sekolah.png', 100, 80),
        ];
    }

    public function test_submission_stores_recipient_exact_coordinates_image_and_logged_in_owner(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $payload = [...$this->payload(), 'user_id' => 999, 'status' => 'verified'];
        $response = $this->postJson('/api/v1/campaigns', $payload)->assertCreated()
            ->assertJsonPath('data.status', 'pending')->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.yayasan', 'Sekolah Warga')->assertJsonPath('data.latitude', -6.8912345)
            ->assertJsonPath('data.longitude', 107.6101234);
        $campaign = Campaign::findOrFail($response->json('data.id'));
        $this->assertSame($user->id, $campaign->user_id);
        $this->assertSame('Para siswa membutuhkan ruang belajar yang aman.', $campaign->recipient_description);
        Storage::disk('public')->assertExists($campaign->getRawOriginal('image'));
        $this->getJson('/api/v1/campaigns?my=1')->assertOk()->assertJsonPath('data.0.status', 'pending');
        $this->assertDatabaseHas('admin_notifications', ['related_campaign_id' => $campaign->id, 'related_user_id' => $user->id]);
    }

    public function test_authentication_and_donor_role_are_required(): void
    {
        $this->postJson('/api/v1/campaigns', $this->payload())->assertUnauthorized();
        Sanctum::actingAs($this->user('validator'));
        $this->postJson('/api/v1/campaigns', $this->payload())->assertForbidden();
    }

    public function test_unverified_submissions_are_only_visible_to_the_owner_and_admin(): void
    {
        $this->getJson('/api/v1/campaigns?my=1')->assertUnauthorized();
        $owner = $this->user(); Sanctum::actingAs($owner);
        $id = $this->postJson('/api/v1/campaigns', $this->payload())->assertCreated()->json('data.id');
        $this->getJson('/api/v1/campaigns/'.$id)->assertOk();
        $this->getJson('/api/v1/campaigns')->assertJsonCount(0, 'data');
        Sanctum::actingAs($this->user());
        $this->getJson('/api/v1/campaigns/'.$id)->assertNotFound();
        $this->getJson('/api/v1/campaigns?my=1')->assertJsonCount(0, 'data');
        Sanctum::actingAs($this->user('admin'));
        $this->getJson('/api/v1/campaigns/'.$id)->assertOk();
        $this->putJson('/api/v1/campaigns/'.$id, ['status' => 'verified'])->assertOk();
        Sanctum::actingAs($this->user());
        $this->getJson('/api/v1/campaigns')->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/campaigns/'.$id)->assertOk();
    }

    public function test_invalid_missing_coordinates_image_category_and_dates_are_rejected(): void
    {
        Sanctum::actingAs($this->user());
        foreach ([['latitude' => 91], ['longitude' => -181], ['latitude' => null], ['image' => null],
            ['kategori' => 'Unknown'], ['target_amount' => 9999], ['end_date' => now()->toDateString()],
            ['recipient_description' => ''], ['jenis_penerima' => 'Unknown']] as $invalid) {
            $this->postJson('/api/v1/campaigns', [...$this->payload(), ...$invalid])->assertUnprocessable();
        }
        $this->postJson('/api/v1/campaigns', [...$this->payload(), 'image' => UploadedFile::fake()->create('large.jpg', 2049, 'image/jpeg')])->assertUnprocessable();
        $this->assertSame(0, Campaign::count());
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }

    public function test_only_admin_can_change_approval_and_other_users_cannot_edit_campaign(): void
    {
        $owner = $this->user(); Sanctum::actingAs($owner);
        $id = $this->postJson('/api/v1/campaigns', $this->payload())->assertCreated()->json('data.id');
        $this->putJson('/api/v1/campaigns/'.$id, ['status' => 'verified'])->assertForbidden();
        Sanctum::actingAs($this->user());
        $this->putJson('/api/v1/campaigns/'.$id, ['title' => 'Other user'])->assertForbidden();
        $this->deleteJson('/api/v1/campaigns/'.$id)->assertForbidden();
        $this->getJson('/api/v1/campaigns?my=1')->assertJsonCount(0, 'data');
        Sanctum::actingAs($this->user('admin'));
        $this->putJson('/api/v1/campaigns/'.$id, ['status' => 'verified'])->assertOk()->assertJsonPath('data.status', 'verified');
        $this->putJson('/api/v1/campaigns/'.$id, ['status' => 'rejected'])->assertOk()->assertJsonPath('data.status', 'rejected');
    }

    private function geocodeResult(): array
    {
        return ['lat' => '-6.9', 'lon' => '107.6', 'display_name' => 'Jalan Pendidikan, Coblong, Kota Bandung, Jawa Barat',
            'address' => ['city_district' => 'Coblong', 'city' => 'Kota Bandung', 'state' => 'Jawa Barat', 'postcode' => '40132']];
    }

    public function test_geocoding_requires_authentication_and_valid_input(): void
    {
        $this->getJson('/api/v1/locations/search?q=Bandung')->assertUnauthorized();
        Sanctum::actingAs($this->user());
        $this->getJson('/api/v1/locations/search?q=ab')->assertUnprocessable();
        $this->getJson('/api/v1/locations/reverse?latitude=91&longitude=100')->assertUnprocessable();
    }

    public function test_search_returns_addresses_and_caches_provider_response(): void
    {
        Sanctum::actingAs($this->user());
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([$this->geocodeResult()])]);
        $this->getJson('/api/v1/locations/search?q=Sekolah%20Bandung')->assertOk()
            ->assertJsonPath('data.0.city', 'Kota Bandung')->assertJsonPath('data.0.latitude', -6.9);
        $this->getJson('/api/v1/locations/search?q=Sekolah%20Bandung')->assertOk();
        Http::assertSentCount(1);
    }

    public function test_reverse_preserves_pin_coordinates_instead_of_nearby_place_centroid(): void
    {
        Sanctum::actingAs($this->user());
        Http::fake(['nominatim.openstreetmap.org/reverse*' => Http::response($this->geocodeResult())]);
        $this->getJson('/api/v1/locations/reverse?latitude=-6.8912345&longitude=107.6101234')->assertOk()
            ->assertJsonPath('data.latitude', -6.8912345)->assertJsonPath('data.longitude', 107.6101234)
            ->assertJsonPath('data.district', 'Coblong');
    }

    public function test_provider_errors_and_shared_rate_limit_are_reported_without_dummy_location(): void
    {
        Sanctum::actingAs($this->user());
        Http::fake(['*' => Http::response(['error' => 'Unavailable'], 503)]);
        $this->getJson('/api/v1/locations/search?q=Bandung')->assertStatus(503)->assertJsonMissingPath('data');
        $this->getJson('/api/v1/locations/search?q=Jakarta')->assertStatus(429)->assertJsonMissingPath('data');
        Http::assertSentCount(1);
    }
}
