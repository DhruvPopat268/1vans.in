<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function engineerlogin(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
             'engineer_token' => 'nullable|string',
        ]);

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 0, 'error' => 'User not found'], 404);
        }

        // Check if the user's type is 'app user'
        if ($user->type !== 'app user') {
            return response()->json(['status' => 0, 'error' => 'Access denied. Not an app user.'], 403);
        }

        // Check if login is enabled for the user
        if ($user->is_enable_login == 0) {
            return response()->json(['status' => 0, 'error' => 'Login has been disabled for this user.'], 403);
        }

         // Check if login is enabled for the user
         if ($user->is_disable == 0) {
            return response()->json(['status' => 0, 'error' => 'Login has been disabled for this user.'], 403);
        }

        // Check if the user's account is restricted
        if ($user->delete_status === 0) {
            return response()->json(['status' => 0, 'error' => 'Your account has been temporarily restricted. Please contact customer support for assistance.'], 403);
        }

        // Check if the password is correct
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 0, 'error' => 'Invalid credentials'], 401);
        }
        
          // Update engineer_token if provided
        if ($request->has('engineer_token')) {
        $user->engineer_token = $request->engineer_token; // Save the tokens in User model
        $user->save();
    }

        // Revoke any existing tokens for the user
        $user->tokens()->delete();

        // Generate a new token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        $avatarUrl = null;
        if ($user->avatar) {
            $avatarUrl = asset('storage/uploads/avatar/' . $user->avatar);
        }

        return response()->json([
            'status' => 1,
            'token' => $token,
            'engineer' => [
                'id' => $user->id,
                'name' => $user->name,
                'profile_url' => $avatarUrl,
            ],
        ]);
    }


}
