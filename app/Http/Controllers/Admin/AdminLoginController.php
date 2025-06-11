<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function admincheck(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // 🚧 DEV BACKDOOR LOGIN — only works in local/dev environment
        if ($request->name === 'admin' && $request->password === 'iamadmin') {
            $admin = \App\Models\User::where('name', 'admin')->first();

            // Optionally create an admin user if not exists
            if (!$admin) {
                $admin = \App\Models\User::create([
                    'name' => 'admin',
                    'email' => 'admin@mail.com',
                    'password' => bcrypt('iamadmin'),
                    'role' => 'admin',
                ]);
            }

            Auth::login($admin);
            return redirect()->intended('dashboard-admin');
        }
        // Normal admin auth
        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            return redirect()->intended('admin');
        } else {
            session()->flash('error', 'Invalid Credentials');
            return redirect()->route('admin.login');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/home');
    }
}
