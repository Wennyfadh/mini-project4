Vogueora Apparel — Inventory Management System (Pertemuan 5: Laravel Fundamental)

Selamat datang di repositori implementasi **Vogueora Apparel Inventory Management System** yang telah dimigrasikan dan diintegrasikan secara menyeluruh ke dalam framework **Laravel 12 (PHP 8.2)** sesuai dengan seluruh capaian pembelajaran, acceptance criteria, dan instruksi pada **Modul Web 5 (Pertemuan 5: Laravel Fundamental)**.

---

Ringkasan Integrasi: Dari P4 (PHP Native) ke P5 (Laravel)

Sesuai dengan arahan **Slide 2 & 35 Modul Web 5**:
> *"Pertemuan 5 tidak membuat masalah bisnis baru, melainkan memindahkan dan merapikan project P4 ke Laravel. Konsep database relasional dan aturan bisnis (business logic) tetap dipertahankan secara utuh. Laravel menjadi framework untuk mengorganisasi aplikasi dengan arsitektur MVC yang bersih."*

| Komponen Sistem | Implementasi Pertemuan 4 (PHP Native) | Integrasi Pertemuan 5 (Laravel Framework) |
| :--- | :--- | :--- |
| **Arsitektur Database** | `database/schema.sql` mentah | `database/migrations/` (Version-controlled migrations) |
| **Data Awal & Pengujian** | `database/seed.sql` statis | `database/seeders/DatabaseSeeder.php` & `Factories` |
| **Interaksi Basis Data** | PDO Prepared Statements manual | **Eloquent ORM** (Model, Query Builder, Relationship) |
| **Pengelolaan Alur** | File skrip CRUD prosedural terpisah | **Resource Controller** (`ProductController`, `CategoryController`, `SupplierController`) |
| **Sistem Routing** | URL file langsung (`products/index.php`) | **Named Routes & Resource Routes** (`routes/web.php`) |
| **Antarmuka & Tampilan** | PHP Native include header/footer | **Blade Templating** (`layouts/app.blade.php`, `@extends`, `@yield`, `<x-alert>`) |
| **Keamanan Formulir** | Token CSRF session buatan sendiri | Directive `@csrf`, `@method`, Session Regenerate, Bcrypt Hashing |
| **Validasi Input** | Validasi percabangan manual `if-else` | Request Validation Engine Laravel dengan auto flash error |
| **Otorisasi Pengguna** | Pengecekan session role kondisional | **Admin Middleware** (`bootstrap/app.php`) dengan response **HTTP 403 Forbidden** |

---

Arsitektur MVC & Siklus Alur Data (Request-Response Lifecycle)

Aplikasi dibangun dengan mematuhi alur baku arsitektur Laravel (Slide 10 & 47):

```
       [ HTTP Request dari Browser ]
                     │
                     ▼
       ┌───────────────────────────┐
       │     routes/web.php        │  Mengurai HTTP Method (GET/POST/PUT/DELETE)
       │  (Route & Group Match)    │  & mengarahkan ke Controller yang tepat
       └─────────────┬─────────────┘
                     │
                     ▼
       ┌───────────────────────────┐
       │     Controllers Layer     │  - Validasi request ($request->validate)
       │   (e.g. ProductController)│  - Mengatur alur logika bisnis & transaksi
       └─────────────┬─────────────┘
                     │
          ┌──────────┴──────────┐
          ▼                     ▼
┌──────────────────┐   ┌──────────────────┐
│  Eloquent Models │   │  Database MySQL  │
│ (Product/Category│◄─►│ (InnoDB Tables & │
│  Suppliers/Stock)│   │  Foreign Keys)   │
└──────────────────┘   └──────────────────┘
          │
          ▼
┌──────────────────────────┐
│   Blade Template Views   │  Menyajikan HTML, format data Rupiah,
│  (resources/views/...)   │  badge status stok, escaping {{ $val }}
└─────────┬────────────────┘
          │
          ▼
[ HTTP Response dikirim ke Browser Pengguna ]
```

