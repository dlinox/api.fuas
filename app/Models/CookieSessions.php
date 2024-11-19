<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieSessions extends Model
{
    use HasFactory;

    protected $table = 'cookie_sessions';
    protected $fillable = ['session_id', 'browser_fingerprint', 'user_agent'];

}
