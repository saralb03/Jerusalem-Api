<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guard_name = "passport";

    protected $fillable = [
        'personal_id',
        'personal_number',
        'prefix',
        'ranks',
        'surname',
        'first_name',
        'department',
        'division',
        'service_type',
        'date_of_birth',
        'service_type_code',
        'security_class_start_date',
        'service_start_date',
        'solider_type',
        'age',
        'classification',
        'phone_number',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
