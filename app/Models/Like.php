<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
