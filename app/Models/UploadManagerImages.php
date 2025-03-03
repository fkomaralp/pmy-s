<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class UploadManagerImages extends Model
{
    protected $fillable = ["event_id", "file_name", "path", "status", "photo_date"];

    protected $table = "upload_manager_images";

    protected $casts = ["status" => "integer", "event_id" => "integer"];

    public function event()
    {
        return $this->hasOne(Event::class, "id", "event_id");
    }
}
