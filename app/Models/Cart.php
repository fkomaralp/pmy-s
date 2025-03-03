<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "event_id", "price_id", "token", "bib_number"];

    protected $table = "cart";

    public function event()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function event_price()
    {
        return $this->hasOne(EventPrice::class, 'price_id', 'price_id');
    }

}
