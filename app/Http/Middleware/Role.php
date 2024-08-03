<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\User;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role) // biến $role được truyền thông qua tham số khi đăng kí middleware cho 1 route trong file web.php
    {                                                              // ví dụ: Route::middleware(['auth', 'roles:admin'])->group(function () {


        /// Check user đang active or inactive
        if (Auth::check()) {
            $expireTime = Carbon::now()->addSeconds(30);
            //Lưu trạng thái online của người dùng vào bộ nhớ đệm (cache)
            Cache::put('user-is-online' . Auth::user()->id, true, $expireTime);
            //Cập nhật thời gian cuối cùng người dùng truy cập vào cơ sở dữ liệu
            User::where('id', Auth::user()->id)->update(['last_seen' => Carbon::now()]);
        }


        // role của người đăng nhập hiện tại
        $userRole = $request->user()->role;
        // Nếu người dùng có vai trò là 'user' và vai trò yêu cầu không phải 'user':
        if ($userRole === 'user' && $role !== 'user') {
            return redirect('dashboard');
            //Nếu người dùng có vai trò là 'admin' và vai trò yêu cầu là 'user':
        } elseif ($userRole === 'admin' && $role === 'user') {
            return redirect('/admin/dashboard');
        } elseif ($userRole === 'instructor' && $role === 'user') {
            return redirect('/instructor/dashboard');
        } elseif ($userRole === 'admin' && $role === 'instructor') {
            return redirect('/admin/dashboard');
        } elseif ($userRole === 'instructor' && $role === 'admin') {
            return redirect('/instructor/dashboard');
        }
        return $next($request);
    }
}
