<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function register(Request $request)
    {


        try {
            $this->validate(request(), [
                'username' => 'required|min:3|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6|max:255',
                'password_confirmation' => 'required_with:password|same:password|min:6|max:255'
            ]);
        } catch (\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e], 400);
        }

        $input = request()->only('username', 'email', 'password');


        try {
            $createUser = new User($input);

            if ($createUser->save()) {
                return $this->login($request);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Check if field is empty
        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 60 * 24 * 30
        ]);
    }
}
