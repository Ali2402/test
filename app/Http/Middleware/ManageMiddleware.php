<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class ManageMiddleware
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
        $check_purchases = DB::table('purchases')->where('user_id' , session()->get('user_id'))->first();
        if($check_purchases){
        return $next($request);
        }else{

            return redirect('/home')->with('msg', 'You must buy product first for access manage');
        }
    }
}
