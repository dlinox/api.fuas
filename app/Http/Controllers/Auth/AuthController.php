<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function signIn(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = $this->user->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        if ($user->status == 0) {
            return response()->json([
                'message' => 'Usuario inactivo'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        $role = Role::where('name', $user->getRoleNames()[0])->first();

        return response()->json([
            'token' => $token,
            'user' => [
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role' => $role->name,
                'redirect_route' => $role->redirect_route,
            ],
            'permissions' => implode('|', $user->getAllPermissions()->pluck('name')->toArray()),
        ]);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function user(Request $request)
    {
        $user =  $request->user();

        $role = Role::where('name', $user->getRoleNames()[0])->first();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'role' => $user->getRoleNames()[0],
                'email' => $user->email,
                'redirect_route' => $role->redirect_route,
            ],
            'permissions' => implode('|', $user->getAllPermissions()->pluck('name')->toArray()),
        ]);
    }
}
