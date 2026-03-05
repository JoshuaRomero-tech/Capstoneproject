<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'position',
        'committee',
        'term_start',
        'term_end',
        'status',
    ];

    protected $casts = [
        'term_start' => 'date',
        'term_end' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
