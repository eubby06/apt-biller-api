<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_inserts_the_default_tenants(): void
    {
        $this->assertDatabaseHas('tenants', [
            'id' => 1,
            'name' => 'Trixie',
            'unit' => 4,
            'total_heads' => 2,
            'rent_amount' => 6000,
            'internet_plan' => 'low_speed',
            'due_date' => today()->format('m-d'),
        ]);

        $this->assertDatabaseHas('tenants', [
            'id' => 2,
            'name' => 'Vince',
            'unit' => 3,
            'total_heads' => 2,
            'rent_amount' => 6000,
            'internet_plan' => 'high_speed',
            'due_date' => today()->format('m-d'),
        ]);

        $this->assertSame(2, DB::table('tenants')->count());
    }
}
