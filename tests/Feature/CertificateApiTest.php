<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VolunteerCertificate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CertificateApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email'); $t->string('password'); $t->timestamps();
        });
        Schema::create('volunteer_campaigns', function (Blueprint $t) { $t->id(); });
        Schema::create('volunteer_applications', function (Blueprint $t) { $t->id(); });
        (require database_path('migrations/2026_06_02_164116_create_volunteer_certificates_table.php'))->up();
    }

    private function user(): User
    {
        return User::forceCreate(['name' => 'Relawan Uji', 'email' => uniqid().'@example.test', 'password' => 'test-password']);
    }

    private function certificate(User $user, string $status = 'active'): VolunteerCertificate
    {
        $campaignId = DB::table('volunteer_campaigns')->insertGetId([]);
        return VolunteerCertificate::create([
            'certificate_id' => (string) \Illuminate\Support\Str::uuid(),
            'certificate_number' => 'DGV-VOL-TEST-'.uniqid(),
            'volunteer_id' => $user->id, 'volunteer_name' => $user->name,
            'campaign_id' => $campaignId, 'campaign_name' => 'Bersih Pantai',
            'category' => 'Lingkungan', 'location' => 'Bandung',
            'start_date' => '2026-01-01', 'end_date' => '2026-01-02', 'issue_date' => '2026-01-03',
            'total_volunteer_hours' => 8, 'volunteer_level' => 'Relawan Baru', 'status' => $status,
        ]);
    }

    private function signed(VolunteerCertificate $certificate): string
    {
        return URL::temporarySignedRoute('api.certificates.signed-download', now()->addMinutes(10), ['id' => $certificate->id], absolute: false);
    }

    public function test_only_owner_can_read_detail_or_request_download_link(): void
    {
        $owner = $this->user(); $cert = $this->certificate($owner);
        $this->getJson('/api/v1/certificates')->assertUnauthorized();
        Sanctum::actingAs($this->user());
        $this->getJson('/api/v1/certificates/'.$cert->id)->assertNotFound();
        $this->getJson('/api/v1/certificates/'.$cert->id.'/download-url')->assertNotFound();
        $this->getJson('/api/v1/certificates/'.$cert->id.'/download')->assertNotFound();
        Sanctum::actingAs($owner);
        $this->getJson('/api/v1/certificates/'.$cert->id)->assertOk()->assertJsonPath('data.is_downloadable', true);
    }

    public function test_list_statistics_match_website_and_exclude_pending_hours(): void
    {
        $user = $this->user();
        $this->certificate($user); $this->certificate($user, 'pending'); $this->certificate($user, 'revoked');
        $this->certificate($this->user());
        Sanctum::actingAs($user);
        $this->getJson('/api/v1/certificates')->assertOk()->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.total', 3)->assertJsonPath('meta.total_active', 1)
            ->assertJsonPath('meta.total_campaigns', 3)->assertJsonPath('meta.total_hours', 8);
    }

    public function test_pending_and_revoked_certificates_cannot_be_downloaded(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        foreach (['pending', 'revoked'] as $status) {
            $cert = $this->certificate($user, $status);
            $this->getJson('/api/v1/certificates/'.$cert->id.'/download-url')->assertForbidden();
            $this->getJson('/api/v1/certificates/'.$cert->id.'/download')->assertForbidden();
            $this->getJson($this->signed($cert))->assertForbidden();
        }
    }

    public function test_download_link_returns_a_real_pdf_without_browser_login(): void
    {
        $cert = $this->certificate($this->user());
        $response = $this->get($this->signed($cert))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));
        $this->assertFalse(auth()->check());
    }

    public function test_website_and_authenticated_api_download_the_same_certificate_template(): void
    {
        $user = $this->user(); $cert = $this->certificate($user);
        $web = $this->actingAs($user, 'web')->get('/volunteer/certificates/'.$cert->id.'/download')
            ->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $web->getContent());
        Sanctum::actingAs($user);
        $api = $this->get('/api/v1/certificates/'.$cert->id.'/download')
            ->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $api->getContent());
        $this->assertSame($web->headers->get('content-disposition'), $api->headers->get('content-disposition'));
    }

    public function test_download_url_is_expiring_relative_signed_route_and_works_over_https(): void
    {
        $user = $this->user(); Sanctum::actingAs($user);
        $cert = $this->certificate($user);
        URL::forceRootUrl('https://donasi.example.test'); URL::forceScheme('https');
        $result = $this->getJson('/api/v1/certificates/'.$cert->id.'/download-url')->assertOk();
        $url = $result->json('download_url');
        $this->assertStringStartsWith('https://donasi.example.test/api/v1/certificates/', $url);
        $this->assertStringContainsString('signature=', $url);
        $this->assertNotEmpty($result->json('expires_at'));
        // Host changes do not break relative signatures (emulator/proxy).
        $relative = parse_url($url, PHP_URL_PATH).'?'.parse_url($url, PHP_URL_QUERY);
        $this->get($relative)->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    public function test_expired_tampered_and_revoked_links_are_rejected(): void
    {
        $cert = $this->certificate($this->user()); $link = $this->signed($cert);
        $this->getJson($link.'&extra=1')->assertForbidden();
        $cert->update(['status' => 'revoked']);
        $this->getJson($link)->assertForbidden();
        $cert->update(['status' => 'active']);
        $this->travel(11)->minutes();
        $this->getJson($link)->assertForbidden();
    }

    public function test_verification_is_public_and_qr_is_generated_locally(): void
    {
        $cert = $this->certificate($this->user());
        $this->getJson('/api/v1/certificate/verify/'.$cert->certificate_number)->assertOk()->assertJsonPath('is_valid', true);
        $qr = $this->get('/api/v1/certificate/qr/'.$cert->certificate_number)->assertOk()->assertHeader('content-type', 'image/png');
        $this->assertStringStartsWith("\x89PNG", $qr->getContent());
        $this->assertStringStartsWith('data:image/png;base64,', $cert->qr_data_uri);
        $cert->update(['status' => 'revoked']);
        $this->getJson('/api/v1/certificate/verify/'.$cert->certificate_number)->assertJsonPath('is_valid', false);
    }

    public function test_cors_uses_hosting_configuration_instead_of_wildcard_override(): void
    {
        config(['cors.allowed_origins' => ['https://website.example.test']]);
        $this->withHeaders(['Origin' => 'https://website.example.test'])
            ->getJson('/api/v1/certificate/verify/unknown')->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'https://website.example.test');
    }
}
