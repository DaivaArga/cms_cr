<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nama' => 'Super Admin',
                'alias' => 'superadmin',
                'hak_akses' => 'all',
            ],
            [
                'nama' => 'Admin',
                'alias' => 'admin',
                'hak_akses' => 'users,roles,shifts,kode_akses,struk',
            ],
            [
                'nama' => 'Manager',
                'alias' => 'manager',
                'hak_akses' => 'laporan,kelola_produk',
            ],
            [
                'nama' => 'Staff',
                'alias' => 'staff',
                'hak_akses' => 'laporan',
            ],
            [
                'nama' => 'Kasir',
                'alias' => 'kasir',
                'hak_akses' => 'transaksi',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['nama' => $role['nama']],
                [
                    'alias' => $role['alias'],
                    'hak_akses' => $role['hak_akses'],
                ]
            );
        }
    }
}
