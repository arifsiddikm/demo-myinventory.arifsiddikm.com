# CLAUDE PROMPT — MyInventory Web App
> Upload file ini ke Claude, lalu ketik: **"Buatkan website ini sesuai PRD di atas"**

---

## 🧾 PRD (Product Requirements Document)

### Nama Proyek
**MyInventory** — Sistem Informasi Manajemen Inventaris Barang Perusahaan

### Tech Stack
- **Framework:** Laravel 12 (PHP 8.3+)
- **Database:** SQLite (default) atau MySQL
- **Frontend:** Blade template + Tailwind CSS (via CDN)
- **Icons:** Font Awesome 6 (via CDN)
- **PDF Export:** barryvdh/laravel-dompdf
- **Excel Export:** maatwebsite/excel
- **Alert/Confirm:** SweetAlert2 (via CDN)
- **Charts:** Chart.js (via CDN)
- **Auth:** Session-based (built-in Laravel Auth)

---

## 🗂️ Database Schema

### Tabel `users`
```
id, name, email, password, role (enum: admin|karyawan), department, phone, is_active (boolean), remember_token, timestamps
```

### Tabel `categories`
```
id, name, slug (unique), description, timestamps
```

### Tabel `items`
```
id, code (unique), name, category_id (FK), stock (int, default 0), min_stock (int, default 5), unit (default: pcs), location, description, image, condition (enum: baik|rusak_ringan|rusak_berat, default: baik), is_active (boolean, default: true), timestamps
```

### Tabel `stock_ins`
```
id, reference_no (unique), item_id (FK), user_id (FK), quantity (int), supplier, price_per_unit (decimal 15,2 nullable), received_date (date), notes, timestamps
```

### Tabel `stock_outs`
```
id, reference_no (unique), item_id (FK), user_id (FK), quantity (int), purpose, recipient, issued_date (date), notes, timestamps
```

### Tabel `borrowings`
```
id, reference_no (unique), item_id (FK), borrower_id (FK → users), approved_by (FK → users, nullable), borrower_name, quantity (int), borrow_date (date), expected_return_date (date), actual_return_date (date nullable), status (enum: pending|approved|rejected|returned|overdue, default: pending), purpose, notes, reject_reason, timestamps
```

### Tabel `activity_logs`
```
id, user_id (FK nullable), action, model_type (nullable), model_id (nullable), description, ip_address, timestamps
```

---

## 👤 Role & Permission

| Fitur                        | Admin | Karyawan |
|------------------------------|:-----:|:--------:|
| Dashboard                    | ✅    | ✅       |
| Inventaris — lihat           | ✅    | ✅       |
| Inventaris — tambah/edit/hapus | ✅  | ❌       |
| Barang Masuk — lihat         | ✅    | ✅       |
| Barang Masuk — tambah        | ✅    | ❌       |
| Barang Keluar — lihat        | ✅    | ✅       |
| Barang Keluar — tambah       | ✅    | ❌       |
| Peminjaman — lihat           | ✅    | ✅ (milik sendiri) |
| Peminjaman — buat request    | ✅    | ✅       |
| Peminjaman — approve/reject  | ✅    | ❌       |
| Laporan & Export             | ✅    | ❌       |
| Manajemen User               | ✅    | ❌       |

---

## 📄 Halaman & Fitur Detail

### 1. Halaman Login (`/login`)
- Form email + password
- Validasi: email & password wajib
- Cek `is_active` user, jika false tampilkan pesan "Akun dinonaktifkan"
- Redirect ke dashboard setelah login
- Flash message sukses/error menggunakan SweetAlert2

### 2. Dashboard (`/`)
Widget ringkasan:
- Total item aktif
- Barang masuk bulan ini (sum quantity)
- Barang keluar bulan ini (sum quantity)
- Peminjaman aktif (status = approved)
- Peminjaman pending (butuh persetujuan)
- Item stok menipis (stock ≤ min_stock)
- Total user aktif

Tabel bawah:
- **Aktivitas Terbaru** — 10 log terbaru dengan nama user, aksi, deskripsi, waktu
- **Peminjaman Terbaru** — 5 terbaru dengan status badge berwarna
- **Peringatan Stok Menipis** — item dengan stock ≤ min_stock

### 3. Inventaris Barang (`/items`)
- Tabel: Kode, Nama, Kategori, Stok, Min Stok, Satuan, Lokasi, Kondisi, Status Aktif, Aksi
- Filter: pencarian nama/kode, filter kategori
- Indikator warna stok: merah jika stock ≤ min_stock
- Badge kondisi: Baik (hijau), Rusak Ringan (kuning), Rusak Berat (merah)
- Tombol: Lihat Detail, Edit, Hapus (dengan konfirmasi SweetAlert)
- Halaman detail item: info lengkap + riwayat stock in/out + riwayat peminjaman

