<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    const COMMISSION_RATE = 0.085; 

    protected $fillable = ['seller_id', 'amount', 'sale_date'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value;
        $this->attributes['commission'] = $value * self::COMMISSION_RATE;
    }
}
