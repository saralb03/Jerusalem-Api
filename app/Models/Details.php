<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $guard_name = "passport";

    protected $fillable = [
        'rank',
        'department',
        'branch',
        'section',
        'division',
        'date_of_birth',
        'security_class_start_date',
        'age',
        'classification',
        'classification_name',
        'phone_number',
        'profession',
        'gender',
        'religion',
        'country_of_birth',
        'release_date',
        'employee_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
