<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VolunteerRegistrationApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:', 'cache.default'=>'array']);
        DB::purge('sqlite'); Storage::fake('public');
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email'); $t->string('password'); $t->string('role'); $t->timestamps();
        });
        Schema::create('volunteer_campaigns', function (Blueprint $t) {
            $t->id(); $t->string('judul'); $t->integer('kuota_total'); $t->integer('kuota_terisi'); $t->timestamps();
        });
        (require database_path('migrations/2025_12_10_171808_create_volunteer_applications_table.php'))->up();
        DB::table('volunteer_campaigns')->insert(['id'=>1, 'judul'=>'Bakti Sosial', 'kuota_total'=>10, 'kuota_terisi'=>0]);
    }

    private function login(): User
    {
        $user = User::forceCreate(['name'=>'Pendaftar', 'email'=>'test@example.test', 'password'=>'secret', 'role'=>'donatur']);
        Sanctum::actingAs($user); return $user;
    }

    private function payload(): array
    {
        return ['volunteer_campaign_id'=>1, 'alamat'=>'Jalan Pendidikan Bandung', 'posisi_dilamar'=>'Lapangan',
            'alasan_bergabung'=>'Saya ingin membantu kegiatan sosial masyarakat.',
            'cv'=>UploadedFile::fake()->createWithContent('resume.pdf', "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF")];
    }

    public function test_multipart_saves_website_fields_pdf_owner_and_pending(): void
    {
        $user = $this->login();
        $response = $this->postJson('/api/v1/volunteer-applications', [...$this->payload(), 'user_id'=>999, 'status'=>'approved'])
            ->assertCreated()->assertJsonPath('data.user_id', $user->id)->assertJsonPath('data.status','pending')
            ->assertJsonPath('data.posisi_dilamar','Lapangan')->assertJsonPath('data.alamat','Jalan Pendidikan Bandung');
        Storage::disk('public')->assertExists($response->json('data.cv_path'));
        $this->assertDatabaseHas('volunteer_applications', ['id'=>$response->json('data.id'), 'alasan_bergabung'=>$this->payload()['alasan_bergabung']]);
        $this->getJson('/api/v1/volunteer-applications')->assertOk()->assertJsonPath('data.data.0.id', $response->json('data.id'));
        $this->postJson('/api/v1/volunteer-applications', $this->payload())->assertStatus(409);
        $this->assertDatabaseCount('volunteer_applications',1);
        $this->assertCount(1,Storage::disk('public')->allFiles());
    }

    public function test_authentication_and_required_website_fields_are_enforced(): void
    {
        $this->postJson('/api/v1/volunteer-applications', $this->payload())->assertUnauthorized();
        $this->login();
        $this->postJson('/api/v1/volunteer-applications', ['volunteer_campaign_id'=>1, 'motivation'=>'legacy', 'skills'=>'legacy'])
            ->assertUnprocessable()->assertJsonValidationErrors(['alamat','posisi_dilamar','alasan_bergabung','cv']);
        $this->assertDatabaseCount('volunteer_applications',0);
    }

    public function test_invalid_or_oversized_pdf_is_rejected_without_files(): void
    {
        $this->login();
        $this->postJson('/api/v1/volunteer-applications', [...$this->payload(), 'cv'=>UploadedFile::fake()->create('photo.jpg', 1, 'image/jpeg')])
            ->assertUnprocessable()->assertJsonValidationErrors('cv');
        $this->postJson('/api/v1/volunteer-applications', [...$this->payload(), 'cv'=>UploadedFile::fake()->create('large.pdf',2049,'application/pdf')])
            ->assertUnprocessable()->assertJsonValidationErrors('cv');
        $this->assertCount(0,Storage::disk('public')->allFiles());
    }

    public function test_full_campaign_rejects_application_without_storing_cv(): void
    {
        $this->login(); DB::table('volunteer_campaigns')->where('id',1)->update(['kuota_terisi'=>10]);
        $this->postJson('/api/v1/volunteer-applications', $this->payload())->assertStatus(400);
        $this->assertDatabaseCount('volunteer_applications',0);
        $this->assertCount(0,Storage::disk('public')->allFiles());
    }
}
