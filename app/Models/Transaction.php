<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
        'category',
        'date',
        'payment_method',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /* =========================================================================
       RELATIONSHIPS
       ========================================================================= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* =========================================================================
       QUERY SCOPES (Model encapsulates DB queries)
       ========================================================================= */

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', 'expense');
    }

    public function scopeInMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeRecent(Builder $query, int $limit = 5): Builder
    {
        return $query->orderBy('date', 'desc')->orderBy('id', 'desc')->limit($limit);
    }

    /* =========================================================================
       ACCESSORS & HELPERS
       ========================================================================= */

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date ? Carbon::parse($this->date)->translatedFormat('d M Y') : '-';
    }
}
