<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems    = Item::where('is_active', true)->count();
        $totalStockIn  = StockIn::whereMonth('created_at', now()->month)->sum('quantity');
        $totalStockOut = StockOut::whereMonth('created_at', now()->month)->sum('quantity');
        $activeBorrows = Borrowing::whereIn('status', ['approved'])->count();
        $pendingBorrows = Borrowing::where('status', 'pending')->count();
        $lowStockItems = Item::where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count();
        $totalUsers    = User::where('is_active', true)->count();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $recentBorrowings = Borrowing::with(['item', 'borrower'])
            ->latest()
            ->limit(5)
            ->get();

        $lowStockList = Item::with('category')
            ->where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalItems', 'totalStockIn', 'totalStockOut',
            'activeBorrows', 'pendingBorrows', 'lowStockItems',
            'totalUsers', 'recentActivities', 'recentBorrowings', 'lowStockList'
        ));
    }
}
