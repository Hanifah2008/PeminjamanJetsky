<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Alat;
use App\Models\Rating;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Kategori Alat Sky dan Dron
        $kategoriFoundation = Kategori::create(['name' => 'Sky']);
        $kategoriLips = Kategori::create(['name' => 'Dron']);
        $kategoriEyes = Kategori::create(['name' => 'Aksesoris']);
        $kategoriWajah = Kategori::create(['name' => 'Perlengkapan Pendukung']);

        // Buat Alat Sky dan Dron Wardah
        Alat::create([
            'kategori_id' => $kategoriFoundation->id,
            'name' => 'Sky Camera Pro 4K',
            'harga' => 150000,
            'diskon_persen' => 15,
            'deskripsi_promo' => 'Promo Sky! Diskon 15% untuk semua Sky Camera',
            'stok' => 10,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriFoundation->id,
            'name' => 'Sky Gimbal Stabilizer',
            'harga' => 80000,
            'diskon_persen' => 15,
            'deskripsi_promo' => 'Promo Sky! Diskon 15% untuk semua Sky Camera',
            'stok' => 15,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriLips->id,
            'name' => 'Dron DJI Mavic 3',
            'harga' => 200000,
            'diskon_persen' => 20,
            'deskripsi_promo' => 'Promo Drone! Diskon 20% untuk semua Drone',
            'stok' => 5,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriLips->id,
            'name' => 'Dron DJI Mini 3 Pro',
            'harga' => 85000,
            'diskon_persen' => 20,
            'deskripsi_promo' => 'Promo Drone! Diskon 20% untuk semua Drone',
            'stok' => 12,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriLips->id,
            'name' => 'Dron Parrot Anafi',
            'harga' => 90000,
            'diskon_persen' => 20,
            'deskripsi_promo' => 'Promo Drone! Diskon 20% untuk semua Drone',
            'stok' => 8,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriEyes->id,
            'name' => 'Kamera Sony A7III',
            'harga' => 170000,
            'diskon_persen' => 0,
            'deskripsi_promo' => null,
            'stok' => 6,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriEyes->id,
            'name' => 'Kamera GoPro Hero 11',
            'harga' => 55000,
            'diskon_persen' => 0,
            'deskripsi_promo' => null,
            'stok' => 20,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriFoundation->id,
            'name' => 'Tripod Manfrotto Pro',
            'harga' => 25000,
            'diskon_persen' => 0,
            'deskripsi_promo' => null,
            'stok' => 25,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriWajah->id,
            'name' => 'Lighting Kit Professional',
            'harga' => 60000,
            'diskon_persen' => 0,
            'deskripsi_promo' => null,
            'stok' => 10,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        Alat::create([
            'kategori_id' => $kategoriWajah->id,
            'name' => 'Microphone Rode Wireless',
            'harga' => 35000,
            'diskon_persen' => 0,
            'deskripsi_promo' => null,
            'stok' => 18,
            'gambar' => null,
            'use_progressive_pricing' => true,
        ]);

        // Buat akun Admin
        User::create([
            'name' => 'Admin Kasir',
            'email' => 'admin@kasir.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        // Buat akun Petugas
        User::create([
            'name' => 'Petugas Kasir',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'petugas',
            'phone' => '081234567899',
            'address' => 'Jl. Kasir No. 456, Jakarta',
        ]);

        // Buat akun Customer utama
        $customer = User::create([
            'name' => 'Bila Customer',
            'email' => 'bila@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
        ]);

        // Buat Rating untuk Tripod Manfrotto Pro (9 rating dengan rata-rata 4.5 bintang)
        // Perlu buat 9 user untuk masing-masing rating
        $tripod = Alat::where('name', 'Tripod Manfrotto Pro')->first();
        if ($tripod) {
            // 4 user dengan rating 5 bintang
            for ($i = 1; $i <= 4; $i++) {
                $user = User::create([
                    'name' => 'Customer Reviewer ' . $i,
                    'email' => 'reviewer' . $i . '@gmail.com',
                    'password' => bcrypt('123456'),
                    'role' => 'customer',
                ]);
                Rating::create([
                    'user_id' => $user->id,
                    'alat_id' => $tripod->id,
                    'bintang' => 5,
                    'komentar' => 'Tripod berkualitas tinggi, stabil dan kokoh untuk shooting profesional!',
                ]);
            }
            
            // 5 user dengan rating 4 bintang (untuk membuat rata-rata 4.44 ≈ 4.5)
            for ($i = 5; $i <= 9; $i++) {
                $user = User::create([
                    'name' => 'Customer Reviewer ' . $i,
                    'email' => 'reviewer' . $i . '@gmail.com',
                    'password' => bcrypt('123456'),
                    'role' => 'customer',
                ]);
                Rating::create([
                    'user_id' => $user->id,
                    'alat_id' => $tripod->id,
                    'bintang' => 4,
                    'komentar' => 'Tripod bagus, stabil tapi agak berat untuk dibawa perjalanan.',
                ]);
            }
        }

        // Tambah Rating untuk Sky Camera Pro 4K (5 rating dengan 5 bintang rata-rata)
        $camera = Alat::where('name', 'Sky Camera Pro 4K')->first();
        if ($camera) {
            for ($i = 10; $i <= 14; $i++) {
                $user = User::create([
                    'name' => 'Camera Lover ' . ($i-9),
                    'email' => 'camera' . ($i-9) . '@gmail.com',
                    'password' => bcrypt('123456'),
                    'role' => 'customer',
                ]);
                Rating::create([
                    'user_id' => $user->id,
                    'alat_id' => $camera->id,
                    'bintang' => 5,
                    'komentar' => 'Kamera 4K Sky terbaik! Kualitas gambar jernih, fitur lengkap, sangat profesional!',
                ]);
            }
        }

        // Rating untuk Dron DJI Mavic 3 (5 rating dengan 5 bintang)
        $dronMavic = Alat::where('name', 'Dron DJI Mavic 3')->first();
        if ($dronMavic) {
            for ($i = 15; $i <= 19; $i++) {
                $user = User::create([
                    'name' => 'Drone Enthusiast ' . ($i-14),
                    'email' => 'drone' . ($i-14) . '@gmail.com',
                    'password' => bcrypt('123456'),
                    'role' => 'customer',
                ]);
                Rating::create([
                    'user_id' => $user->id,
                    'alat_id' => $dronMavic->id,
                    'bintang' => 5,
                    'komentar' => 'Dron DJI Mavic 3 terbaik! Stabilitas sempurna, baterai tahan lama, hasil foto luar biasa!',
                ]);
            }
        }

        // Rating untuk Dron DJI Mini 3 Pro (5 rating dengan 5 bintang)
        $dronMini = Alat::where('name', 'Dron DJI Mini 3 Pro')->first();
        if ($dronMini) {
            for ($i = 20; $i <= 24; $i++) {
                $user = User::create([
                    'name' => 'Sky Producer ' . ($i-19),
                    'email' => 'skyproducer' . ($i-19) . '@gmail.com',
                    'password' => bcrypt('123456'),
                    'role' => 'customer',
                ]);
                Rating::create([
                    'user_id' => $user->id,
                    'alat_id' => $dronMini->id,
                    'bintang' => 5,
                    'komentar' => 'Dron Mini 3 Pro ringan namun powerful! Cocok untuk pemula dan profesional, kualitas terbaik!',
                ]);
            }
        }

        // Rating untuk Lighting Kit Professional (5 rating dengan 5 bintang)
        $lighting = Alat::where('name', 'Lighting Kit Professional')->first();
        if ($lighting) {
            for ($i = 25; $i <= 29; $i++) {
                $user = User::create([
                    'name' => 'Lighting Expert ' . ($i-24),
                    'email' => 'lighting' . ($i-24) . '@gmail.com',
                    'password' => bcrypt('123456'),
                    'role' => 'customer',
                ]);
                Rating::create([
                    'user_id' => $user->id,
                    'alat_id' => $lighting->id,
                    'bintang' => 5,
                    'komentar' => 'Lighting kit profesional dengan kualitas terbaik! Pencahayaan sempurna untuk studio dan outdoor!',
                ]);
            }
        }

        // Buat data transaksi dummy
        $this->createDummyTransactions($customer);
    }

    private function createDummyTransactions($customer)
    {
        // Ambil alat yang ada
        $alats = Alat::limit(5)->get();

        if ($alats->isEmpty()) {
            return; // Jika tidak ada alat, skip seeding transaksi
        }

        // Transaksi 1 - Selesai
        $transaksi1 = Transaksi::create([
            'user_id' => $customer->id,
            'total' => 350000,
            'kasir_name' => 'Admin Kasir',
            'status' => 'selesai',
        ]);

        // Detail transaksi 1
        TransaksiDetail::create([
            'transaksi_id' => $transaksi1->id,
            'alat_id' => $alats[0]->id ?? 1,
            'qty' => 2,
            'harga' => 125000,
        ]);

        TransaksiDetail::create([
            'transaksi_id' => $transaksi1->id,
            'alat_id' => $alats[1]->id ?? 2,
            'qty' => 1,
            'harga' => 100000,
        ]);

        // Transaksi 2 - Pending
        $transaksi2 = Transaksi::create([
            'user_id' => $customer->id,
            'total' => 250000,
            'kasir_name' => 'Admin Kasir',
            'status' => 'pending',
        ]);

        // Detail transaksi 2
        TransaksiDetail::create([
            'transaksi_id' => $transaksi2->id,
            'alat_id' => $alats[2]->id ?? 3,
            'qty' => 1,
            'harga' => 250000,
        ]);

        // Transaksi 3 - Selesai
        $transaksi3 = Transaksi::create([
            'user_id' => $customer->id,
            'total' => 500000,
            'kasir_name' => 'Admin Kasir',
            'status' => 'selesai',
        ]);

        // Detail transaksi 3
        if (isset($alats[3])) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi3->id,
                'alat_id' => $alats[3]->id,
                'qty' => 2,
                'harga' => 150000,
            ]);
        }

        if (isset($alats[4])) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi3->id,
                'alat_id' => $alats[4]->id,
                'qty' => 1,
                'harga' => 200000,
            ]);
        }
    }
}
