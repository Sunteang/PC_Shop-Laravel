<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    protected $table = 'tblcarts';
    protected $fillable = ['user_id', 'product_type', 'product_id', 'quantity'];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}
