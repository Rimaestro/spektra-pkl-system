<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample successful login attempts
        DB::table('login_attempts')->insert([
            [
                'email' => 'admin@spektra.ac.id',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'successful' => true,
                'failure_reason' => null,
                'attempted_at' => now()->subHours(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'koordinator@spektra.ac.id',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'successful' => true,
                'failure_reason' => null,
                'attempted_at' => now()->subHour(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Sample failed login attempts
        DB::table('login_attempts')->insert([
            [
                'email' => 'test@example.com',
                'ip_address' => '192.168.1.200',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'successful' => false,
                'failure_reason' => 'Invalid credentials',
                'attempted_at' => now()->subMinutes(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'admin@spektra.ac.id',
                'ip_address' => '10.0.0.50',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)',
                'successful' => false,
                'failure_reason' => 'Account locked',
                'attempted_at' => now()->subMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
