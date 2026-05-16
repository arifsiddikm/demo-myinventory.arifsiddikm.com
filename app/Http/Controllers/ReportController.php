<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $type       = $request->type ?? 'stock_in';
        $dateFrom   = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo     = $request->date_to   ?? now()->toDateString();
        $categoryId = $request->category_id;

        $data = $this->getData($type, $dateFrom, $dateTo, $categoryId);

        return view('reports.index', compact('categories', 'type', 'dateFrom', 'dateTo', 'categoryId', 'data'));
    }

    public function exportPdf(Request $request)
    {
        $type       = $request->type ?? 'stock_in';
        $dateFrom   = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo     = $request->date_to   ?? now()->toDateString();
        $categoryId = $request->category_id;

        $data = $this->getData($type, $dateFrom, $dateTo, $categoryId);

        $pdf = Pdf::loadView('reports.pdf', compact('data', 'type', 'dateFrom', 'dateTo'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("laporan-{$type}-{$dateFrom}-{$dateTo}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $type     = $request->type     ?? 'stock_in';
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();

        $data = $this->getData($type, $dateFrom, $dateTo, $request->category_id);

        return Excel::download(new ReportExport($data, $type), "laporan-{$type}-{$dateFrom}-{$dateTo}.xlsx");
    }

    private function getData(string $type, string $dateFrom, string $dateTo, ?string $categoryId): \Illuminate\Support\Collection
    {
        return match($type) {
            'stock_in'  => StockIn::with(['item.category', 'user'])
                ->whereBetween('received_date', [$dateFrom, $dateTo])
                ->when($categoryId, fn($q) => $q->whereHas('item', fn($i) => $i->where('category_id', $categoryId)))
                ->latest()->get(),

            'stock_out' => StockOut::with(['item.category', 'user'])
                ->whereBetween('issued_date', [$dateFrom, $dateTo])
                ->when($categoryId, fn($q) => $q->whereHas('item', fn($i) => $i->where('category_id', $categoryId)))
                ->latest()->get(),

            'borrowing' => Borrowing::with(['item.category', 'borrower', 'approver'])
                ->whereBetween('borrow_date', [$dateFrom, $dateTo])
                ->when($categoryId, fn($q) => $q->whereHas('item', fn($i) => $i->where('category_id', $categoryId)))
                ->latest()->get(),

            'inventory' => Item::with('category')
                ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
                ->orderBy('name')->get(),

            default => collect(),
        };
    }
}
