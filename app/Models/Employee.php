<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'personal_number',
        'user_name',
        'type',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'employee_id',
        'type',
        'deleted_at'
    ];

    public function details(): HasOne {
        return $this->hasOne(Details::class);
    }
}