<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{

    public function login(){

        $ip_addr = $_SERVER['REMOTE_ADDR'];
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if(session()->get('logged_in') == null)
        {
            if(DB::table('user_devices')->where('ip',$ip_addr)->where('user_agent' , $useragent)->first())
            {
                DB::table('user_devices')->where('ip',$ip_addr)->where('user_agent' , $useragent)->delete();
            }
        }
        $check_devices = DB::table('user_devices')->where('ip' , $ip_addr)->where('user_agent' , $useragent)->first();
        if(!$check_devices){
            return view('auth.login');
        }else{

            $auth = DB::table('users')->where('id' , $check_devices->user_id)->first();

            session([
                'logged_in' => true,
                'user_id' => $auth->id,
                'role' => $auth->role
            ]);
        // insert to logs auth.
        DB::table('logs')->insert([
            ['user_id' => $auth->id,
            'type' => 1,
            'desc' => 'User logged in ( current session )',
            'ip_address' => $ip_addr,
            'user_agent' => $useragent
            ]
        ]);

        return redirect('/home');

        }
    }

    public function register(){
        return view('auth.register');
    }

    public function doLogin(Request $request){

        $validator = Validator::make($request->all() , [
            'email' => ['required' , 'string' , 'max:255'],
            'password' => ['required' , 'string' , 'max:255'] 
        ]);

        if(!$validator->fails()){
            $auth = DB::table('users')->where('email' , $request->email)->first();
            if($auth && Hash::check($request->password , $auth->password)){
                $fullip = explode(".",$_SERVER['REMOTE_ADDR']);
            $ip_addr =  $fullip[0].".".$fullip[1];
            $useragent = $_SERVER['HTTP_USER_AGENT'];
             

                $check_devices = DB::table('user_devices')->where('ip' ,'like', "$ip_addr%")->where('user_agent' , $useragent)->where('user_id' , $auth->id)->first();
                if(!$check_devices){
                    $count_devices = DB::table('user_devices')->where('user_id' , $auth->id)->count();
                    if($count_devices < 3){
                        // dd("device kurang 3");
                        DB::table('user_devices')->insert([ 
                            [
                                'ip' => implode(".",$fullip),
                                'user_agent' =>$useragent,
                                'user_id' => $auth->id
                            ]
                        ]);

                        session([
                            'logged_in' => true,
                            'user_id' => $auth->id,
                            'role' => $auth->role
                        ]);
                        // insert to logs auth.
                        DB::table('logs')->insert([
                            ['user_id' => $auth->id,
                            'type' => 1,
                            'desc' => 'User logged in !',
                            'ip_address' => implode(".",$fullip),
                            'user_agent' => $useragent
                            ]
                        ]);

                        return redirect('/home');
                    }else{
                        return redirect('/login')->with('msg' , 'maximum_device');
                    }
                } else{
                    session([
                        'logged_in' => true,
                        'user_id' => $auth->id,
                        'role' => $auth->role
                    ]);
                // insert to logs auth.
                DB::table('logs')->insert([
                    ['user_id' => $auth->id,
                    'type' => 1,
                    'desc' => 'User logged in ( current session )',
                    'ip_address' => implode(".",$fullip),
                    'user_agent' => $useragent
                    ]
                ]);
                    return redirect('/home');
                }

        } else {
            return redirect('/login')->with('msg', 'failed_password');
        }
        } else { // validator fail
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
    }

    public function doRegister(Request $request){
        $reff = "";
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'invite_code' => ['required', 'string', 'max:255']
        ]);

        if(!$validator->fails()){
            if(DB::table('invite_code')->where('code', $request->invite_code)->where('status', 1)->first()){
                if(isset($request->reff)){
                    $reff = $request->reff;
                    $Userreff = DB::table('users')->where('reff' , $reff)->first();
                    if($Userreff)
                    {
                        DB::table('refferal')->insert([
                            ['id_user' => $Userreff->id,
                            'reff' => $reff,
                            'reward' => 1,
                        ]
                        ]);
                    }

                }

                $makeReff = substr(sha1($request->username.$request->email) , 0,8);
                $save = DB::table('users')->insert([
                    ['username' => $request->username , 
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'reff' => $makeReff ,
                    'balance' => 0,
                    'domain_limit' => 3,
                    'role' => 2

                    ]
                ]);

                if($save)
                {
                    DB::table('invite_code')->where('code' , $request->invite_code)->update(['status' => 0, 'description' => 'Used by : '.$request->username]);
                    return redirect('/login')->with('msg' ,'success');
                }else{
                    return redirect('/register')->with('msg' ,'db_error');
                }
                
            } else {
                return redirect('/register')->with('msg' , 'invalid_invite');
            }
            // DB::table('users')->insert([
            //     [
            //         'username' => $request->username,
            //         'email' => $request->email,
            //         'password' => Hash::make($request->password),
            //         'reff' => $request->reff,
            //     ]
            // ]);
        } else {
            return redirect('/register')->with('msg' , 'invalid_form');
        }

    }

}
