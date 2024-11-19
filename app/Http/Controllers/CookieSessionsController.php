<?php

namespace App\Http\Controllers;

use App\Models\CookieSessions;
use Illuminate\Http\Request;

class CookieSessionsController extends Controller
{

    protected $cookieSessions;

    public function __construct(CookieSessions $cookieSessions)
    {
        $this->cookieSessions = $cookieSessions;
    }

    public function store(Request $request)
    {
        $cookieSession = $this->cookieSessions->create([
            'session_id' => $request->sessionId(),
            'browser_fingerprint' => 'asdasdad',
            'user_agent' => 'asdasdad',
        ]);

        return response()->json($cookieSession);
    }
}
