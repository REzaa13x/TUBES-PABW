<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (! Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah. Silakan coba lagi.',
            ])->withInput($request->only('email', 'remember'));
        }
        $request->session()->regenerate();
        if (Auth::guard('web')->user()->role === 'admin') {
            $request->session()->forget('url.intended');
            return redirect('/admin/dashboard');
        }
        return redirect()->intended('/');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get the post-login redirect path.
     *
     * @return string
     */
    protected function redirectTo()
    {
        // Redirect based on user role - we'll determine this in the login method after authentication
        // For now, return a default value that will be overridden in the login method
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}
