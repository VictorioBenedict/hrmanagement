<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'statuses_id',
        'status_type'
    ];
}
