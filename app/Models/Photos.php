<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Photos extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = ["event_id", "file_name", "thumbnail"];

    public function event()
    {
        return $this->hasOne(Event::class, "id", "event_id");
    }

    public function bib_numbers()
    {
        return $this->hasMany(BibNumber::class, "photo_id", "id");
    }
}
