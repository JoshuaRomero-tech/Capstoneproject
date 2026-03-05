<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'civil_status',
        'nationality',
        'religion',
        'contact_number',
        'email',
        'address',
        'occupation',
        'educational_attainment',
        'voter_status',
        'is_pwd',
        'is_solo_parent',
        'is_senior_citizen',
        'philhealth_no',
        'sss_no',
        'tin_no',
        'photo',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_pwd' => 'boolean',
        'is_solo_parent' => 'boolean',
        'is_senior_citizen' => 'boolean',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function official()
    {
        return $this->hasOne(Official::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function complaintsFiledAgainst()
    {
        return $this->hasMany(Blotter::class, 'respondent_id');
    }

    public function complaintsFiled()
    {
        return $this->hasMany(Blotter::class, 'complainant_id');
    }

    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }
}
