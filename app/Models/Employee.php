<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','firstname','middlename','lastname', 'email', 'employee_image','employee_id','department_id','designation_id','date_of_birth','phone','location','role','isArchived'
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }

    public function payroll()
    {
        return $this->hasOne(Payroll::class, 'employee_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
