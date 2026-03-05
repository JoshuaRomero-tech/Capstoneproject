<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'issued_by',
        'type',
        'purpose',
        'or_number',
        'amount',
        'date_issued',
        'valid_until',
        'remarks',
    ];

    protected $casts = [
        'date_issued' => 'date',
        'valid_until' => 'date',
        'amount' => 'decimal:2',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
