<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user');
    }
    public function api_get_user(Request $request)
    {
        $data = DB::table('users')->select('id', 'username', 'email', 'balance', 'created_at' , 'reff', 'domain_limit', 'role')->paginate(10);
        return ryuReply('SUCCESS', $data, 200);
    }
    public function api_get_invitecode(Request $request)
    {
        $data = DB::table('invite_code')->select('*')->paginate(10);
        return ryuReply('SUCCESS' , $data,200);
    }

    public function generate_invitecode()
    {
        $hash = sha1(time().rand().$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        $hash_limit = strtoupper(substr($hash,0,10));
        $format = "RYU{$hash_limit}XI";

        DB::table('invite_code')->insert([
            ['code' => $format,
            'status' => 1,
            'description' => 'Code still valid',
            'author' => DB::table('users')->where('id',session()->get('user_id'))->first()->username,
             ]
        ]);

        return redirect('/admin/user')->with('code',$format);

    }

    public function addUserAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);
        if(!$validator->fails()){
            $makeReff = substr(sha1($request->username.$request->email) , 0,8);
            $save = DB::table('users')->insert([
                [
                    'username' => $request->username, 
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'reff' => $makeReff,
                    'balance' => $request->balance,
                    'domain_limit' => 3,
                    'role' => 2
                ]
            ]);

            if($save){
                return ryuReply('SUCCESS', array(
                    'msg' => 'User '. $request->username.' has been added!'
                ), 200);
            }else{
                return ryuReply('SUCCESS', array(
                    'msg' => 'Oops db_error'
                ), 200);
            }
        } else {
            return ryuReply('SUCCESS', array(
                'msg' => 'Oops invalid_form'
            ), 200);
        }
    }

    public function updateUserAdmin(Request $request){
        $password = $request->password;
        $save = false;
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
        ]);
        if(!$validator->fails()){
            if(isset($password)) {
                $save = DB::table('users')->where('id', $request->id)->update([
                    'username' => $request->username, 
                    'email' => $request->email,
                    'balance' => $request->balance,
                    'password' => Hash::make($request->password),
                ]);
            } else {
                $save = DB::table('users')->where('id', $request->id)->update([
                    'username' => $request->username, 
                    'email' => $request->email,
                    'balance' => $request->balance,
                ]);
            }

            if($save){
                return ryuReply('SUCCESS', array(
                    'msg' => 'User '. $request->username.' succesfully updated!'
                ), 200);
            }else{
                return ryuReply('SUCCESS', array(
                    'msg' => 'Oops db_error'
                ), 200);
            }
        } else {
            return ryuReply('SUCCESS', array(
                'msg' => 'Oops invalid_form'
            ), 200);
        }
    }

    public function deleteUserAdmin(Request $request){
        DB::table('users')->where('id', $request->id)->delete();
        return redirect('/admin/user');
    }

    public function account_index(){
        $user = DB::table('users')->where('id', session()->get('user_id'))->first();
        $data = array(
            'username' => $user->username,
            'email' => $user->email,
            'balance' => $user->balance
        );
        return view('account.index', compact('data'));
    }

    public function updatePassowrd(Request $request){
        $new_password = $request->new_password;
        $old_password = $request->old_password;
        if(isset($new_password) && isset($old_password)){
            if(Hash::check($old_password, DB::table('users')->where('id', session()->get('user_id'))->first()->password)){
                DB::table('users')->where('id', session()->get('user_id'))->update([
                    'password' => Hash::make($new_password)
                ]);
                return redirect('/account')->with('msg', 'Password succesffully updated!');
            } else {
                return redirect('/account')->with('msg', 'Oops! Invalid old password');
            }
        } else {
            return redirect('/account')->with('msg', 'Oops! Invalid Form');
        }
    }
}
