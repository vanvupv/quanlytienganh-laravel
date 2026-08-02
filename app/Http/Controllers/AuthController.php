<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('profile.index');
        }
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('profile.index')->with('success', 'Chào mừng quay trở lại, ' . Auth::user()->name . '!');
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Thông tin đăng nhập không hợp lệ. Vui lòng kiểm tra lại!');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('profile.index');
        }
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the user with default profile attributes
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'english_level' => 'B1',
            'target_goal' => 'IELTS 7.0 & Giao tiếp trôi chảy',
            'learning_speed' => 'Trung bình',
            'retention_rate' => 75,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('profile.index')->with('success', 'Đăng ký tài khoản thành công! Hãy bắt đầu lộ trình của riêng bạn.');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đăng xuất tài khoản thành công!');
    }
}
