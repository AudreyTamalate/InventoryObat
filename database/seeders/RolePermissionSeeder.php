<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat roles
        $apoteker = Role::firstOrCreate(['name' => 'apoteker']);
        $kepala   = Role::firstOrCreate(['name' => 'kepala_klinik']);

        // Buat permissions
        $permissions = [
            'kelola obat',
            'kelola obat masuk',
            'kelola obat keluar',
            'laporan stok',
            'laporan keuangan',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permission ke role
        $apoteker->givePermissionTo([
            'kelola obat',
            'kelola obat masuk',
            'kelola obat keluar',
            'laporan stok',
        ]);

        $kepala->givePermissionTo(Permission::all()); // akses semua

        // Buat user apoteker
        $userApoteker = User::firstOrCreate(
            ['email' => 'apoteker@gmail.com'],
            [
                'name' => 'Apoteker',
                'password' => bcrypt('Init123!'),
            ]
        );
        $userApoteker->assignRole('apoteker');

        // Buat user kepala klinik
        $userKepala = User::firstOrCreate(
            ['email' => 'kepalaklinik@gmail.com'],
            [
                'name' => 'Kepala Klinik',
                'password' => bcrypt('Init234!'),
            ]
        );
        $userKepala->assignRole('kepala_klinik');
    }
}
