<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_invoice(): void
    {
        $invoice = [
            'prev_date' => '2026-10-01',
            'prev_reading' => 2321,
            'curr_date' => '2026-11-01',
            'curr_reading' => 4556,
            'price' => 13,
            'amount' => 1287,
            'total_heads' => 2,
            'water_price' => 150,
            'water_amount' => 150,
            'rent_amount' => 6500,
            'internet_amount' => 1000,
            'total' => 7937,
        ];

        $response = $this->postJson('/api/invoices', $invoice);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.prev_reading', 2321)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('invoices', [
            'prev_date' => '2026-10-01',
            'curr_reading' => 4556,
            'total' => 7937,
            'status' => 'pending',
        ]);
    }

    public function test_it_rejects_an_invoice_with_invalid_data(): void
    {
        $response = $this->postJson('/api/invoices', [
            'prev_date' => '2026-11-01',
            'prev_reading' => -1,
            'curr_date' => '2026-10-01',
            'curr_reading' => 'not-a-reading',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'prev_reading',
                'curr_date',
                'curr_reading',
                'price',
                'total',
            ]);

        $this->assertDatabaseCount('invoices', 0);
    }
}
