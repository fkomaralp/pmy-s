<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["event_id", "price_id"];

    public function price()
    {
        return $this->hasOne(Price::class, "id", "price_id");
    }
}
