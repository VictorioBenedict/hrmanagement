<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingFields extends Model
{
    use HasFactory;
    protected $fillable = [
        'incoming_fieldname',
        'action_type_id',
        'is_visible'
    ];

    public function typeConnection()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }
}
