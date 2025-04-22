<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class CustomerAuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:100",
            "email" => "required|email|unique:customers",
            "password" => "required|min:6",
        ]);

        $customer = Customer::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($customer);

        return response()->json([
            "message" => "Customer registered successfully",
            "token" => $token,
            "customer" => $customer,
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('customers')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            "message" => "Login successful",
            "token" => $token,
            "customer" => auth('customers')->user(),
        ]);
    }

    // Authenticated customer
    public function me()
    {
        return response()->json(auth('customers')->user());
    }

    // Logout
    public function logout()
    {
        auth('customers')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
