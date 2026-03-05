<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_no',
        'address',
    ];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function head()
    {
        return $this->residents()->oldest('date_of_birth')->first();
    }
}
