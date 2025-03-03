<?php

namespace App\Models;

use App\Events\EventCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = ["faq_id", "question", "answer"];
}
