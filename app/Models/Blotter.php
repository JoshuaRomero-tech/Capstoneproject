<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_no',
        'complainant_id',
        'respondent_id',
        'incident_details',
        'incident_type',
        'incident_date',
        'incident_location',
        'status',
        'action_taken',
        'remarks',
        'recorded_by',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function complainant()
    {
        return $this->belongsTo(Resident::class, 'complainant_id');
    }

    public function respondent()
    {
        return $this->belongsTo(Resident::class, 'respondent_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
