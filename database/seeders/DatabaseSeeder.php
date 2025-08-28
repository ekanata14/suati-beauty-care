<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HomeContent;
use App\Models\Produk;
use App\Models\ProdukSize;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        HomeContent::create([
            'title' => 'SUATI BEUATY CARE',
            'description' => 'Welcome to Suati Beauty Care, your one-stop destination for all your beauty needs. We offer a wide range of services and products to help you look and feel your best.',
            'logo' => 'logo.jpeg', // Assuming you have a logo file in the public directory
        ]);

        foreach (
            [
                'Perawatan Wajah',
                'Perawatan Tubuh',
                'Makeup',
                'Hair Care',
                'Aksesoris'
            ] as $nama
        ) {
            \App\Models\Kategori::create(['nama' => $nama]);
        }
        // Create dummy Produk data for clothes
        $produk3 = Produk::create([
            'id_kategori' => 5, // Aksesoris or create a new category for clothes if needed
            'nama' => 'T-Shirt',
            'stok' => 100,
            'harga' => 85000,
            'deskripsi' => 'Comfortable cotton T-Shirt available in various sizes.',
            'foto_produk' => 'tshirt.jpg',
        ]);

        $produk4 = Produk::create([
            'id_kategori' => 5,
            'nama' => 'Dress',
            'stok' => 60,
            'harga' => 150000,
            'deskripsi' => 'Elegant summer dress for all occasions.',
            'foto_produk' => 'dress.jpg',
        ]);

        // Create dummy ProdukSize data related to clothes
        ProdukSize::create([
            'id_produk' => $produk3->id,
            'size' => 'S',
            'stock' => 25,
        ]);
        ProdukSize::create([
            'id_produk' => $produk3->id,
            'size' => 'M',
            'stock' => 35,
        ]);
        ProdukSize::create([
            'id_produk' => $produk3->id,
            'size' => 'L',
            'stock' => 40,
        ]);

        ProdukSize::create([
            'id_produk' => $produk4->id,
            'size' => 'S',
            'stock' => 20,
        ]);
        ProdukSize::create([
            'id_produk' => $produk4->id,
            'size' => 'M',
            'stock' => 20,
        ]);
        ProdukSize::create([
            'id_produk' => $produk4->id,
            'size' => 'L',
            'stock' => 20,
        ]);
    }
}
