<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validate data
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'userType' => 'required|string|max:50',
        ]);

        try {
            // Create user
            $user = new User();
            $user->userId = Str::uuid(); // Generate a unique userId
            $user->fullname = $validatedData['fullname'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']); // Hash password
            $user->userType = $validatedData['userType'];
            $user->save();

            // Return success response
            return response()->json(['status' => true, 'message' => 'Pendaftaran berhasil! silahkan login']);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function login(Request $request)
    {
        // Validate data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            // Get user by email
            $user = User::where('email', $validatedData['email'])->first();

            if ($user) {
                // Verify password
                if (Hash::check($validatedData['password'], $user->password)) {
                    // Remove password from user data
                    $userData = $user->makeHidden('password')->toArray();

                    // Encrypt user data using Base64
                    $encryptedUserData = base64_encode(json_encode($userData));

                    return response()->json([
                        'status' => true,
                        'message' => 'Login berhasil!',
                        'authorize' => $encryptedUserData
                    ]);
                } else {
                    return response()->json(['status' => false, 'message' => 'Password salah!']);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Pengguna tidak ditemukan!']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

   public function changePassword(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'email' => 'required|email',
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // Confirmed for additional security
        ]);

        try {
            // Get the user by email
            $user = User::where('email', $validatedData['email'])->first();

            if ($user) {
                // Verify the old password
                if (Hash::check($validatedData['old_password'], $user->password)) {
                    // Hash the new password
                    $user->password = Hash::make($validatedData['new_password']);
                    $user->save();

                    return response()->json(['status' => true, 'message' => 'Password baru berhasil disimpan! silahkan login']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Password lama salah!']);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Email tidak ditemukan!']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
