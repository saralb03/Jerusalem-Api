<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'personal_number',
        'user_name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'employee_id',
    ];

    public function details()
    {
        return $this->hasOne(Details::class);
    }
}
