<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'surname',
        'personal_id',
        'personal_number',
        'user_name',
        'population',
        'prefix',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function details(): HasOne {
        return $this->hasOne(Details::class);
    }
}
