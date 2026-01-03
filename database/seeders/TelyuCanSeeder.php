<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Menu;

class TelyuCanSeeder extends Seeder
{
    public function run()
    {
        // 0. Buat Admin
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin TelyuCan',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 0. Buat Customer (untuk testing)
        \App\Models\User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Customer Test',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]
        );

        // 1. Buat Tenants dengan Users
        $t1User = \App\Models\User::firstOrCreate(
            ['email' => 'tult@gmail.com'],
            [
                'name' => 'Kantin TULT User',
                'password' => bcrypt('password'),
                'role' => 'tenant',
            ]
        );
        $t1 = Tenant::firstOrCreate(
            ['id_user' => $t1User->id_user],
            [
                'name' => 'Kantin TULT', 
                'description' => 'Masakan lezat di gedung TULT.',
                'owner_name' => 'Bu Rini',
                'phone' => '081234567890',
                'address' => 'Gedung TULT Lt. 1',
                'city' => 'Bandung'
            ]
        );

        $t2User = \App\Models\User::firstOrCreate(
            ['email' => 'gku@gmail.com'],
            [
                'name' => 'Kantin GKU User',
                'password' => bcrypt('password'),
                'role' => 'tenant',
            ]
        );
        $t2 = Tenant::firstOrCreate(
            ['id_user' => $t2User->id_user],
            [
                'name' => 'Kantin GKU', 
                'description' => 'Favorit mahasiswa rekayasa.',
                'owner_name' => 'Pak Asep',
                'phone' => '081234567891',
                'address' => 'Gedung GKU',
                'city' => 'Bandung'
            ]
        );

        $t3User = \App\Models\User::firstOrCreate(
            ['email' => 'pujasera@gmail.com'],
            [
                'name' => 'Pujasera User',
                'password' => bcrypt('password'),
                'role' => 'tenant',
            ]
        );
        $t3 = Tenant::firstOrCreate(
            ['id_user' => $t3User->id_user],
            [
                'name' => 'Pujasera',
                'description' => 'Pilihan lengkap, harga hemat.',
                'owner_name' => 'Bu Susi',
                'phone' => '081234567892',
                'address' => 'Pujasera Area',
                'city' => 'Bandung'
            ]
        );

        // 3. Buat Kategori
        $catFood = \App\Models\Category::firstOrCreate(['name' => 'Makanan']);
        $catDrink = \App\Models\Category::firstOrCreate(['name' => 'Minuman']);

        // 4. Buat Menu
        $menus = [
            [
                'id_tenant' => $t1->id_tenant,
                'name' => 'Ayam Geprek Bu Rini',
                'price' => 15000,
                'stock' => 20,
                'id_category' => $catFood->id_category,
                'description' => 'Ayam geprek pedas nampol dengan nasi hangat.',
                'image' => 'https://images.unsplash.com/photo-1696340034876-6245523babfa?q=80&w=600'
            ],
            [
                'id_tenant' => $t1->id_tenant,
                'name' => 'Es Teh Manis',
                'price' => 5000,
                'stock' => 50,
                'id_category' => $catDrink->id_category,
                'description' => 'Es teh manis segar pelepas dahaga.',
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=600'
            ],
            [
                'id_tenant' => $t2->id_tenant,
                'name' => 'Nasi Goreng Mas Yoga',
                'price' => 18000,
                'stock' => 15,
                'id_category' => $catFood->id_category,
                'description' => 'Nasi goreng spesial dengan telur dan sosis.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=600'
            ],
            [
                'id_tenant' => $t3->id_tenant,
                'name' => 'Bakso Malang Karapitan',
                'price' => 20000,
                'stock' => 10,
                'id_category' => $catFood->id_category,
                'description' => 'Bakso malang komplit dengan pangsit.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=600'
            ],
        ];

        foreach($menus as $m) {
            Menu::firstOrCreate(
                ['name' => $m['name'], 'id_tenant' => $m['id_tenant']],
                $m
            );
        }
    }
}
