<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TenantDueDateMigrationTest extends TestCase
{
    public function test_it_preserves_month_and_day_for_existing_tenants_and_their_invoices(): void
    {
        config(['database.connections.due_date_migration_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        $originalConnection = DB::getDefaultConnection();
        DB::setDefaultConnection('due_date_migration_test');

        try {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->date('due_date');
            });
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            });
            DB::table('tenants')->insert([
                ['id' => 1, 'due_date' => '2024-02-29'],
                ['id' => 2, 'due_date' => '2025-12-03'],
            ]);
            DB::table('invoices')->insert(['id' => 1, 'tenant_id' => 1]);

            $migration = require database_path('migrations/2026_11_11_143148_change_tenant_due_date_to_month_day.php');
            $migration->up();

            $this->assertSame('02-29', DB::table('tenants')->where('id', 1)->value('due_date'));
            $this->assertSame('12-03', DB::table('tenants')->where('id', 2)->value('due_date'));
            $this->assertSame(1, DB::table('invoices')->where('id', 1)->value('tenant_id'));
            DB::table('tenants')->where('id', 1)->delete();
            $this->assertNull(DB::table('invoices')->where('id', 1)->value('tenant_id'));
        } finally {
            DB::setDefaultConnection($originalConnection);
            DB::purge('due_date_migration_test');
        }
    }
}