**Form Tambah/Edit Item:**
- Kode barang (auto-generate atau manual, unique)
- Nama barang (required)
- Kategori (required, dropdown dari tabel categories)
- Stok awal (int)
- Stok minimum (int, untuk trigger warning)
- Satuan (pcs, rim, unit, botol, set, lembar, dll)
- Lokasi penyimpanan
- Deskripsi
- Upload gambar (optional, simpan ke storage/public)
- Kondisi (Baik / Rusak Ringan / Rusak Berat)
- Status aktif (toggle)

### 4. Barang Masuk (`/stock-in`)
- Tabel: No. Referensi, Barang, Jumlah, Supplier, Harga/Unit, Tanggal Terima, Dicatat Oleh
- Form tambah: pilih item, jumlah, supplier, harga per unit, tanggal terima, catatan
- Saat submit: stok item otomatis **bertambah** sebesar quantity
- Reference number auto-generate: `SI-YYYY-NNN`
- Hanya Admin yang bisa menambah

### 5. Barang Keluar (`/stock-out`)
- Tabel: No. Referensi, Barang, Jumlah, Tujuan, Penerima, Tanggal Keluar, Dicatat Oleh
- Form tambah: pilih item, jumlah (validasi tidak boleh melebihi stok), tujuan, penerima, tanggal, catatan
- Saat submit: stok item otomatis **berkurang** sebesar quantity
- Reference number auto-generate: `SO-YYYY-NNN`
- Hanya Admin yang bisa menambah

### 6. Peminjaman (`/borrowings`)
- Tabel: No. Referensi, Barang, Peminjam, Jumlah, Tgl Pinjam, Tgl Kembali, Status
- Status badge berwarna: Pending (kuning), Disetujui (hijau), Ditolak (merah), Dikembalikan (biru), Terlambat (oranye)
- Karyawan hanya melihat peminjaman milik sendiri
- Admin melihat semua peminjaman
- Badge notifikasi di sidebar untuk jumlah `pending` (hanya admin)

**Form Buat Peminjaman (semua user):**
- Pilih item, jumlah, tanggal pinjam, estimasi tanggal kembali, tujuan
- Status awal: `pending`
- Reference number auto-generate: `BRW-YYYY-NNN`

**Aksi Admin:**
- **Approve** → status jadi `approved`, stok berkurang sebesar quantity
- **Reject** → status jadi `rejected`, isi alasan penolakan (modal SweetAlert dengan input)
- **Kembalikan** → status jadi `returned`, isi tanggal pengembalian aktual, stok bertambah kembali

**Halaman Detail Peminjaman:**
- Info lengkap + tombol aksi sesuai status saat ini

### 7. Laporan (`/reports`) — Admin Only
- Filter: tanggal mulai, tanggal akhir, jenis laporan (Barang Masuk / Barang Keluar / Peminjaman)
- Tampilkan tabel hasil filter
- Tombol **Export PDF** → generate PDF via DomPDF
- Tombol **Export Excel** → generate file .xlsx via Maatwebsite Excel

### 8. Manajemen User (`/users`) — Admin Only
- Tabel: Nama, Email, Role, Divisi/Departemen, No. HP, Status Aktif
- Form tambah/edit user: nama, email, password, role, departemen, telepon, status aktif
- Hapus user dengan konfirmasi SweetAlert
- Admin tidak bisa menghapus diri sendiri

---

## 🎨 Desain UI/UX

### Layout
- **Sidebar kiri** fixed — lebar ≈ 240px, warna putih/slate
- **Konten kanan** scrollable, background `slate-50`
- **Topbar** di setiap halaman: judul halaman + nama user + tombol logout

### Warna Utama (Tailwind)
- Primary: `indigo-600` / `#4f46e5`
- Sidebar active: gradient `from-indigo-600 to-indigo-500`
- Danger: `red-500`
- Success: `green-500`
- Warning: `amber-500`

### Sidebar
Logo + nama "MyInventory" di atas, navigasi dengan icon Font Awesome, user info + tombol logout di bawah. Tombol logout menggunakan SweetAlert konfirmasi sebelum logout.

