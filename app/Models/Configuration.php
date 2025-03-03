<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = ["name", "value"];

    public static function getValue($name)
    {
        if(is_array($name)){
            try{
                self::where("name", $name)->get(["value"]);
            } catch (\Exception $e){
                return "";
            }
        } else {
            try {
                return self::where("name", $name)->first()->value;
            } catch (\Exception $e){
                return "";
            }
        }
    }
}
