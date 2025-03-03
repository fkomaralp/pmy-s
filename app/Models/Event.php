<?php

namespace App\Models;

use App\Events\EventCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Event extends Model
{
    use HasFactory, HasApiTokens/*, SoftDeletes*/
        ;

    protected $fillable = ["title", "protected", "image", "note", /*"country_id", "city_id",*/
        "country", "city", "event_date", "event_code", "bucket_name", "status", "is_sponsored",
        "sponsor_fit_image_to_width", "sponsor_position", "sponsor_horizontal_image", "sponsor_vertical_image", "sponsor_opacity"];

    protected $hidden = ["created_at", "deleted_at", "updated_at"];

    protected $casts = [
      "event_date" => "datetime",
        "status" => "boolean",
        "protected" => "boolean",
        "is_sponsored" => "boolean",
        "sponsor_fit_image_to_width" => "boolean",
    ];

    protected $dates = ['event_date'];

    protected $dispatchesEvents = [
        'created' => EventCreated::class,
    ];

//    public function country()
//    {
//        return $this->hasOne(Country::class, "id", "country_id");
//    }
//
//    public function city()
//    {
//        return $this->hasOne(City::class, "id", "city_id");
//    }

    public function photos()
    {
        return $this->hasMany(Photos::class, "event_id", "id");
    }

//    public function photosManytoMany()
//    {
//        return $this->ManyToMany(Photos::class, "event_id", "id");
//    }

    public function bib_numbers()
    {
        return $this->hasManyThrough(BibNumber::class, Photos::class, null, 'photo_id');
    }

    public function price_list()
    {
        return $this->hasMany(EventPrice::class, "event_id", "id");
    }

    public function filters()
    {
        return $this->hasMany(EventFilters::class, "event_id", "id");
    }
}
