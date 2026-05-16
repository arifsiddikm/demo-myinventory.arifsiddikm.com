<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Borrowing;
use App\Models\ActivityLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // USERS
        // =============================================
        $admin = User::create([
            'name'       => 'Administrator',
            'email'      => 'admin@mail.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'department' => 'IT',
            'phone'      => '081234567890',
            'is_active'  => true,
        ]);

        $budi = User::create([
            'name'       => 'Budi Santoso',
            'email'      => 'budi@mail.com',
            'password'   => Hash::make('password'),
            'role'       => 'karyawan',
            'department' => 'Finance',
            'phone'      => '081234567891',
            'is_active'  => true,
        ]);

        $siti = User::create([
            'name'       => 'Siti Rahayu',
            'email'      => 'siti@mail.com',
            'password'   => Hash::make('password'),
            'role'       => 'karyawan',
            'department' => 'HR',
            'phone'      => '081234567892',
            'is_active'  => true,
        ]);

        $andi = User::create([
            'name'       => 'Andi Wijaya',
            'email'      => 'andi@mail.com',
            'password'   => Hash::make('password'),
            'role'       => 'karyawan',
            'department' => 'Operations',
            'phone'      => '081234567893',
            'is_active'  => true,
        ]);

        $dewi = User::create([
            'name'       => 'Dewi Lestari',
            'email'      => 'dewi@mail.com',
            'password'   => Hash::make('password'),
            'role'       => 'karyawan',
            'department' => 'Marketing',
            'phone'      => '081234567894',
            'is_active'  => true,
        ]);

        // =============================================
        // CATEGORIES
        // =============================================
        $catATK        = Category::create(['name' => 'ATK',        'slug' => 'atk',        'description' => 'Alat Tulis Kantor']);
        $catElektronik = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik', 'description' => 'Perangkat elektronik']);
        $catFurnitur   = Category::create(['name' => 'Furnitur',   'slug' => 'furnitur',   'description' => 'Perabot kantor']);
        $catKebersihan = Category::create(['name' => 'Kebersihan', 'slug' => 'kebersihan', 'description' => 'Perlengkapan kebersihan']);
        $catKomputer   = Category::create(['name' => 'Komputer',   'slug' => 'komputer',   'description' => 'Perangkat komputer & aksesoris']);

        // =============================================
        // ITEMS
        // =============================================
        $items = [
            Item::create(['code' => 'ITM-001', 'name' => 'Pulpen Ballpoint',     'category_id' => $catATK->id,        'stock' => 150, 'min_stock' => 20, 'unit' => 'pcs',   'location' => 'Rak A-1',  'condition' => 'baik',        'description' => 'Pulpen ballpoint warna biru merk Pilot']),
            Item::create(['code' => 'ITM-002', 'name' => 'Kertas A4 80gr',       'category_id' => $catATK->id,        'stock' => 50,  'min_stock' => 10, 'unit' => 'rim',   'location' => 'Rak A-2',  'condition' => 'baik',        'description' => 'Kertas A4 80gsm satu rim isi 500 lembar']),
            Item::create(['code' => 'ITM-003', 'name' => 'Printer Canon IP2870', 'category_id' => $catElektronik->id, 'stock' => 5,   'min_stock' => 2,  'unit' => 'unit',  'location' => 'Gudang B', 'condition' => 'baik',        'description' => 'Printer inkjet warna, garansi resmi']),
            Item::create(['code' => 'ITM-004', 'name' => 'Kursi Kantor Ergonomis','category_id' => $catFurnitur->id,  'stock' => 12,  'min_stock' => 3,  'unit' => 'unit',  'location' => 'Gudang C', 'condition' => 'baik',        'description' => 'Kursi kantor ergonomis dengan sandaran tinggi']),
            Item::create(['code' => 'ITM-005', 'name' => 'Sabun Cuci Tangan',    'category_id' => $catKebersihan->id, 'stock' => 30,  'min_stock' => 10, 'unit' => 'botol', 'location' => 'Rak D-1',  'condition' => 'baik',        'description' => 'Sabun cair antiseptik 500ml']),
            Item::create(['code' => 'ITM-006', 'name' => 'Mouse Wireless',       'category_id' => $catKomputer->id,   'stock' => 20,  'min_stock' => 5,  'unit' => 'pcs',   'location' => 'Rak E-1',  'condition' => 'baik',        'description' => 'Mouse wireless optik 2.4GHz']),
            Item::create(['code' => 'ITM-007', 'name' => 'Keyboard USB',         'category_id' => $catKomputer->id,   'stock' => 15,  'min_stock' => 5,  'unit' => 'pcs',   'location' => 'Rak E-2',  'condition' => 'baik',        'description' => 'Keyboard USB standar 104 tombol']),
            Item::create(['code' => 'ITM-008', 'name' => 'Spidol Whiteboard',    'category_id' => $catATK->id,        'stock' => 40,  'min_stock' => 15, 'unit' => 'pcs',   'location' => 'Rak A-3',  'condition' => 'baik',        'description' => 'Spidol whiteboard berbagai warna']),
            Item::create(['code' => 'ITM-009', 'name' => 'Monitor LED 22"',      'category_id' => $catElektronik->id, 'stock' => 8,   'min_stock' => 2,  'unit' => 'unit',  'location' => 'Rak E-3',  'condition' => 'baik',        'description' => 'Monitor LED 22 inch resolusi Full HD']),
            Item::create(['code' => 'ITM-010', 'name' => 'Meja Kantor',          'category_id' => $catFurnitur->id,   'stock' => 6,   'min_stock' => 2,  'unit' => 'unit',  'location' => 'Gudang C', 'condition' => 'baik',        'description' => 'Meja kantor kayu ukuran 120x60cm']),
            Item::create(['code' => 'ITM-011', 'name' => 'Tinta Printer Hitam',  'category_id' => $catElektronik->id, 'stock' => 4,   'min_stock' => 5,  'unit' => 'botol', 'location' => 'Rak B-1',  'condition' => 'baik',        'description' => 'Tinta printer hitam refill 100ml']),
            Item::create(['code' => 'ITM-012', 'name' => 'Stapler Besar',        'category_id' => $catATK->id,        'stock' => 8,   'min_stock' => 3,  'unit' => 'pcs',   'location' => 'Rak A-4',  'condition' => 'rusak_ringan', 'description' => 'Stapler ukuran besar untuk hingga 50 lembar']),
            Item::create(['code' => 'ITM-013', 'name' => 'UPS APC 650VA',        'category_id' => $catElektronik->id, 'stock' => 3,   'min_stock' => 1,  'unit' => 'unit',  'location' => 'Rak B-2',  'condition' => 'baik',        'description' => 'UPS untuk server/komputer, backup 15 menit']),
            Item::create(['code' => 'ITM-014', 'name' => 'Sapu & Pengki Set',    'category_id' => $catKebersihan->id, 'stock' => 10,  'min_stock' => 4,  'unit' => 'set',   'location' => 'Rak D-2',  'condition' => 'baik',        'description' => 'Set sapu lantai dan pengki plastik']),
            Item::create(['code' => 'ITM-015', 'name' => 'Flashdisk 32GB',       'category_id' => $catKomputer->id,   'stock' => 3,   'min_stock' => 5,  'unit' => 'pcs',   'location' => 'Rak E-4',  'condition' => 'baik',        'description' => 'Flashdisk USB 3.0 kapasitas 32GB']),
        ];

        // =============================================
        // STOCK IN (BARANG MASUK)
        // =============================================
        $stockIns = [
            ['reference_no' => 'SI-2025-001', 'item_id' => $items[0]->id,  'user_id' => $admin->id, 'quantity' => 200, 'supplier' => 'Toko ATK Maju',         'price_per_unit' => 3500,    'received_date' => now()->subMonths(3)->format('Y-m-d'),             'notes' => 'Pembelian rutin bulanan'],
            ['reference_no' => 'SI-2025-002', 'item_id' => $items[1]->id,  'user_id' => $admin->id, 'quantity' => 30,  'supplier' => 'PT Sinar Dunia',         'price_per_unit' => 58000,   'received_date' => now()->subMonths(3)->subDays(5)->format('Y-m-d'),  'notes' => null],
            ['reference_no' => 'SI-2025-003', 'item_id' => $items[5]->id,  'user_id' => $admin->id, 'quantity' => 30,  'supplier' => 'Tokopedia Store',        'price_per_unit' => 150000,  'received_date' => now()->subMonths(3)->subDays(10)->format('Y-m-d'), 'notes' => 'Pengiriman sesuai pesanan'],
            ['reference_no' => 'SI-2025-004', 'item_id' => $items[7]->id,  'user_id' => $admin->id, 'quantity' => 50,  'supplier' => 'Toko ATK Maju',         'price_per_unit' => 8500,    'received_date' => now()->subMonths(2)->format('Y-m-d'),             'notes' => null],
            ['reference_no' => 'SI-2025-005', 'item_id' => $items[8]->id,  'user_id' => $admin->id, 'quantity' => 5,   'supplier' => 'PT Elektronik Jaya',    'price_per_unit' => 1350000, 'received_date' => now()->subMonths(2)->subDays(3)->format('Y-m-d'),  'notes' => 'Pembelian unit baru untuk ruang meeting'],
            ['reference_no' => 'SI-2025-006', 'item_id' => $items[10]->id, 'user_id' => $admin->id, 'quantity' => 10,  'supplier' => 'CV Tinta Jaya',         'price_per_unit' => 45000,   'received_date' => now()->subMonths(2)->subDays(7)->format('Y-m-d'),  'notes' => null],
            ['reference_no' => 'SI-2025-007', 'item_id' => $items[0]->id,  'user_id' => $admin->id, 'quantity' => 100, 'supplier' => 'Toko ATK Maju',         'price_per_unit' => 3500,    'received_date' => now()->subMonths(1)->format('Y-m-d'),             'notes' => 'Restock stok habis'],
            ['reference_no' => 'SI-2025-008', 'item_id' => $items[4]->id,  'user_id' => $admin->id, 'quantity' => 24,  'supplier' => 'Distributor Kebersihan', 'price_per_unit' => 22000,   'received_date' => now()->subMonths(1)->subDays(5)->format('Y-m-d'),  'notes' => 'Pembelian bulanan kebersihan'],
            ['reference_no' => 'SI-2025-009', 'item_id' => $items[6]->id,  'user_id' => $admin->id, 'quantity' => 10,  'supplier' => 'Shopee Wholesale',      'price_per_unit' => 125000,  'received_date' => now()->subMonths(1)->subDays(12)->format('Y-m-d'), 'notes' => null],
            ['reference_no' => 'SI-2025-010', 'item_id' => $items[14]->id, 'user_id' => $admin->id, 'quantity' => 5,   'supplier' => 'Lazada Store',          'price_per_unit' => 85000,   'received_date' => now()->subWeek()->format('Y-m-d'),                'notes' => 'Penambahan stok flashdisk'],
            ['reference_no' => 'SI-2025-011', 'item_id' => $items[1]->id,  'user_id' => $admin->id, 'quantity' => 20,  'supplier' => 'PT Sinar Dunia',        'price_per_unit' => 58000,   'received_date' => now()->subDays(5)->format('Y-m-d'),               'notes' => null],
            ['reference_no' => 'SI-2025-012', 'item_id' => $items[11]->id, 'user_id' => $admin->id, 'quantity' => 5,   'supplier' => 'Toko ATK Maju',         'price_per_unit' => 35000,   'received_date' => now()->subDays(2)->format('Y-m-d'),               'notes' => 'Ganti stapler yang rusak'],
        ];

        foreach ($stockIns as $si) {
            StockIn::create($si);
        }

        // =============================================
        // STOCK OUT (BARANG KELUAR)
        // =============================================
        $stockOuts = [
            ['reference_no' => 'SO-2025-001', 'item_id' => $items[0]->id,  'user_id' => $admin->id, 'quantity' => 30, 'purpose' => 'Distribusi Divisi Finance',     'recipient' => 'Budi Santoso',   'issued_date' => now()->subMonths(3)->addDays(2)->format('Y-m-d'),  'notes' => null],
            ['reference_no' => 'SO-2025-002', 'item_id' => $items[1]->id,  'user_id' => $admin->id, 'quantity' => 5,  'purpose' => 'Kebutuhan cetak laporan',       'recipient' => 'Dewi Lestari',   'issued_date' => now()->subMonths(3)->addDays(5)->format('Y-m-d'),  'notes' => 'Untuk keperluan presentasi Q1'],
            ['reference_no' => 'SO-2025-003', 'item_id' => $items[7]->id,  'user_id' => $admin->id, 'quantity' => 10, 'purpose' => 'Distribusi ke ruang rapat',     'recipient' => 'Siti Rahayu',    'issued_date' => now()->subMonths(2)->format('Y-m-d'),              'notes' => null],
            ['reference_no' => 'SO-2025-004', 'item_id' => $items[4]->id,  'user_id' => $admin->id, 'quantity' => 12, 'purpose' => 'Distribusi ke semua lantai',    'recipient' => 'Tim Kebersihan', 'issued_date' => now()->subMonths(2)->addDays(3)->format('Y-m-d'),  'notes' => 'Untuk protokol kebersihan'],
            ['reference_no' => 'SO-2025-005', 'item_id' => $items[5]->id,  'user_id' => $admin->id, 'quantity' => 5,  'purpose' => 'Penggantian mouse rusak',       'recipient' => 'Andi Wijaya',    'issued_date' => now()->subMonths(2)->addDays(10)->format('Y-m-d'), 'notes' => null],
            ['reference_no' => 'SO-2025-006', 'item_id' => $items[0]->id,  'user_id' => $admin->id, 'quantity' => 20, 'purpose' => 'Distribusi Divisi HR',          'recipient' => 'Siti Rahayu',    'issued_date' => now()->subMonths(1)->format('Y-m-d'),              'notes' => null],
            ['reference_no' => 'SO-2025-007', 'item_id' => $items[6]->id,  'user_id' => $admin->id, 'quantity' => 3,  'purpose' => 'Penggantian keyboard rusak',    'recipient' => 'Andi Wijaya',    'issued_date' => now()->subMonths(1)->addDays(7)->format('Y-m-d'),  'notes' => null],
            ['reference_no' => 'SO-2025-008', 'item_id' => $items[10]->id, 'user_id' => $admin->id, 'quantity' => 4,  'purpose' => 'Isi ulang printer divisi',      'recipient' => 'Budi Santoso',   'issued_date' => now()->subMonths(1)->addDays(14)->format('Y-m-d'), 'notes' => 'Untuk printer ruang Finance'],
            ['reference_no' => 'SO-2025-009', 'item_id' => $items[1]->id,  'user_id' => $admin->id, 'quantity' => 3,  'purpose' => 'Cetak dokumen proyek',          'recipient' => 'Dewi Lestari',   'issued_date' => now()->subWeeks(2)->format('Y-m-d'),               'notes' => null],
            ['reference_no' => 'SO-2025-010', 'item_id' => $items[4]->id,  'user_id' => $admin->id, 'quantity' => 6,  'purpose' => 'Restock wastafel lantai 2',     'recipient' => 'Tim Kebersihan', 'issued_date' => now()->subWeek()->format('Y-m-d'),                 'notes' => null],
            ['reference_no' => 'SO-2025-011', 'item_id' => $items[0]->id,  'user_id' => $admin->id, 'quantity' => 25, 'purpose' => 'Distribusi Divisi Marketing',   'recipient' => 'Dewi Lestari',   'issued_date' => now()->subDays(4)->format('Y-m-d'),                'notes' => null],
            ['reference_no' => 'SO-2025-012', 'item_id' => $items[13]->id, 'user_id' => $admin->id, 'quantity' => 2,  'purpose' => 'Lantai 3 area pantry',          'recipient' => 'Tim Kebersihan', 'issued_date' => now()->subDays(1)->format('Y-m-d'),                'notes' => null],
        ];

        foreach ($stockOuts as $so) {
            StockOut::create($so);
        }

        // =============================================
        // BORROWINGS (PEMINJAMAN)
        // =============================================
        $borrowings = [
            // Returned (sudah dikembalikan)
            [
                'reference_no'         => 'BRW-2025-001',
                'item_id'              => $items[2]->id,
                'borrower_id'          => $budi->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $budi->name,
                'quantity'             => 1,
                'borrow_date'          => now()->subMonths(2)->format('Y-m-d'),
                'expected_return_date' => now()->subMonths(2)->addDays(7)->format('Y-m-d'),
                'actual_return_date'   => now()->subMonths(2)->addDays(6)->format('Y-m-d'),
                'status'               => 'returned',
                'purpose'              => 'Cetak laporan keuangan tahunan',
                'notes'                => 'Dipinjam untuk acara audit',
            ],
            [
                'reference_no'         => 'BRW-2025-002',
                'item_id'              => $items[8]->id,
                'borrower_id'          => $siti->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $siti->name,
                'quantity'             => 1,
                'borrow_date'          => now()->subMonths(2)->addDays(5)->format('Y-m-d'),
                'expected_return_date' => now()->subMonths(2)->addDays(12)->format('Y-m-d'),
                'actual_return_date'   => now()->subMonths(2)->addDays(11)->format('Y-m-d'),
                'status'               => 'returned',
                'purpose'              => 'Presentasi rekrutmen karyawan baru',
                'notes'                => null,
            ],
            [
                'reference_no'         => 'BRW-2025-003',
                'item_id'              => $items[3]->id,
                'borrower_id'          => $andi->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $andi->name,
                'quantity'             => 2,
                'borrow_date'          => now()->subMonths(1)->addDays(3)->format('Y-m-d'),
                'expected_return_date' => now()->subMonths(1)->addDays(10)->format('Y-m-d'),
                'actual_return_date'   => now()->subMonths(1)->addDays(9)->format('Y-m-d'),
                'status'               => 'returned',
                'purpose'              => 'Meeting room setup lantai 4',
                'notes'                => null,
            ],
            // Approved (masih dipinjam)
            [
                'reference_no'         => 'BRW-2025-004',
                'item_id'              => $items[9]->id,
                'borrower_id'          => $dewi->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $dewi->name,
                'quantity'             => 1,
                'borrow_date'          => now()->subDays(10)->format('Y-m-d'),
                'expected_return_date' => now()->addDays(4)->format('Y-m-d'),
                'actual_return_date'   => null,
                'status'               => 'approved',
                'purpose'              => 'Workstation sementara divisi marketing',
                'notes'                => 'Meja untuk project campaign Q2',
            ],
            [
                'reference_no'         => 'BRW-2025-005',
                'item_id'              => $items[12]->id,
                'borrower_id'          => $budi->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $budi->name,
                'quantity'             => 1,
                'borrow_date'          => now()->subDays(5)->format('Y-m-d'),
                'expected_return_date' => now()->addDays(9)->format('Y-m-d'),
                'actual_return_date'   => null,
                'status'               => 'approved',
                'purpose'              => 'Backup listrik server ruang Finance',
                'notes'                => null,
            ],
            // Pending (menunggu persetujuan)
            [
                'reference_no'         => 'BRW-2025-006',
                'item_id'              => $items[2]->id,
                'borrower_id'          => $siti->id,
                'approved_by'          => null,
                'borrower_name'        => $siti->name,
                'quantity'             => 1,
                'borrow_date'          => now()->addDays(2)->format('Y-m-d'),
                'expected_return_date' => now()->addDays(9)->format('Y-m-d'),
                'actual_return_date'   => null,
                'status'               => 'pending',
                'purpose'              => 'Cetak materi onboarding karyawan baru',
                'notes'                => 'Untuk bulan depan batch rekrutmen',
            ],
            [
                'reference_no'         => 'BRW-2025-007',
                'item_id'              => $items[8]->id,
                'borrower_id'          => $andi->id,
                'approved_by'          => null,
                'borrower_name'        => $andi->name,
                'quantity'             => 1,
                'borrow_date'          => now()->addDays(1)->format('Y-m-d'),
                'expected_return_date' => now()->addDays(5)->format('Y-m-d'),
                'actual_return_date'   => null,
                'status'               => 'pending',
                'purpose'              => 'Demo produk ke client penting',
                'notes'                => 'Mohon segera diproses',
            ],
            // Rejected
            [
                'reference_no'         => 'BRW-2025-008',
                'item_id'              => $items[5]->id,
                'borrower_id'          => $dewi->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $dewi->name,
                'quantity'             => 5,
                'borrow_date'          => now()->subWeeks(3)->format('Y-m-d'),
                'expected_return_date' => now()->subWeeks(3)->addDays(7)->format('Y-m-d'),
                'actual_return_date'   => null,
                'status'               => 'rejected',
                'purpose'              => 'Event pameran produk eksternal',
                'notes'                => null,
                'reject_reason'        => 'Stok tidak mencukupi, max peminjaman 2 unit',
            ],
            // Overdue (terlambat dikembalikan)
            [
                'reference_no'         => 'BRW-2025-009',
                'item_id'              => $items[6]->id,
                'borrower_id'          => $budi->id,
                'approved_by'          => $admin->id,
                'borrower_name'        => $budi->name,
                'quantity'             => 1,
                'borrow_date'          => now()->subDays(20)->format('Y-m-d'),
                'expected_return_date' => now()->subDays(13)->format('Y-m-d'),
                'actual_return_date'   => null,
                'status'               => 'overdue',
                'purpose'              => 'Keyboard pengganti yang rusak sementara',
                'notes'                => 'Belum dikembalikan, sudah melewati batas waktu',
            ],
        ];

        foreach ($borrowings as $b) {
            Borrowing::create($b);
        }

        // =============================================
        // ACTIVITY LOGS
        // =============================================
        $logs = [
            ['user_id' => $admin->id, 'action' => 'login',  'model_type' => null,       'model_id' => null, 'description' => 'User Administrator berhasil login',     'ip_address' => '127.0.0.1'],
            ['user_id' => $admin->id, 'action' => 'create', 'model_type' => 'StockIn',  'model_id' => 1,    'description' => 'Barang masuk ITM-001 sebanyak 200 pcs', 'ip_address' => '127.0.0.1'],
            ['user_id' => $admin->id, 'action' => 'create', 'model_type' => 'StockIn',  'model_id' => 2,    'description' => 'Barang masuk ITM-002 sebanyak 30 rim',  'ip_address' => '127.0.0.1'],
            ['user_id' => $budi->id,  'action' => 'login',  'model_type' => null,       'model_id' => null, 'description' => 'User Budi Santoso berhasil login',       'ip_address' => '192.168.1.10'],
            ['user_id' => $admin->id, 'action' => 'create', 'model_type' => 'Borrowing','model_id' => 1,    'description' => 'Peminjaman BRW-2025-001 disetujui',      'ip_address' => '127.0.0.1'],
            ['user_id' => $siti->id,  'action' => 'login',  'model_type' => null,       'model_id' => null, 'description' => 'User Siti Rahayu berhasil login',        'ip_address' => '192.168.1.11'],
            ['user_id' => $admin->id, 'action' => 'create', 'model_type' => 'StockOut', 'model_id' => 1,    'description' => 'Barang keluar ITM-001 sebanyak 30 pcs',  'ip_address' => '127.0.0.1'],
            ['user_id' => $admin->id, 'action' => 'update', 'model_type' => 'Item',     'model_id' => 3,    'description' => 'Update stok ITM-003 Printer Canon',      'ip_address' => '127.0.0.1'],
            ['user_id' => $andi->id,  'action' => 'login',  'model_type' => null,       'model_id' => null, 'description' => 'User Andi Wijaya berhasil login',         'ip_address' => '192.168.1.12'],
            ['user_id' => $admin->id, 'action' => 'create', 'model_type' => 'Borrowing','model_id' => 4,    'description' => 'Peminjaman BRW-2025-004 disetujui',      'ip_address' => '127.0.0.1'],
            ['user_id' => $dewi->id,  'action' => 'login',  'model_type' => null,       'model_id' => null, 'description' => 'User Dewi Lestari berhasil login',        'ip_address' => '192.168.1.13'],
            ['user_id' => $admin->id, 'action' => 'create', 'model_type' => 'StockIn',  'model_id' => 10,   'description' => 'Barang masuk ITM-015 sebanyak 5 pcs',    'ip_address' => '127.0.0.1'],
        ];

        foreach ($logs as $log) {
            ActivityLog::create($log);
        }
    }
}
