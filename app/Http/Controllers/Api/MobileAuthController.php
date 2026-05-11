<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class MobileAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function register(Request $request)
    {
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'customer',
        'is_aktif' => true,
    ]);

    $token = $user->createToken('mobile-token')->plainTextToken;

    return response()->json([
        'message' => 'Registrasi berhasil',
        'token' => $token,
        'user' => $user
    ], 201);
    }

    public function sendOtp(Request $request)
    {
    $request->validate([
        'email' => 'required|email|unique:users,email',
    ]);

    $otp = rand(1000, 9999);

    Otp::updateOrCreate(
        ['email' => $request->email],
        [
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]
    );

    Mail::raw("Kode OTP PawPet kamu adalah: $otp", function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Kode OTP PawPet');
    });

    return response()->json([
        'message' => 'OTP berhasil dikirim'
    ]);
    }

    public function verifyOtpAndRegister(Request $request)
    {
    $request->validate([
        'nama' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'otp' => 'required',
    ]);

    $otpData = Otp::where('email', $request->email)
        ->where('otp', $request->otp)
        ->first();

    if (!$otpData) {
        return response()->json([
            'message' => 'OTP salah'
        ], 400);
    }

    if (Carbon::now()->gt($otpData->expired_at)) {
        return response()->json([
            'message' => 'OTP sudah expired'
        ], 400);
    }

    $user = User::create([
    'nama' => $request->nama,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => 'customer',
    ]);
    $otpData->delete();
    $token = $user->createToken('mobile-token')->plainTextToken;
    
    return response()->json([
        'message' => 'Register berhasil',
        'token' => $token,
        'user' => $user,
    ]);
    }

    public function sendForgotPasswordOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $otp = rand(1000, 9999);

    Otp::updateOrCreate(
        ['email' => $request->email],
        [
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]
    );

    Mail::raw("Kode OTP reset password PawPet kamu adalah: $otp", function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Reset Password PawPet');
    });

    return response()->json([
        'message' => 'OTP reset password berhasil dikirim'
    ]);
}

public function verifyForgotPasswordOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required',
    ]);

    $otpData = Otp::where('email', $request->email)
        ->where('otp', $request->otp)
        ->first();

    if (!$otpData) {
        return response()->json([
            'message' => 'OTP salah'
        ], 400);
    }

    if (Carbon::now()->gt($otpData->expired_at)) {
        return response()->json([
            'message' => 'OTP sudah expired'
        ], 400);
    }

    return response()->json([
        'message' => 'OTP valid'
    ]);
}

public function resetForgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required',
        'password' => 'required|min:6',
    ]);

    $otpData = Otp::where('email', $request->email)
        ->where('otp', $request->otp)
        ->first();

    if (!$otpData) {
        return response()->json([
            'message' => 'OTP salah'
        ], 400);
    }

    if (Carbon::now()->gt($otpData->expired_at)) {
        return response()->json([
            'message' => 'OTP sudah expired'
        ], 400);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    $otpData->delete();

    return response()->json([
        'message' => 'Password berhasil diubah'
    ]);
}
}