<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["price", "type", "title", "status"];

    protected $appends = ["text_type"];

    public function getTextTypeAttribute()
    {
        switch ($this->type){
            case 0:
                $type = "HD";
                break;
            case 1:
                $type = "4K";
                break;
            case 2:
                $type = "Sponsored";
                break;
        }

        return $type;
    }
}
