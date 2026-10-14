<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users & UserProfiles (Slide 5 & 6)
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Wenny Fadhilah',
            'email' => 'admin@vogueora.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'full_name' => 'Wenny Fadhilah (Owner & Inventory Director)',
            'phone' => '081234567890',
            'address' => 'Vogueora Headquarter, Jl. Senopati No. 88, Jakarta Selatan',
        ]);

        $staff = User::create([
            'username' => 'staff',
            'name' => 'Aditya Pratama',
            'email' => 'staff@vogueora.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        UserProfile::create([
            'user_id' => $staff->id,
            'full_name' => 'Aditya Pratama (Warehouse & Logistic Supervisor)',
            'phone' => '081987654321',
            'address' => 'Warehouse Vogueora, Kawasan Pergudangan Sentul Blok C2, Bogor',
        ]);

        // 2. Seed Categories (Slide 5 & 7)
        $categoriesData = [
            1 => ['name' => 'Kaos & T-Shirt', 'description' => 'Koleksi kaos santai potongan oversized dan regular dari katun combed gramasi premium.'],
            2 => ['name' => 'Kemeja & Flannel', 'description' => 'Kemeja formal, casual smart, serta kemeja hangat berbahan oxford dan corduroy ekspor.'],
            3 => ['name' => 'Jaket & Outerwear', 'description' => 'Jaket denim vintage washed, windbreaker tahan angin, serta outerwear fungsional modern.'],
            4 => ['name' => 'Hoodie & Sweatshirt', 'description' => 'Hoodie boxy fleece berat dan sweater rajut tebal berkarakter streetwear elegan.'],
            5 => ['name' => 'Celana & Denim', 'description' => 'Celana jeans raw selvedge klasik, tapered chino rapi, dan celana santai harian.'],
            6 => ['name' => 'Aksesoris Fashion', 'description' => 'Topi rajut beanie, tote bag kanvas tebal, dan perlengkapan penunjang gaya pakaian.'],
        ];

        $categories = [];
        foreach ($categoriesData as $id => $data) {
            $categories[$id] = Category::create(array_merge(['id' => $id], $data));
        }

        // 3. Seed Suppliers (Slide 5 & 8)
        $suppliersData = [
            1 => [
                'name' => 'PT Bandung Textile & Garment Mills',
                'contact_person' => 'Hendra Wijaya',
                'phone' => '022-7788990',
                'email' => 'sales@bdgtextile.co.id',
                'address' => 'Kawasan Industri Majalaya Kav. 14, Bandung',
            ],
            2 => [
                'name' => 'CV Solo Busana Nusantara',
                'contact_person' => 'Dewi Lestari',
                'phone' => '0271-654321',
                'email' => 'info@solobusana.com',
                'address' => 'Jl. Slamet Riyadi No. 120, Surakarta',
            ],
            3 => [
                'name' => 'PT Indo Denim & Fabric Mills',
                'contact_person' => 'Bambang Irawan',
                'phone' => '021-8989123',
                'email' => 'bambang@indodenim.id',
                'address' => 'Kawasan Industri Cikarang Blok F7, Bekasi',
            ],
            4 => [
                'name' => 'CV Aksesoris Mode Kreatif',
                'contact_person' => 'Kevin Pratama',
                'phone' => '031-7654321',
                'email' => 'contact@modekreatif.com',
                'address' => 'Jl. Rungkut Industri No. 45, Surabaya',
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $id => $data) {
            $suppliers[$id] = Supplier::create(array_merge(['id' => $id], $data));
        }

        // 4. Seed Products (Slide 11 & 37)
        $productsData = [
            1 => [
                'category_id' => 1,
                'sku' => 'VOG-TSH-001',
                'name' => 'Oversized Heavyweight Cotton T-Shirt',
                'price' => 149000.00,
                'stock' => 24,
                'minimum_stock' => 6,
                'description' => 'Kaos oversized bahan 100% cotton combed 20s premium 220 GSM. Potongan boxy modern, kerah rib tebal anti melar.',
                'image' => 'tshirt_oversize.jpg',
            ],
            2 => [
                'category_id' => 1,
                'sku' => 'VOG-TSH-002',
                'name' => 'Acid Wash Vintage Graphic Tee',
                'price' => 169000.00,
                'stock' => 12,
                'minimum_stock' => 5,
                'description' => 'Kaos bergaya washed vintage dengan teknik acid wash manual. Tekstur lembut dan corak estetik retro 90-an.',
                'image' => 'tshirt_acid.jpg',
            ],
            3 => [
                'category_id' => 2,
                'sku' => 'VOG-SHT-001',
                'name' => 'Oxford Slim-Fit Long Sleeve Shirt',
                'price' => 229000.00,
                'stock' => 15,
                'minimum_stock' => 5,
                'description' => 'Kemeja lengan panjang bahan Oxford cotton woven kualitas ekspor. Desain button-down collar rapi dan elegan.',
                'image' => 'shirt_oxford.jpg',
            ],
            4 => [
                'category_id' => 2,
                'sku' => 'VOG-SHT-002',
                'name' => 'Corduroy Overshirt Autumn Brown',
                'price' => 259000.00,
                'stock' => 8,
                'minimum_stock' => 4,
                'description' => 'Overshirt bahan baby corduroy 14-wale halus dan tebal. Cocok sebagai luaran hangat dengan nuansa warna earth-tone.',
                'image' => 'shirt_corduroy.jpg',
            ],
            5 => [
                'category_id' => 3,
                'sku' => 'VOG-JKT-001',
                'name' => 'Vintage Washed Denim Trucker Jacket',
                'price' => 389000.00,
                'stock' => 4,
                'minimum_stock' => 5,
                'description' => 'Jaket denim klasik 14.5 oz non-stretch dengan efek cucian vintage stone wash dan kancing logam berukir logo Vogueora.',
                'image' => 'jacket_denim.jpg',
            ],
            6 => [
                'category_id' => 3,
                'sku' => 'VOG-JKT-002',
                'name' => 'Technical Windbreaker Waterproof Jacket',
                'price' => 319000.00,
                'stock' => 10,
                'minimum_stock' => 4,
                'description' => 'Jaket tahan angin dan air berbahan ripstop nylon parasut dengan lapisan dalam breathable mesh dan zipper kedap air.',
                'image' => 'jacket_windbreaker.jpg',
            ],
            7 => [
                'category_id' => 4,
                'sku' => 'VOG-HOD-001',
                'name' => 'Heavyweight Boxy Fleece Hoodie',
                'price' => 289000.00,
                'stock' => 18,
                'minimum_stock' => 6,
                'description' => 'Hoodie potongan boxy santai berbahan cotton fleece tebal 330 GSM. Bagian tudung dua lapis kokoh dan kantong kanguru lapang.',
                'image' => 'hoodie_fleece.jpg',
            ],
            8 => [
                'category_id' => 4,
                'sku' => 'VOG-HOD-002',
                'name' => 'Chunky Knit Oversized Sweater',
                'price' => 269000.00,
                'stock' => 2,
                'minimum_stock' => 4,
                'description' => 'Sweater rajut motif tenun kabel tebal bertekstur empuk. Memberikan kehangatan maksimal dengan siluet kasual elegan.',
                'image' => 'sweater_knit.jpg',
            ],
            9 => [
                'category_id' => 5,
                'sku' => 'VOG-JNS-001',
                'name' => 'Raw Selvedge Denim Straight Pants',
                'price' => 349000.00,
                'stock' => 7,
                'minimum_stock' => 5,
                'description' => 'Celana denim kaku (raw) selvedge line 13.5 oz potongan straight leg. Menghasilkan kontur fading alami seiring pemakaian.',
                'image' => 'jeans_selvedge.jpg',
            ],
            10 => [
                'category_id' => 5,
                'sku' => 'VOG-PNT-001',
                'name' => 'Pleated Tapered Chino Trousers',
                'price' => 219000.00,
                'stock' => 14,
                'minimum_stock' => 5,
                'description' => 'Celana chino katun twill stretch dengan lipatan depan (pleat) berpotongan meruncing dari lutut hingga pergelangan kaki.',
                'image' => 'pants_chino.jpg',
            ],
            11 => [
                'category_id' => 6,
                'sku' => 'VOG-ACC-001',
                'name' => 'Ribbed Knit Fisherman Beanie Hat',
                'price' => 89000.00,
                'stock' => 30,
                'minimum_stock' => 8,
                'description' => 'Topi rajut model fisherman beanie berbahan benang akrilik elastis tebal. Nyaman melindungi kepala dari udara dingin.',
                'image' => 'beanie_hat.jpg',
            ],
            12 => [
                'category_id' => 6,
                'sku' => 'VOG-ACC-002',
                'name' => 'Heavy Canvas Daily Tote Bag',
                'price' => 119000.00,
                'stock' => 0,
                'minimum_stock' => 5,
                'description' => 'Tas jinjing kanvas katun tebal 16 oz dengan saku dalam berseleting dan sablon logo minimalis Vogueora Apparel.',
                'image' => 'totebag_canvas.jpg',
            ],
        ];

        foreach ($productsData as $id => $data) {
            Product::create(array_merge(['id' => $id], $data));
        }

        // 5. Seed Product Suppliers (Many-to-Many - Slide 5 & 8)
        $productSuppliers = [
            ['product_id' => 1, 'supplier_id' => 1, 'supplier_price' => 95000.00],
            ['product_id' => 1, 'supplier_id' => 2, 'supplier_price' => 98000.00],
            ['product_id' => 2, 'supplier_id' => 1, 'supplier_price' => 110000.00],
            ['product_id' => 3, 'supplier_id' => 2, 'supplier_price' => 150000.00],
            ['product_id' => 4, 'supplier_id' => 2, 'supplier_price' => 175000.00],
            ['product_id' => 5, 'supplier_id' => 3, 'supplier_price' => 260000.00],
            ['product_id' => 6, 'supplier_id' => 1, 'supplier_price' => 210000.00],
            ['product_id' => 7, 'supplier_id' => 1, 'supplier_price' => 195000.00],
            ['product_id' => 8, 'supplier_id' => 1, 'supplier_price' => 180000.00],
            ['product_id' => 9, 'supplier_id' => 3, 'supplier_price' => 235000.00],
            ['product_id' => 10, 'supplier_id' => 2, 'supplier_price' => 145000.00],
            ['product_id' => 11, 'supplier_id' => 4, 'supplier_price' => 55000.00],
            ['product_id' => 12, 'supplier_id' => 4, 'supplier_price' => 75000.00],
        ];

        DB::table('product_suppliers')->insert(array_map(function ($row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
            return $row;
        }, $productSuppliers));

        // 6. Seed Initial Stock Movements (Audit History - Slide 10, 25-27)
        $movementsData = [
            ['id' => 1, 'product_id' => 1, 'user_id' => 1, 'type' => 'IN', 'quantity' => 30, 'reference_number' => 'PO-VOG-0001', 'notes' => 'Penerimaan batch produksi awal dari PT Bandung Textile', 'days_ago' => 6],
            ['id' => 2, 'product_id' => 1, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 6, 'reference_number' => 'SO-VOG-0101', 'notes' => 'Pesanan online store butik Senopati', 'days_ago' => 4],
            ['id' => 3, 'product_id' => 2, 'user_id' => 1, 'type' => 'IN', 'quantity' => 15, 'reference_number' => 'PO-VOG-0002', 'notes' => 'Restok kaos acid wash vintage', 'days_ago' => 5],
            ['id' => 4, 'product_id' => 2, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 3, 'reference_number' => 'SO-VOG-0102', 'notes' => 'Penjualan offline customer pop-up store', 'days_ago' => 3],
            ['id' => 5, 'product_id' => 3, 'user_id' => 1, 'type' => 'IN', 'quantity' => 20, 'reference_number' => 'PO-VOG-0003', 'notes' => 'Penerimaan kemeja Oxford dari CV Solo Busana', 'days_ago' => 5],
            ['id' => 6, 'product_id' => 3, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 5, 'reference_number' => 'SO-VOG-0105', 'notes' => 'Faktur pesanan butik #INV-VOG-55', 'days_ago' => 2],
            ['id' => 7, 'product_id' => 4, 'user_id' => 1, 'type' => 'IN', 'quantity' => 12, 'reference_number' => 'PO-VOG-0004', 'notes' => 'Restok batch baby corduroy overshirt', 'days_ago' => 4],
            ['id' => 8, 'product_id' => 4, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 4, 'reference_number' => 'SO-VOG-0108', 'notes' => 'Distribusi ke outlet cabang Kemang', 'days_ago' => 1],
            ['id' => 9, 'product_id' => 5, 'user_id' => 1, 'type' => 'IN', 'quantity' => 8, 'reference_number' => 'PO-VOG-0005', 'notes' => 'Penerimaan batch jaket denim PT Indo Denim', 'days_ago' => 4],
            ['id' => 10, 'product_id' => 5, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 4, 'reference_number' => 'SO-VOG-0112', 'notes' => 'Penjualan retail customer butik (Stok Menipis)', 'days_ago' => 1],
            ['id' => 11, 'product_id' => 6, 'user_id' => 1, 'type' => 'IN', 'quantity' => 15, 'reference_number' => 'PO-VOG-0006', 'notes' => 'Penerimaan jaket windbreaker parasut', 'days_ago' => 3],
            ['id' => 12, 'product_id' => 6, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 5, 'reference_number' => 'SO-VOG-0115', 'notes' => 'Faktur pengiriman grosir reseller', 'days_ago' => 2],
            ['id' => 13, 'product_id' => 7, 'user_id' => 1, 'type' => 'IN', 'quantity' => 25, 'reference_number' => 'PO-VOG-0007', 'notes' => 'Penerimaan hoodie boxy fleece Bandung', 'days_ago' => 3],
            ['id' => 14, 'product_id' => 7, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 7, 'reference_number' => 'SO-VOG-0118', 'notes' => 'Penjualan marketplace online', 'days_ago' => 1],
            ['id' => 15, 'product_id' => 8, 'user_id' => 1, 'type' => 'IN', 'quantity' => 5, 'reference_number' => 'PO-VOG-0008', 'notes' => 'Batch rajut knit sweater edisi terbatas', 'days_ago' => 3],
            ['id' => 16, 'product_id' => 8, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 3, 'reference_number' => 'SO-VOG-0120', 'notes' => 'Terjual customer butik (Sisa 2 unit - Low Stock)', 'days_ago' => 0],
            ['id' => 17, 'product_id' => 9, 'user_id' => 1, 'type' => 'IN', 'quantity' => 10, 'reference_number' => 'PO-VOG-0009', 'notes' => 'Penerimaan denim raw selvedge 13.5 oz', 'days_ago' => 2],
            ['id' => 18, 'product_id' => 9, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 3, 'reference_number' => 'SO-VOG-0122', 'notes' => 'Faktur order store Senopati', 'days_ago' => 1],
            ['id' => 19, 'product_id' => 10, 'user_id' => 1, 'type' => 'IN', 'quantity' => 20, 'reference_number' => 'PO-VOG-0010', 'notes' => 'Penerimaan celana tapered chino', 'days_ago' => 2],
            ['id' => 20, 'product_id' => 10, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 6, 'reference_number' => 'SO-VOG-0125', 'notes' => 'Pesanan seragam staf butik partner', 'days_ago' => 0],
            ['id' => 21, 'product_id' => 11, 'user_id' => 1, 'type' => 'IN', 'quantity' => 40, 'reference_number' => 'PO-VOG-0011', 'notes' => 'Penerimaan rajut beanie hat Surabaya', 'days_ago' => 4],
            ['id' => 22, 'product_id' => 11, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 10, 'reference_number' => 'SO-VOG-0128', 'notes' => 'Faktur bundle promosi akhir pekan', 'days_ago' => 1],
            ['id' => 23, 'product_id' => 12, 'user_id' => 1, 'type' => 'IN', 'quantity' => 15, 'reference_number' => 'PO-VOG-0012', 'notes' => 'Penerimaan tote bag kanvas batch 1', 'days_ago' => 4],
            ['id' => 24, 'product_id' => 12, 'user_id' => 2, 'type' => 'OUT', 'quantity' => 15, 'reference_number' => 'SO-VOG-0130', 'notes' => 'Seluruh tote bag habis terjual (Stok Habis / 0)', 'days_ago' => 1],
        ];

        foreach ($movementsData as $m) {
            StockMovement::create([
                'id' => $m['id'],
                'product_id' => $m['product_id'],
                'user_id' => $m['user_id'],
                'type' => $m['type'],
                'quantity' => $m['quantity'],
                'reference_number' => $m['reference_number'],
                'notes' => $m['notes'],
                'created_at' => now()->subDays($m['days_ago']),
                'updated_at' => now()->subDays($m['days_ago']),
            ]);
        }
    }
}