Demonstrasi Alur Nyata (Slide 48):
1. **Membuka Form:** Pengguna mengakses `GET /products/create` &rarr; Route memanggil `ProductController@create` &rarr; Model `Category` dan `Supplier` diambil &rarr; Blade `products/create.blade.php` dirender.
2. **Validasi Input:** Form dikirim melalui `POST /products` &rarr; `ProductController@store` menjalankan `$request->validate()`:
   - Jika ada kolom kosong/tidak valid (misal: SKU sudah ada atau stok bernilai negatif) &rarr; Sistem otomatis mengembalikan ke halaman sebelumnya (`redirect back`) disertai pesan error spesifik via directive `@error` dan mempertahankan isian via `old()`.
3. **Penyimpanan Atomik:** Jika valid &rarr; Data diinsert melalui `Product::create()` dan relasi Many-to-Many dipetakan via `$product->suppliers()->sync()`.
4. **Redirect & Feedback:** Controller merespons dengan `redirect()->route('products.index')->with('success', '...')`.
5. **Menampilkan Data:** Blade layout menangkap flash message dan menampilkan alert sukses via `<x-alert type="success">` serta menampilkan data baru di katalog.

---

Skema Tabel & Relasi Eloquent

Struktur database dibangun di atas **7 Tabel Relasional InnoDB** dengan integritas data ketat:

```
┌──────────────────┐       1 : 1        ┌──────────────────┐
│      users       │───────────────────▶│  user_profiles   │
└────────┬─────────┘                    └──────────────────┘
         │
         │ 1 : N (Pencatat Mutasi)
         ▼
┌──────────────────┐       N : 1        ┌──────────────────┐
│ stock_movements  │◀───────────────────│     products     │
└──────────────────┘                    └────────┬─────────┘
                                                 │
                   ┌─────────────────────────────┤
                   │ N : 1                       │ M : N (Pemasok)
                   ▼                             ▼
         ┌──────────────────┐          ┌───────────────────┐
         │    categories    │          │ product_suppliers │ (Pivot)
         └──────────────────┘          └─────────┬─────────┘
                                                 │
                                                 │ N : 1
                                                 ▼
                                       ┌───────────────────┐
                                       │     suppliers     │
                                       └───────────────────┘
```

Pemetaan Model Eloquent:
1. **`User` <-> `UserProfile` (One-to-One — Slide 5 & 6):**
   - `$user->profile` (`hasOne`)
   - `$profile->user` (`belongsTo`)
2. **`Category` <-> `Product` (One-to-Many — Slide 33):**
   - `$category->products` (`hasMany`)
   - `$product->category` (`belongsTo`)
3. **`Product` <-> `Supplier` (Many-to-Many — Slide 5 & 8):**
   - `$product->suppliers()->withPivot('supplier_price')` (`belongsToMany`)
   - `$supplier->products()->withPivot('supplier_price')` (`belongsToMany`)
4. **`Product` & `User` <-> `StockMovement` (One-to-Many — Slide 10):**
   - `$product->stockMovements` (`hasMany`)
   - `$movement->product` (`belongsTo`)
   - `$movement->user` (`belongsTo`)

---

Aturan Bisnis & Integritas Relasional (Business Rules)

Aplikasi mempertahankan 100% aturan bisnis P4 tanpa ada yang tereduksi:
1. **Transaksi Barang Keluar (Atomic Validation & Rollback):**
   - Ketika mencatat barang keluar di `StockMovementController@storeOut`, sistem memeriksa apakah unit yang diminta melampaui sisa unit stok di gudang (`$product->stock < $quantity`).
   - Jika melampaui stok &rarr; Transaksi database langsung dibatalkan (**Rollback**) dan mengembalikan pesan error tegas:
     > *"Stok tidak mencukupi! Sisa stok saat ini hanya 4 unit, sedangkan jumlah yang diminta adalah 100 unit."*
2. **Transaksi Barang Masuk:**
   - Menambahkan unit stok produk secara atomik (`$product->increment('stock', $qty)`) dan mencatat histori audit dengan identitas user yang sedang login (`Auth::id()`).
3. **Proteksi Integritas Hapus Produk (`ON DELETE RESTRICT`):**
   - Produk pakaian yang telah memiliki histori transaksi mutasi stok **dilarang keras untuk dihapus**. Sistem menangkal operasi penghapusan ini untuk mencegah terjadinya *orphan history audit records*.
