<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $password = config('bootstrap_admin.password');

        if (! is_string($password) || $password === '') {
            throw new RuntimeException('Set BOOTSTRAP_ADMIN_PASSWORD before running the admin user migration.');
        }

        $now = now();

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make($password),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new LogicException('The admin user migration cannot be rolled back without risking user data.');
    }
};
