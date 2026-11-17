<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ChatbotApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        DB::purge('sqlite');
        $this->app->instance(\Illuminate\Cache\RateLimiter::class,
            new \Illuminate\Cache\RateLimiter(\Illuminate\Support\Facades\Cache::store('array')));
        Schema::create('campaigns', function (Blueprint $t) {
            $t->id();
            foreach (['title','description','kategori','status','lokasi'] as $field) $t->string($field);
            $t->string('image')->nullable(); $t->string('yayasan')->nullable();
            $t->decimal('target_amount'); $t->decimal('current_amount'); $t->date('end_date');
        });
        Schema::create('volunteer_campaigns', function (Blueprint $t) {
            $t->id();
            foreach (['judul','deskripsi','kategori','status','lokasi'] as $field) $t->string($field);
            $t->string('image')->nullable(); $t->integer('kuota_total'); $t->integer('kuota_terisi');
            $t->date('tanggal_mulai'); $t->date('tanggal_selesai');
        });
        DB::table('campaigns')->insert([
            ['title'=>'Bantuan Sekolah', 'description'=>'Pendidikan anak', 'kategori'=>'Pendidikan', 'status'=>'verified', 'lokasi'=>'Bandung', 'target_amount'=>10000, 'current_amount'=>0, 'end_date'=>now()->addDays(20)->toDateString()],
            ['title'=>'Rahasia pending', 'description'=>'Pendidikan', 'kategori'=>'Pendidikan', 'status'=>'pending', 'lokasi'=>'Bandung', 'target_amount'=>10000, 'current_amount'=>0, 'end_date'=>now()->addDays(20)->toDateString()],
        ]);
        DB::table('volunteer_campaigns')->insert([
            'judul'=>'Bersih Sungai', 'deskripsi'=>'Aksi lingkungan', 'kategori'=>'Lingkungan', 'status'=>'Aktif',
            'lokasi'=>'Bandung', 'kuota_total'=>10, 'kuota_terisi'=>2,
            'tanggal_mulai'=>now()->addDay()->toDateString(), 'tanggal_selesai'=>now()->addDays(10)->toDateString(),
        ]);
    }

    public function test_donation_recommendations_are_public_verified_and_match_category(): void
    {
        $this->postJson('/api/v1/chatbot/message', ['message'=>'Cari donasi pendidikan'])
            ->assertOk()->assertJsonPath('used_ai', false)->assertJsonCount(1, 'data.donations')
            ->assertJsonCount(0, 'data.volunteers')->assertJsonPath('data.donations.0.title','Bantuan Sekolah')
            ->assertJsonPath('data.donations.0.type','donation');
    }

    public function test_volunteers_match_location_and_have_capacity(): void
    {
        $this->postJson('/api/v1/chatbot/message', ['message'=>'Kegiatan relawan lingkungan di Bandung'])
            ->assertOk()->assertJsonCount(1, 'data.volunteers')->assertJsonCount(0, 'data.donations')
            ->assertJsonPath('data.volunteers.0.type','volunteer');
        $this->postJson('/api/v1/chatbot/message', ['message'=>'Relawan di Jakarta'])
            ->assertOk()->assertJsonCount(0, 'data.volunteers');
        DB::table('volunteer_campaigns')->update(['kuota_terisi'=>10]);
        $this->postJson('/api/v1/chatbot/message', ['message'=>'Relawan lingkungan'])
            ->assertOk()->assertJsonCount(0, 'data.volunteers');
    }

    public function test_expired_and_funded_campaigns_are_not_recommended(): void
    {
        DB::table('campaigns')->where('status','verified')->update(['end_date'=>now()->subDay()->toDateString()]);
        $this->postJson('/api/v1/chatbot/message', ['message'=>'Donasi pendidikan'])
            ->assertOk()->assertJsonCount(0, 'data.donations');
        DB::table('campaigns')->where('status','verified')->update(['end_date'=>now()->addDay()->toDateString(), 'current_amount'=>10000]);
        $this->postJson('/api/v1/chatbot/message', ['message'=>'Donasi pendidikan'])
            ->assertOk()->assertJsonCount(0, 'data.donations');
    }

    public function test_greeting_and_guidance_return_useful_replies_without_recommendations(): void
    {
        foreach ([
            ['Halo','Halo!'], ['Bagaimana cara daftar relawan?', 'CV PDF'],
            ['Cara donasi', 'Riwayat Donasi'], ['Sertifikat saya', 'Sertifikat Saya'],
            ['Cara membuat kampanye','verifikasi admin'],
        ] as [$message, $expected]) {
            $response = $this->postJson('/api/v1/chatbot/message', compact('message'))
                ->assertOk()->assertJsonCount(0, 'data.donations')->assertJsonCount(0, 'data.volunteers');
            $this->assertStringContainsString($expected, $response->json('reply'));
        }
    }

    public function test_invalid_messages_are_rejected_and_unknown_requests_are_honest(): void
    {
        foreach (['', '   ', str_repeat('a',1001), ['bad']] as $message) {
            $this->postJson('/api/v1/chatbot/message', compact('message'))->assertUnprocessable();
        }
        $response = $this->postJson('/api/v1/chatbot/message', ['message'=>'Ajari astronomi'])
            ->assertOk()->assertJsonCount(0, 'data.donations')->assertJsonCount(0,'data.volunteers');
        $this->assertStringContainsString('Belum ada', $response->json('reply'));
    }
}
