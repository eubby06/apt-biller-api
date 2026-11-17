<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        DB::table('tenants')->insert([
            [
                'id' => 1,
                'name' => 'Trixie',
                'unit' => 4,
                'total_heads' => 2,
                'rent_amount' => 6000,
                'internet_plan' => 'low_speed',
                'move_in_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Vince',
                'unit' => 3,
                'total_heads' => 2,
                'rent_amount' => 6000,
                'internet_plan' => 'high_speed',
                'move_in_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tenants')->whereIn('id', [1, 2])->delete();
    }
};
