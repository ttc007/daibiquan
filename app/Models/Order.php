<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'total_price', 'customer_id'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
