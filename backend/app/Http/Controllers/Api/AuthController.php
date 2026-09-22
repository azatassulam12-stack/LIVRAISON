<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function registerStore(Request $request): JsonResponse
    {
        $data = $request->validate(['store_name' => ['required','string','max:120'], 'name' => ['required','string','max:120'], 'email' => ['required','email','max:255','unique:users'], 'password' => ['required','string','min:12','confirmed'], 'phone' => ['nullable','string','max:30']]);
        [$store, $user] = DB::transaction(function () use ($data): array {
            $store = Store::create(['name' => $data['store_name'], 'phone' => $data['phone'] ?? null]);
            $user = User::create(['store_id' => $store->id, 'name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'], 'phone' => $data['phone'] ?? null, 'role' => User::STORE_ADMIN]);
            return [$store, $user];
        });
        return response()->json(['store' => $store, 'user' => $user, 'token' => $user->createToken('store-admin')->plainTextToken], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required','email'], 'password' => ['required','string']]);
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! $user->is_active || ! Hash::check($data['password'], $user->password)) abort(422, 'Identifiants invalides.');
        return response()->json(['user' => $user->load('store'), 'token' => $user->createToken('mobile-or-web')->plainTextToken]);
    }

    public function me(Request $request): JsonResponse { return response()->json($request->user()->load('store')); }
    public function logout(Request $request): JsonResponse { $request->user()->currentAccessToken()?->delete(); return response()->json(status: 204); }
}
