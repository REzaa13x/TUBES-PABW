<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DeploymentMigrationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
    }

    public function test_all_migrations_run_on_an_empty_database(): void
    {
        $this->assertSame(0, Artisan::call('migrate', ['--force' => true]), Artisan::output());
        foreach (['admin_notifications', 'campaigns', 'donations', 'volunteers', 'sessions', 'cache'] as $table) {
            $this->assertTrue(Schema::hasTable($table), $table);
        }
        $keys = Schema::getForeignKeys('admin_notifications');
        $this->assertFalse(collect($keys)->contains(fn ($key) => in_array($key['columns'][0], [
            'related_campaign_id', 'related_donation_id', 'related_volunteer_id',
        ], true)));
    }

    public function test_retry_preserves_an_existing_notification_table(): void
    {
        (require database_path('migrations/0001_01_01_000000_create_users_table.php'))->up();
        $migration = require database_path('migrations/2024_01_01_000001_create_admin_notifications_table.php');
        $migration->up();
        DB::table('admin_notifications')->insert(['title' => 'Keep me', 'message' => 'Existing notification']);
        $migration->up();
        $cleanup = require database_path('migrations/2026_06_02_000003_drop_foreign_keys_from_admin_notifications_table.php');
        $cleanup->up();
        $cleanup->up();
        $this->assertSame('Keep me', DB::table('admin_notifications')->value('title'));
    }

    public function test_session_repair_restores_database_sessions_and_preserves_existing_data(): void
    {
        $this->assertSame(0, Artisan::call('migrate', ['--force' => true]), Artisan::output());
        Schema::drop('sessions');

        $repair = require database_path('migrations/2026_11_17_000003_ensure_sessions_table_exists.php');
        $repair->up();

        $handler = new \Illuminate\Session\DatabaseSessionHandler(DB::connection(), 'sessions', 120);
        $this->assertSame('', $handler->read('deployment-session'));
        $this->assertTrue($handler->write('deployment-session', 'session-payload'));

        $repair->up();
        $repair->down();
        $this->assertSame('session-payload', $handler->read('deployment-session'));
    }
}
