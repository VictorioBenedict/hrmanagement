<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveFields extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_fieldname',
        'leave_type_id',
        'is_visible'
    ];

    public function typeConnection()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