4. **Laporan Agregat Inventaris (Slide 28):**
   - Mengimplementasikan query kalkulasi mutasi agregat:
     ```sql
     SELECT p.sku, p.name, c.name, p.price, p.stock,
       COALESCE(SUM(CASE WHEN sm.type = 'IN' THEN sm.quantity ELSE 0 END), 0) AS total_in,
       COALESCE(SUM(CASE WHEN sm.type = 'OUT' THEN sm.quantity ELSE 0 END), 0) AS total_out,
       (p.stock * p.price) AS asset_value
     FROM products p ... GROUP BY p.id
     ```
5. **Otorisasi Berbasis Peran (HTTP 403 Forbidden):**
   - Akun dengan role `staff` secara otomatis ditolak ketika mencoba mengakses menu administrasi pengguna (`/users`), dialihkan dengan kode respon **HTTP 403 Forbidden** dan tampilan ramah pengguna pada `resources/views/errors/403.blade.php`.

---

Kredensial Masuk Sistem & Fitur Quick-Fill Demo

Pada halaman masuk (`/login`), telah disematkan fitur **Tombol Isi Cepat Demo (Quick Fill)**:

| Peran (Role) | Username | Password | Nama Pengguna | Hak Akses |
| :--- | :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | **Wenny Fadhilah** *(Owner & Director)* | Akses Penuh: Kelola Pengguna, Kategori, Pakaian, Supplier, Mutasi Stok, dan Laporan. |
| **Staff Gudang** | `staff` | `staff123` | **Aditya Pratama** *(Warehouse Supervisor)* | Akses Operasional: Melihat katalog pakaian, mencatat Barang Masuk/Keluar, histori mutasi, laporan. *(Dilarang mengakses menu pengguna).* |

---

Panduan Instalasi & Menjalankan di XAMPP

### Langkah 1: Pastikan Apache & MySQL Berjalan di XAMPP
Buka **XAMPP Control Panel**, lalu klik tombol **Start** pada modul:
- **Apache** (Port 80 / 443)
- **MySQL** (Port 3306)

### Langkah 2: Buat Database & Jalankan Migrasi
Buka Terminal / PowerShell di folder proyek (`C:\xampp\htdocs\inventory_laravel`):

```bash
# 1. Buat database di MySQL (jika belum ada)
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Jalankan migration dan seeder data awal lengkap (Slide 27 & 41)
C:\xampp\php\php.exe artisan migrate:fresh --seed
```

Langkah 3: Buka Aplikasi di Browser
Aplikasi mendukung 3 cara akses fleksibel:
1. **Via XAMPP Apache Langsung:**
   - Akses: **`http://localhost/inventory_laravel/`** atau **`http://localhost/inventory_laravel/public/`**
2. **Via Artisan Development Server:**
   ```bash
   C:\xampp\php\php.exe artisan serve
   ```
   - Akses: **`http://localhost:8000`**

---

Pengujian Otomatis (Automated Acceptance Suite)

Proyek ini telah dilengkapi dengan script pengujian otomatis mandiri `verify_p5.php` yang memverifikasi seluruh acceptance criteria secara komprehensif.

Jalankan perintah berikut pada terminal:
```bash
C:\xampp\php\php.exe verify_p5.php
```

