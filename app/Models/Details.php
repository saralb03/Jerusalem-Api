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
        'rank',
        'surname',
        'first_name',
        'department',
        'branch',
        'section',
        'division',
        'date_of_birth',
        'security_class_start_date',
        'service_start_date',
        'age',
        'classification',
        'classification_name',
        'population_id',
        'employee_id',
        'phone_number',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
