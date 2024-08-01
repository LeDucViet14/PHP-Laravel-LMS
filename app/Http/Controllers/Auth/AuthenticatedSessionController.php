<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    // http://127.0.0.1:8000/login
    public function create(): View
    {
        return view('frontend.dashboard.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    //  xác thực người dùng login
    public function store(LoginRequest $request): RedirectResponse
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        // $url = '';
        // if ($request->user()->role === 'admin') {
        //     $url = 'admin/dashboard';
        // } elseif ($request->user()->role === 'instructor') {
        //     $url = 'instructor/dashboard';
        // } elseif ($request->user()->role === 'user') {
        //     $url = '/dashboard';
        // }

        // return redirect()->intended($url);
        $url = '';
        $notification = array(
            'message' => 'Login Successfully',
            'alert-type' => 'success'
        );

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('rememberMeCheckbox'); // Kiểm tra xem checkbox "Remember Me" có được chọn không
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ($request->user()->role === 'admin') {
                $url = 'admin/dashboard';
            } elseif ($request->user()->role === 'instructor') {
                $url = 'instructor/dashboard';
            } elseif ($request->user()->role === 'user') {
                $url = '/dashboard';
            }
            return redirect()->intended($url)->with($notification);
        } else {
            return redirect()->back()->with([
                'message' => 'Incorrect email or password !'
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
