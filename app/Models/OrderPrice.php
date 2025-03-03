<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPrice extends Model
{
    use HasFactory;

    protected $fillable = ["order_id", "price", "type", "title"];

    public function order()
    {
        return $this->hasOne(Order::class, "id", "order_id");
    }
}
