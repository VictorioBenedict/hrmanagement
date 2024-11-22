<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    protected $fillable = ['action_type_id', 'is_visible', 'typeConnection_id'];
    use HasFactory;
    protected $guarded = [];

}
