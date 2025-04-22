<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id', 'name', 'address', 'phone', 'paypal_order_id', 'total', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
