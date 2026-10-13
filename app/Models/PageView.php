<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'is_bot' => 'boolean',
        'viewed_date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Filter only real human visitors (excluding crawlers).
     */
    public function scopeHumans(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    /**
     * Filter by date range.
     */
    public function scopeBetweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereDate('viewed_date', '>=', $startDate)
            ->whereDate('viewed_date', '<=', $endDate);
    }

    /**
     * Active in the last N minutes.
     */
    public function scopeActiveRecent(Builder $query, int $minutes = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }
}
