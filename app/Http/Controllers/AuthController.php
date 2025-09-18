<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show register form
    public function showRegister() {
        if (Auth::check()) {
            return redirect('/task_list');
        }
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request) {
        
        $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('task_list');
    }

    // Show login form
    public function showLogin() {
        if (Auth::check()) {
            return redirect('/task_list');
        }
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            //return redirect()->intended('dashboard');
           // return redirect()->route('task_list');
        }else{
            dd('ok');
        }
        return back()->withErrors(['email'=>'Invalid credentials']);
    }

    // Handle logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
