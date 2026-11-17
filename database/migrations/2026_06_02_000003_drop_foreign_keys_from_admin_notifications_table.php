<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQL executes after the Blueprint callback, so inspect constraints first.
        foreach (Schema::getForeignKeys('admin_notifications') as $foreignKey) {
            if (count($foreignKey['columns']) === 1 && in_array($foreignKey['columns'][0], [
                'related_donation_id', 'related_volunteer_id', 'related_campaign_id',
            ], true)) {
                Schema::table('admin_notifications', function (Blueprint $table) use ($foreignKey) {
                    $table->dropForeign($foreignKey['name']);
                });
            }
        }
    }

    public function down(): void
    {
        $foreignKeys = Schema::getForeignKeys('admin_notifications');
        foreach ([
            'related_donation_id' => 'donations',
            'related_volunteer_id' => 'volunteers',
            'related_campaign_id' => 'campaigns',
        ] as $column => $referencedTable) {
            $exists = collect($foreignKeys)->contains(fn ($key) => $key['columns'] === [$column]);
            if (Schema::hasTable($referencedTable) && ! $exists) {
                Schema::table('admin_notifications', function (Blueprint $table) use ($column, $referencedTable) {
                    $table->foreign($column)->references('id')->on($referencedTable)->cascadeOnDelete();
                });
            }
        }
    }
};
