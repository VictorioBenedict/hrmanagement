<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'date',
        'purposes',
        'requestedDocs',
        'statuses_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function type()
    {
        return $this->belongsTo(DocumentTypes::class, 'document_type');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'statuses_id');
    }

    // public function deletedStatus()
    // {
    //     return $this->hasMany(DeletedStatus::class);
    // }


}
