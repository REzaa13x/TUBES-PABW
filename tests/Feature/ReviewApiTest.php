<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Isolated database: never run the existing destructive migrations or touch local user data.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email')->unique(); $t->string('password'); $t->string('photo')->nullable(); $t->timestamps();
        });
        Schema::create('campaigns', function (Blueprint $t) { $t->id(); $t->string('title'); $t->timestamps(); });
        Schema::create('donation_transactions', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('user_id'); $t->unsignedBigInteger('campaign_id');
            $t->string('status'); $t->string('order_id'); $t->timestamps();
        });
        Schema::create('volunteer_campaigns', function (Blueprint $t) {
            $t->id(); $t->string('judul'); $t->string('status'); $t->date('tanggal_selesai'); $t->timestamps();
        });
        Schema::create('volunteer_applications', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('user_id'); $t->unsignedBigInteger('volunteer_campaign_id'); $t->string('status'); $t->timestamps();
        });
        (require database_path('migrations/2026_11_17_000001_create_reviews_table.php'))->up();
    }

    private function user(string $name = 'Donatur'): User
    {
        return User::forceCreate(['name' => $name, 'email' => uniqid().'@example.test', 'password' => 'test-password']);
    }

    private function donation(User $user, string $status = 'VERIFIED'): array
    {
        $campaign = DB::table('campaigns')->insertGetId(['title' => 'Bantu Pendidikan', 'created_at' => now(), 'updated_at' => now()]);
        $id = DB::table('donation_transactions')->insertGetId([
            'user_id' => $user->id, 'campaign_id' => $campaign, 'status' => $status,
            'order_id' => 'TEST-'.uniqid(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return ['activity_type' => 'donation', 'activity_id' => $campaign, 'reference_id' => $id,
            'rating' => 5, 'comment' => 'Penyaluran bantuan sangat baik.'];
    }

    private function volunteer(User $user, string $status = 'approved', bool $finished = true): array
    {
        $campaign = DB::table('volunteer_campaigns')->insertGetId([
            'judul' => 'Bersih Pantai', 'status' => $finished ? 'Selesai' : 'Aktif',
            'tanggal_selesai' => $finished ? now()->subDay()->toDateString() : now()->addWeek()->toDateString(),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $id = DB::table('volunteer_applications')->insertGetId([
            'user_id' => $user->id, 'volunteer_campaign_id' => $campaign, 'status' => $status,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return ['activity_type' => 'volunteer', 'activity_id' => $campaign, 'reference_id' => $id,
            'rating' => 4, 'comment' => 'Kegiatan relawan terorganisir dengan baik.'];
    }

    public function test_guest_can_read_but_cannot_write_reviews(): void
    {
        $this->getJson('/api/v1/reviews')->assertOk()->assertJsonPath('summary.total_reviews', 0);
        $this->postJson('/api/v1/reviews', [])->assertUnauthorized();
        $this->getJson('/api/v1/reviews/eligibility')->assertUnauthorized();
    }

    public function test_verified_donation_can_be_reviewed_once_and_is_returned_in_eligibility(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $payload = $this->donation($user);
        $this->getJson('/api/v1/reviews/eligibility')->assertOk()->assertJsonPath('unreviewed_count', 1);
        $this->postJson('/api/v1/reviews', $payload)->assertCreated()->assertJsonPath('data.campaign_title', 'Bantu Pendidikan');
        $this->postJson('/api/v1/reviews', $payload)->assertStatus(409);
        $this->getJson('/api/v1/reviews/eligibility')->assertJsonPath('data.0.has_reviewed', true)->assertJsonPath('unreviewed_count', 0);
    }

    public function test_pending_donation_and_someone_elses_reference_are_rejected(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $this->postJson('/api/v1/reviews', $this->donation($user, 'AWAITING_TRANSFER'))->assertUnprocessable();
        $this->postJson('/api/v1/reviews', $this->donation($this->user('Other')))->assertUnprocessable();
    }

    public function test_only_approved_finished_volunteer_activity_is_eligible(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $this->postJson('/api/v1/reviews', $this->volunteer($user, 'approved', false))->assertUnprocessable();
        $this->postJson('/api/v1/reviews', $this->volunteer($user, 'rejected'))->assertUnprocessable();
        $this->postJson('/api/v1/reviews', $this->volunteer($user))->assertCreated()->assertJsonPath('data.activity_type', 'volunteer');
    }

    public function test_filters_rating_summary_and_pagination_use_all_matching_reviews(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $this->postJson('/api/v1/reviews', $this->donation($user))->assertCreated();
        $this->postJson('/api/v1/reviews', $this->volunteer($user))->assertCreated();
        $this->getJson('/api/v1/reviews?per_page=1')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('summary.total_reviews', 2)->assertJsonPath('summary.average_rating', 4.5);
        $this->getJson('/api/v1/reviews?activity_type=donation')->assertJsonCount(1, 'data')->assertJsonPath('summary.total_reviews', 1)->assertJsonPath('summary.distribution.5', 1);
        $this->getJson('/api/v1/reviews?activity_type=volunteer')->assertJsonCount(1, 'data')->assertJsonPath('data.0.activity_type', 'volunteer');
        $this->getJson('/api/v1/reviews?activity_type=invalid')->assertUnprocessable();
    }

    public function test_owner_can_edit_delete_but_other_user_cannot(): void
    {
        $owner = $this->user(); Sanctum::actingAs($owner);
        $id = $this->postJson('/api/v1/reviews', $this->donation($owner))->assertCreated()->json('data.id');
        Sanctum::actingAs($this->user('Other'));
        $this->putJson('/api/v1/reviews/'.$id, ['rating' => 1, 'comment' => 'Tidak boleh diubah'])->assertForbidden();
        $this->deleteJson('/api/v1/reviews/'.$id)->assertForbidden();
        Sanctum::actingAs($owner);
        $this->putJson('/api/v1/reviews/'.$id, ['rating' => 4, 'comment' => 'Ulasan sudah diperbarui'])->assertOk()->assertJsonPath('data.rating', 4);
        $this->deleteJson('/api/v1/reviews/'.$id)->assertOk();
        $this->assertSame(0, Review::count());
    }

    public function test_anonymous_review_does_not_disclose_author_to_other_users(): void
    {
        $owner = $this->user('Private Name'); Sanctum::actingAs($owner);
        $this->postJson('/api/v1/reviews', [...$this->donation($owner), 'is_anonymous' => true])->assertCreated();
        $this->getJson('/api/v1/reviews')->assertJsonPath('data.0.user_id', $owner->id);
        Sanctum::actingAs($this->user('Viewer'));
        $this->getJson('/api/v1/reviews')->assertJsonPath('data.0.user_id', 0)->assertJsonPath('data.0.user_name', 'Sahabat DonGiv')->assertJsonPath('data.0.user_avatar', null);
    }

    public function test_rating_comment_and_reference_must_be_valid(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $payload = $this->donation($user);
        $this->postJson('/api/v1/reviews', [...$payload, 'rating' => 6])->assertUnprocessable();
        $this->postJson('/api/v1/reviews', [...$payload, 'comment' => '      '])->assertUnprocessable();
        $this->postJson('/api/v1/reviews', [...$payload, 'activity_id' => 9999])->assertUnprocessable();
        $this->assertSame(0, Review::count());
    }

    public function test_website_uses_the_same_three_category_labels(): void
    {
        $this->get('/ulasan')->assertOk()->assertSee('Semua Ulasan')->assertSee('Donasi')->assertSee('Relawan');
    }
}
