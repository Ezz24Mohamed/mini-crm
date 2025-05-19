<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    protected $fillable=[
        'customer_id',
        'employee_id',
        'action_type',
        'result'
    ];
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function employee(){
        return $this->belongsTo(User::class,'employee_id');
    }
}
