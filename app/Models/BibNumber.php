<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class BibNumber extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = ["photo_id", "bib_number"];

    protected $table = "bib_numbers";

    protected $casts = [
        "bib_number" => "string"
    ];

    public function photo()
    {
        return $this->belongsTo(Photos::class, "photo_id", "id");
    }
}
