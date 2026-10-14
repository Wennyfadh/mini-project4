<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Inventaris | Vogueora Apparel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 { margin: 0; font-size: 18pt; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px 10px;
            font-size: 9.5pt;
        }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; background: #f0fdf4; border: 1px solid #86efac; padding: 12px; border-radius: 6px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #059669; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Mulai Mencetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer; margin-left: 8px;">
            Tutup Halaman
        </button>
    </div>

    <div class="header">
        <h1>VOGUEORA APPAREL</h1>
        <p>Laporan Resmi Valuasi Persediaan & Perputaran Stok Busana</p>
        <p style="font-size: 9pt;">Jl. Senopati No. 88, Jakarta Selatan &bull; Telepon: (021) 555-8899 &bull; info@vogueora.com</p>
    </div>

    <div class="meta">
        <div>
            <strong>Tanggal Cetak:</strong> {{ date('d F Y H:i:s') }}<br>
            <strong>Dicetak Oleh:</strong> {{ Auth::user()->displayName }} ({{ Auth::user()->role }})
        </div>
        <div class="text-right">
            <strong>Total Model:</strong> {{ $summary['total_models'] }} item<br>
            <strong>Total Stok Fisik:</strong> {{ number_format($summary['total_stock_units']) }} unit
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Kode SKU</th>
                <th>Nama Produk Busana</th>
                <th>Kategori</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-center">Total In</th>
                <th class="text-center">Total Out</th>
                <th class="text-center">Sisa Stok</th>
                <th class="text-right">Valuasi Aset</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $idx => $r)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $r->sku }}</td>
                <td>{{ $r->product_name }}</td>
                <td>{{ $r->category_name }}</td>
                <td class="text-right">Rp {{ number_format($r->price, 0, ',', '.') }}</td>
                <td class="text-center">+{{ $r->total_in }}</td>
                <td class="text-center">-{{ $r->total_out }}</td>
                <td class="text-center bold">{{ $r->stock }}</td>
                <td class="text-right">Rp {{ number_format($r->asset_value, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f5f5f5; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-right">-</td>
                <td class="text-center">+{{ number_format($summary['total_in_units']) }}</td>
                <td class="text-center">-{{ number_format($summary['total_out_units']) }}</td>
                <td class="text-center">{{ number_format($summary['total_stock_units']) }}</td>
                <td class="text-right">Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 50px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 220px;">
            <p>Disiapkan Oleh,</p>
            <br><br><br>
            <p><strong>( {{ Auth::user()->displayName }} )</strong><br><span style="text-transform: capitalize;">{{ Auth::user()->role }}</span></p>
        </div>
        <div style="text-align: center; width: 220px;">
            <p>Mengetahui & Menyetujui,</p>
            <br><br><br>
            <p><strong>( Wenny Fadhilah )</strong><br>Owner & Managing Director</p>
        </div>
    </div>
</body>
</html>
