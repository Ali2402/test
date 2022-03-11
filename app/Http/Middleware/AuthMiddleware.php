<?php

namespace App\Http\Middleware;

use Closure, DB;

class AuthMiddleware
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
            return $next($request);
        } else {
            $fullip = explode(".",$_SERVER['REMOTE_ADDR']);
            $ip_addr =  $fullip[0].".".$fullip[1];
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            DB::table('user_devices')->where('user_agent',$useragent)->where('ip','like',"$ip_addr%")->delete();
            return redirect('/login');
        }
    }
}
