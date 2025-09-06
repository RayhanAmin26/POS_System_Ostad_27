<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['customer_id','total','items'];
    protected $casts = ['items' => 'array'];
    public function customer(){ return $this->belongsTo(Customer::class); }
}

