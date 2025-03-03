<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventFilters extends Model
{
    use HasFactory;

    protected $fillable = ["event_id", "filter_from", "filter_to"];
}
