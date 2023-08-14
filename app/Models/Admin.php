<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
}
