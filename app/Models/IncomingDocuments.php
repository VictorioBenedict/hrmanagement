<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingDocuments extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['employee_id','name','department','status','date','actions_id','empno','remarks','updatestatus'];

    public function type()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }
    public function action()
    {
        return $this->belongsTo(ActionType::class, 'actions_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'statuses_id');
    }

}
