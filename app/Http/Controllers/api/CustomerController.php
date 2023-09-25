<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    /**
     * @OA\POST(
     *     path="/api/regisger",
     *     tags={"Account"},
     *     summary="Account Login",
     *     description="Account Login",
     *     @OA\RequestBody(
     *         description="User objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_name",
     *                     description="user_name",
     *                     type="string",
     *                     example="admin"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="password",
     *                     type="string",
     *                     example="admin"
     *                 ),
     *                 required={"user_name","password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *   @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *   @OA\JsonContent()
     *     )
     * )
     */
    public function register(Request $request)
    {
        // Validation rules for registration
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:customers',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create a personal access token
        $token = $customer->createToken('CustomerToken')->accessToken;

        return response()->json([
            'access_token' => $token,
            'user' => $customer,
        ], 201);
    }





    public function login(Request $request)
    {
        // Validation rules for login
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Attempt to authenticate
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $customer = auth()->user();
            $token = $customer->createToken('CustomerToken')->accessToken;

            return response()->json(['access_token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function profile(Request $request)
    {
        // Retrieve the authenticated user
        $user = $request->user();

        // You can return the user or customize the response as needed
        return response()->json(['user' => $user]);
    }
}