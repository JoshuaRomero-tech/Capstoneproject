<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'posted_by',
        'title',
        'content',
        'priority',
        'is_active',
        'publish_date',
        'expiry_date',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Scope: only active, published, and non-expired announcements
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where('publish_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            });
    }
}
