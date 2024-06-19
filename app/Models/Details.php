<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Details extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guard_name = "passport";

    protected $fillable = [
        'personal_id',
        'prefix',
        'ranks',
        'surname',
        'first_name',
        'department',
        'branch',
        'section',
        'division',
        'service_type',
        'date_of_birth',
        'service_type_code',
        'security_class_start_date',
        'service_start_date',
        'solider_type',
        'age',
        'classification',
        'classification_name',
        'population_id',
        'employee_id',
        'phone_number',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
