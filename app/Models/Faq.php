<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;

    protected $fillable = ["title"];

    public function questions()
    {
        return $this->hasMany(Question::class, "faq_id", "id");
    }
}
