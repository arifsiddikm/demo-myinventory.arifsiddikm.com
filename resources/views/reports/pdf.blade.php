<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan MyInventory</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; background: #fff; }
    .header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; padding: 20px 24px; margin-bottom: 20px; }
    .header h1 { font-size: 20px; font-weight: 700; letter-spacing: 0.5px; }
    .header p { opacity: 0.8; font-size: 11px; margin-top: 4px; }
    .meta { display: flex; gap: 24px; margin: 0 24px 16px; }
    .meta-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 14px; }
    .meta-item .label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .meta-item .value { font-weight: 600; color: #1e293b; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin: 0 0 16px; }
    thead tr { background: #4f46e5; color: white; }
    thead th { padding: 9px 12px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody tr:hover { background: #eff6ff; }
    tbody td { padding: 8px 12px; border-bottom: 1px solid #f1f5f9; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: 600; }
    .badge-pending  { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-returned { background: #dbeafe; color: #1e40af; }
    .footer { text-align: center; color: #94a3b8; font-size: 9px; margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    .page-wrap { padding: 0 24px; }
    .text-green { color: #059669; font-weight: 700; }
    .text-red { color: #dc2626; font-weight: 700; }
    .text-warn { color: #d97706; font-weight: 700; }
</style>
</head>
<body>
<div class="header">
    <h1>MyInventory — {{ ['stock_in'=>'Laporan Barang Masuk','stock_out'=>'Laporan Barang Keluar','borrowing'=>'Laporan Peminjaman','inventory'=>'Laporan Inventaris'][$type] ?? 'Laporan' }}</h1>
    <p>Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
</div>

@if($type !== 'inventory')
<div class="meta">
    <div class="meta-item"><div class="label">Periode Dari</div><div class="value">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</div></div>
    <div class="meta-item"><div class="label">Periode Sampai</div><div class="value">{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div></div>
    <div class="meta-item"><div class="label">Total Record</div><div class="value">{{ $data->count() }}</div></div>
    <div class="meta-item"><div class="label">Total Qty</div><div class="value">{{ $data->sum('quantity') }}</div></div>
</div>
@endif

<div class="page-wrap">
@if($type === 'stock_in')
<table>
    <thead><tr>
        <th>No. Referensi</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th>
        <th>Qty</th><th>Supplier</th><th>Tgl Terima</th><th>Dicatat Oleh</th>
    </tr></thead>
    <tbody>
        @foreach($data as $r)
        <tr>
            <td style="font-family:monospace;font-size:10px;color:#64748b">{{ $r->reference_no }}</td>
            <td style="font-family:monospace;font-size:10px">{{ $r->item->code }}</td>
            <td style="font-weight:600">{{ $r->item->name }}</td>
            <td>{{ $r->item->category->name }}</td>
            <td class="text-green">+{{ $r->quantity }} {{ $r->item->unit }}</td>
            <td>{{ $r->supplier ?? '-' }}</td>
            <td>{{ $r->received_date->format('d/m/Y') }}</td>
            <td>{{ $r->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@elseif($type === 'stock_out')
<table>
    <thead><tr>
        <th>No. Referensi</th><th>Nama Barang</th><th>Kategori</th>
        <th>Qty</th><th>Tujuan</th><th>Penerima</th><th>Tgl Keluar</th>
    </tr></thead>
    <tbody>
        @foreach($data as $r)
        <tr>
            <td style="font-family:monospace;font-size:10px;color:#64748b">{{ $r->reference_no }}</td>
            <td style="font-weight:600">{{ $r->item->name }}</td>
            <td>{{ $r->item->category->name }}</td>
            <td class="text-red">-{{ $r->quantity }} {{ $r->item->unit }}</td>
            <td>{{ $r->purpose ?? '-' }}</td>
            <td>{{ $r->recipient ?? '-' }}</td>
            <td>{{ $r->issued_date->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@elseif($type === 'borrowing')
<table>
    <thead><tr>
        <th>No. Referensi</th><th>Nama Barang</th><th>Peminjam</th>
        <th>Qty</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th>
    </tr></thead>
    <tbody>
        @foreach($data as $r)
        <tr>
            <td style="font-family:monospace;font-size:10px;color:#64748b">{{ $r->reference_no }}</td>
            <td style="font-weight:600">{{ $r->item->name }}</td>
            <td>{{ $r->borrower->name }}</td>
            <td style="font-weight:600;text-align:center">{{ $r->quantity }}</td>
            <td>{{ $r->borrow_date->format('d/m/Y') }}</td>
            <td>{{ $r->expected_return_date->format('d/m/Y') }}</td>
            <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

@elseif($type === 'inventory')
<table>
    <thead><tr>
        <th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Stok</th>
        <th>Satuan</th><th>Lokasi</th><th>Kondisi</th>
    </tr></thead>
    <tbody>
        @foreach($data as $r)
        <tr>
            <td style="font-family:monospace;font-size:10px">{{ $r->code }}</td>
            <td style="font-weight:600">{{ $r->name }}</td>
            <td>{{ $r->category->name }}</td>
            <td class="{{ $r->isLowStock() ? 'text-warn' : '' }}" style="text-align:center;font-weight:600">{{ $r->stock }}</td>
            <td>{{ $r->unit }}</td>
            <td>{{ $r->location ?? '-' }}</td>
            <td>{{ $r->condition_label }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
</div>

<div class="footer">
    MyInventory — Sistem Informasi Manajemen Inventaris · Dicetak {{ now()->format('d M Y H:i') }}
</div>
</body>
</html>
