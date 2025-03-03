<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["user_id", "event_title", "bib_number", "status",
        "bucket_name", "event_id", "order_number", "job_status", "job_uuid", "job_exception"];

    protected $casts = [
        "job_status" => "integer"
    ];


    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function event()
    {
        return $this->hasOne(Event::class, "id", "event_id");
    }

    public function orderPrice()
    {
        return $this->hasOne(OrderPrice::class, "order_id", "id");
    }

    public function generateDownloadUrl()
    {
        $email = $this->user->email;
        $file_name = "picmyrun_".$email."-".$this->order_number.".zip";

        return $file_name;
    }
}
