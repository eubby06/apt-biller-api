<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_tenant_by_id(): void
    {
        DB::table('tenants')->where('id', 2)->update(['due_date' => '02-29']);

        $this->getJson('/api/tenants/2')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', 2)
            ->assertJsonPath('data.name', 'Vince')
            ->assertJsonPath('data.due_date', '02-29');
    }

    public function test_the_tenant_listing_returns_month_and_day_only(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 5));

        $this->getJson('/api/tenants')
            ->assertOk()
            ->assertJsonPath('data.0.due_date', '03-05')
            ->assertJsonPath('data.1.due_date', '03-05');
    }

    public function test_it_returns_not_found_for_a_missing_tenant(): void
    {
        $this->getJson('/api/tenants/999')
            ->assertNotFound();
    }
}