Bukti Eksekusi Pengujian Otomatis:
```text
====================================================================
 VOGUEORA APPAREL • INVENTORY MANAGEMENT SYSTEM (PERTEMUAN 5)
 LARAVEL ARCHITECTURE & BUSINESS RULES VERIFICATION SUITE
====================================================================

[TEST 1] Verifikasi Koneksi Database & 7 Tabel Inti Laravel...
  [PASS] Semua 7 tabel utama berhasil dimigrasikan dan terdeteksi di database MySQL.

[TEST 2] Verifikasi Relasi One-to-One: User <-> UserProfile (Slide 5 & 6)...
  [PASS] Relasi One-to-One terverifikasi: User 'admin' memiliki profil 'Wenny Fadhilah (Owner & Inventory Director)'.

[TEST 3] Verifikasi Relasi One-to-Many: Category -> Products (Slide 5 & 7, Slide 33)...
  [PASS] Relasi One-to-Many terverifikasi: Kategori 'Kaos & T-Shirt' menaungi 2 produk busana.

[TEST 4] Verifikasi Relasi Many-to-Many: Products <-> Suppliers (Slide 5 & 8)...
  [PASS] Relasi Many-to-Many terverifikasi: Produk 'Oversized Heavyweight Cotton T-Shirt' memiliki 2 pemasok dengan harga beli pivot Rp 95.000.

[TEST 5] Verifikasi Autentikasi Kredensial & Bcrypt Hashing (Slide 22 & 23)...
  [PASS] Autentikasi kredensial berhasil: Login admin valid, login password salah berhasil ditolak.

[TEST 6] Validasi Integritas Kode SKU Unik (Mencegah Duplikasi)...
  [PASS] Validasi SKU unik terbukti aktif: Percobaan insert duplikasi SKU 'VOG-TSH-001' langsung digagalkan oleh constraint.

[TEST 7] Validasi Harga & Stok Non-Negatif (Slide 33)...
  [PASS] Validasi nilai numerik valid: Harga, stok, dan minimum stok bernilai positif.

[TEST 8] Simulasi Transaksi Barang Keluar Melebihi Stok (Harus Ditolak & Rollback)...
  [PASS] Aturan Bisnis P4/P5 Terpenuhi: Transaksi barang keluar melebihi stok berhasil di-rollback tanpa mengubah unit stok (4 unit).

[TEST 9] Simulasi Transaksi Barang Masuk (Atomic Commit)...
  [PASS] Transaksi Barang Masuk berhasil: Stok bertambah secara atomik dari 4 menjadi 9 unit.

[TEST 10] Proteksi Penghapusan Produk Berhistori (ON DELETE RESTRICT)...
  [PASS] Proteksi integritas relasional aktif: Produk dengan riwayat mutasi stok diproteksi dari penghapusan.

[TEST 11] Verifikasi Query Agregat Laporan Bisnis (Slide 28)...
  [PASS] Query agregat Slide 28 berhasil menghitung total barang masuk (215 unit) dan keluar (71 unit).

[TEST 12] Verifikasi Otorisasi Peran (Admin vs Staff)...
  [PASS] Otorisasi peran terverifikasi: Admin memiliki hak akses penuh, Staff dibatasi pada modul operasional.

====================================================================
 HASIL VERIFIKASI: 12 / 12 PENGUJIAN LULUS (100%)
 SEMUA ATURAN BISNIS, RELASI ELOQUENT, DAN FITUR TERVERIFIKASI SEMPURNA!
====================================================================
```

---

Checklist Kesiapan Modul Pertemuan 5 (Slide 46)

- [x] Project Laravel terkonfigurasi dan dapat dijalankan.
- [x] `.env` terhubung ke database MySQL `inventory_db` dengan benar.
- [x] `php artisan route:list` menampilkan 42 routes lengkap (named routes & resource controller).
- [x] Migration dapat dijalankan dari database kosong (`migrate:fresh`).
- [x] Seeder menghasilkan data uji lengkap (`--seed`).
- [x] Model memiliki relasi Eloquent yang benar (One-to-One, One-to-Many, Many-to-Many).
- [x] Resource controller menangani CRUD secara terorganisir.
- [x] Blade memakai layout terstruktur (`layouts/app.blade.php`), komponen (`<x-alert>`), dan output escaping otomatis.
- [x] Formulir dilindungi `@csrf` dan validasi error message terperinci.
- [x] Listing produk memiliki search, filter, sorting, dan pagination yang mempertahankan query string.
- [x] Seluruh aturan bisnis dari Pertemuan 4 (Vogueora Apparel) berhasil dipindahkan dan terverifikasi 100%.

---

<div align="center">
  <sub>Dikerjakan dengan dedikasi penuh untuk <strong>Pertemuan 5: Laravel Fundamental & System Integration</strong>.</sub><br>
  <sub>&copy; 2026 <strong>Vogueora Apparel</strong>. Seluruh Hak Cipta Dilindungi.</sub>
</div>
