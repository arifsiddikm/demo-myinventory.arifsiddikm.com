<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'reference_no', 'item_id', 'borrower_id', 'approved_by',
        'borrower_name', 'quantity', 'borrow_date', 'expected_return_date',
        'actual_return_date', 'status', 'purpose', 'notes', 'reject_reason',
    ];

    protected $casts = [
        'borrow_date'          => 'date',
        'expected_return_date' => 'date',
        'actual_return_date'   => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => '<span class="badge-status badge-pending">Pending</span>',
            'approved' => '<span class="badge-status badge-approved">Disetujui</span>',
            'rejected' => '<span class="badge-status badge-rejected">Ditolak</span>',
            'returned' => '<span class="badge-status badge-returned">Dikembalikan</span>',
            'overdue'  => '<span class="badge-status badge-overdue">Terlambat</span>',
            default    => '<span class="badge-status badge-pending">Pending</span>',
        };
    }
}
