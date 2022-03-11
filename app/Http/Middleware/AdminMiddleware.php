<?php

namespace App\Http\Middleware;

use Closure, DB;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session()->get('logged_in')){
            if(DB::table('users')->where('id', session()->get('user_id'))->first()->role == 1){
                return $next($request);
            } else {
                return redirect('/login');
            }
        } else {
            $ip_addr = $_SERVER['REMOTE_ADDR'];
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            DB::table('user_devices')->where(['ip' => $ip_addr , 'user_agent' => $useragent])->delete();
            return redirect('/login');
        }
    }
}
