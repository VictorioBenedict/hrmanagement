<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFields extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_fieldname',
        'document_type_id',
        'is_visible'
    ];

    public function typeConnection()
    {
        return $this->belongsTo(DocumentTypes::class, 'document_type_id');
    }
}