### Komponen Umum
- **Card widget dashboard**: shadow, rounded-xl, icon berwarna
- **Tabel**: striped ringan, hover row, rounded, shadow-sm
- **Badge status**: `inline-flex`, `rounded-full`, warna sesuai status
- **Tombol aksi**: icon + teks, ukuran sm, rounded-lg
- **Flash message**: SweetAlert2 auto-close 2.5 detik untuk success, manual close untuk error
- **Konfirmasi hapus**: SweetAlert2 `icon: 'warning'` dengan confirm merah

### Form
- Label di atas input, `rounded-xl`, `border-slate-200`, `focus:ring-indigo-500`
- Error validation merah di bawah field
- Tombol submit: `bg-indigo-600 hover:bg-indigo-700`

---

## 🌱 Data Seeder (DatabaseSeeder)

Seed data berikut saat `php artisan migrate:fresh --seed`:

**Users (5):**
- admin@mail.com / password → role: admin, dept: IT
- budi@mail.com / password → role: karyawan, dept: Finance
- siti@mail.com / password → role: karyawan, dept: HR
- andi@mail.com / password → role: karyawan, dept: Operations
- dewi@mail.com / password → role: karyawan, dept: Marketing

**Kategori (5):** ATK, Elektronik, Furnitur, Kebersihan, Komputer

**Items (15):** Berbagai item dari tiap kategori, beberapa dengan stok menipis dan kondisi rusak ringan

**Barang Masuk (12):** Data 3 bulan terakhir dengan supplier dan harga berbeda

**Barang Keluar (12):** Distribusi ke berbagai divisi

**Peminjaman (9):** Mencakup semua status — returned, approved, pending (2), rejected, overdue

**Activity Log (12):** Login dan aksi CRUD

---

## ⚙️ Model & Relasi

```php
User       → hasMany StockIn, StockOut, Borrowing (as borrower & approver), ActivityLog
Category   → hasMany Item
Item       → belongsTo Category; hasMany StockIn, StockOut, Borrowing
StockIn    → belongsTo Item, User
StockOut   → belongsTo Item, User
Borrowing  → belongsTo Item; belongsTo User (borrower_id); belongsTo User (approved_by)
ActivityLog→ belongsTo User
```

**ActivityLog helper:** `ActivityLog::record($action, $description)` — static method yang otomatis ambil `auth()->id()` dan `request()->ip()`.

---

## 🔒 Middleware

- `auth` — semua route kecuali login
- `AdminMiddleware` — untuk route yang hanya boleh admin: tambah stock in/out, laporan, manajemen user, approve/reject peminjaman

---

## 📦 Struktur File Penting

```
app/
  Http/
    Controllers/
      AuthController.php
      DashboardController.php
      ItemController.php
      StockInController.php
      StockOutController.php
      BorrowingController.php
      ReportController.php
      UserController.php
    Middleware/
      AdminMiddleware.php
  Models/
    User.php, Category.php, Item.php
    StockIn.php, StockOut.php
    Borrowing.php, ActivityLog.php
  Exports/
    ReportExport.php  (Maatwebsite Excel)

resources/views/
  layouts/
    app.blade.php    (master layout + SweetAlert + scripts)
    sidebar.blade.php
  auth/login.blade.php
  dashboard/index.blade.php
  items/ (index, create, edit, show, _form)
  stock-in/ (index, create)
  stock-out/ (index, create)
  borrowings/ (index, create, show)
  reports/ (index, pdf)
  users/ (index, create, edit, _form)

database/
  migrations/
    create_users_table.php
    create_sessions_table.php
    create_items_table.php      (+ categories)
    create_transactions_table.php (+ stock_ins, stock_outs, borrowings, activity_logs)
  seeders/DatabaseSeeder.php
```

---

## 📝 Catatan Implementasi

1. **Auto-generate reference number** — format `SI-YYYY-NNN`, `SO-YYYY-NNN`, `BRW-YYYY-NNN` dengan padding 3 digit, increment dari record terakhir bulan berjalan
2. **Stok update otomatis** — setiap stock in/out/borrowing yang approved/returned harus update kolom `stock` di tabel `items`
3. **Validasi stok** — stock out & borrowing tidak boleh melebihi stok tersedia
4. **Export PDF** — gunakan blade view terpisah `reports/pdf.blade.php` tanpa layout utama
5. **Upload gambar** — simpan ke `storage/app/public/items/`, tampilkan via `asset('storage/items/...')`, pastikan `php artisan storage:link` dijalankan
6. **SweetAlert2** — load via CDN di `app.blade.php`, semua flash `success` / `error` dari session otomatis ditampilkan sebagai toast/popup
7. **Konfirmasi hapus** — gunakan `data-confirm-delete` attribute pada tombol hapus, tangkap dengan JS global di `app.blade.php`
