<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        private \Illuminate\Support\Collection $data,
        private string $type
    ) {}

    public function collection()
    {
        return match($this->type) {
            'stock_in' => $this->data->map(fn($r) => [
                $r->reference_no,
                $r->item->code ?? '-',
                $r->item->name ?? '-',
                $r->item->category->name ?? '-',
                $r->quantity . ' ' . ($r->item->unit ?? ''),
                $r->supplier ?? '-',
                $r->received_date->format('d/m/Y'),
                $r->user->name ?? '-',
                $r->notes ?? '-',
            ]),
            'stock_out' => $this->data->map(fn($r) => [
                $r->reference_no,
                $r->item->code ?? '-',
                $r->item->name ?? '-',
                $r->item->category->name ?? '-',
                $r->quantity . ' ' . ($r->item->unit ?? ''),
                $r->purpose ?? '-',
                $r->recipient ?? '-',
                $r->issued_date->format('d/m/Y'),
                $r->user->name ?? '-',
            ]),
            'borrowing' => $this->data->map(fn($r) => [
                $r->reference_no,
                $r->item->name ?? '-',
                $r->borrower->name ?? '-',
                $r->quantity,
                $r->borrow_date->format('d/m/Y'),
                $r->expected_return_date->format('d/m/Y'),
                $r->actual_return_date?->format('d/m/Y') ?? '-',
                ucfirst($r->status),
                $r->purpose ?? '-',
            ]),
            'inventory' => $this->data->map(fn($r) => [
                $r->code,
                $r->name,
                $r->category->name ?? '-',
                $r->stock,
                $r->unit,
                $r->location ?? '-',
                ucfirst(str_replace('_', ' ', $r->condition)),
                $r->is_active ? 'Aktif' : 'Nonaktif',
            ]),
            default => collect(),
        };
    }

    public function headings(): array
    {
        return match($this->type) {
            'stock_in'  => ['No. Referensi', 'Kode Barang', 'Nama Barang', 'Kategori', 'Jumlah', 'Supplier', 'Tanggal Terima', 'Dicatat Oleh', 'Catatan'],
            'stock_out' => ['No. Referensi', 'Kode Barang', 'Nama Barang', 'Kategori', 'Jumlah', 'Tujuan', 'Penerima', 'Tanggal Keluar', 'Dicatat Oleh'],
            'borrowing' => ['No. Referensi', 'Nama Barang', 'Peminjam', 'Jumlah', 'Tgl Pinjam', 'Tgl Kembali', 'Tgl Dikembalikan', 'Status', 'Tujuan'],
            'inventory' => ['Kode', 'Nama Barang', 'Kategori', 'Stok', 'Satuan', 'Lokasi', 'Kondisi', 'Status'],
            default     => [],
        };
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
        ];
    }

    public function title(): string
    {
        return match($this->type) {
            'stock_in'  => 'Barang Masuk',
            'stock_out' => 'Barang Keluar',
            'borrowing' => 'Peminjaman',
            'inventory' => 'Inventaris',
            default     => 'Laporan',
        };
    }
}
