<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Andi Wijayanto',
            'email' => 'admin@spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 15, Jakarta Pusat, DKI Jakarta 10110',
            'status' => 'active',
        ]);

        // Koordinator PKL
        User::create([
            'name' => 'Dr. Sari Indrawati, M.Kom',
            'email' => 'koordinator@spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'nip' => '197805152005012001',
            'phone' => '081298765432',
            'address' => 'Jl. Sudirman Kav. 25, Jakarta Selatan, DKI Jakarta 12920',
            'status' => 'active',
        ]);

        // Dosen Pembimbing
        User::create([
            'name' => 'Dr. Bambang Sutrisno, S.Kom, M.T',
            'email' => 'bambang.sutrisno@spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => '198203102008011002',
            'phone' => '081387654321',
            'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan, DKI Jakarta 12870',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Dr. Ratna Sari Dewi, S.T, M.Kom',
            'email' => 'ratna.dewi@spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => '198506252010012003',
            'phone' => '081456789012',
            'address' => 'Jl. Thamrin No. 45, Jakarta Pusat, DKI Jakarta 10350',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Prof. Dr. Hendra Wijaya, M.Sc',
            'email' => 'hendra.wijaya@spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => '197512101999031001',
            'phone' => '081567890123',
            'address' => 'Jl. Menteng Raya No. 29, Jakarta Pusat, DKI Jakarta 10340',
            'status' => 'active',
        ]);

        // Mahasiswa
        User::create([
            'name' => 'Ahmad Rizki Pratama',
            'email' => 'ahmad.rizki@student.spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2021110001',
            'phone' => '085612345678',
            'address' => 'Jl. Kebon Jeruk Raya No. 27, Jakarta Barat, DKI Jakarta 11530',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@student.spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2021110002',
            'phone' => '087823456789',
            'address' => 'Jl. Cempaka Putih Tengah No. 15, Jakarta Pusat, DKI Jakarta 10520',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@student.spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2021110003',
            'phone' => '089934567890',
            'address' => 'Jl. Pasar Minggu Raya No. 42, Jakarta Selatan, DKI Jakarta 12520',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@student.spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2021110004',
            'phone' => '081245678901',
            'address' => 'Jl. Kelapa Gading Boulevard No. 88, Jakarta Utara, DKI Jakarta 14240',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Reza Firmansyah',
            'email' => 'reza.firmansyah@student.spektra.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2021110005',
            'phone' => '082356789012',
            'address' => 'Jl. Cipinang Besar Selatan No. 33, Jakarta Timur, DKI Jakarta 13410',
            'status' => 'active',
        ]);

        // Pembimbing Lapangan
        User::create([
            'name' => 'Agus Setiawan',
            'email' => 'agus.setiawan@teknologimaju.co.id',
            'password' => Hash::make('password'),
            'role' => 'pembimbing_lapangan',
            'phone' => '081567890123',
            'address' => 'Jl. HR Rasuna Said Kav. C-5, Jakarta Selatan, DKI Jakarta 12940',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Maya Sari Putri',
            'email' => 'maya.putri@digitalsolusi.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing_lapangan',
            'phone' => '082678901234',
            'address' => 'Jl. Kuningan Barat No. 26, Jakarta Selatan, DKI Jakarta 12710',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Dedi Kurniawan',
            'email' => 'dedi.kurniawan@inovasisistem.co.id',
            'password' => Hash::make('password'),
            'role' => 'pembimbing_lapangan',
            'phone' => '083789012345',
            'address' => 'Jl. Jenderal Sudirman Kav. 52-53, Jakarta Selatan, DKI Jakarta 12190',
            'status' => 'active',
        ]);

        // Additional sample users for testing
        User::factory(15)->create([
            'role' => 'mahasiswa',
            'status' => 'active',
        ]);

        User::factory(5)->create([
            'role' => 'dosen',
            'status' => 'active',
        ]);

        User::factory(8)->create([
            'role' => 'pembimbing_lapangan',
            'status' => 'active',
        ]);
    }
}
