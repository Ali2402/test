<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class VoucherController extends Controller
{
    
    public function api_get_voucher(Request $request)
    {
        $data = DB::table('vouchers')
        ->select('vouchers.id', 'vouchers.user_id' , 'vouchers.code' ,'vouchers.status' , 'vouchers.created_at' , 'vouchers.amount','users.username as author')->join('users', 'vouchers.user_id' ,'=','users.id')
        ->paginate(25);

        return ryuReply('SUCCESS',$data,200);
    }
    public function admin_index()
    {
        $data['users'] = DB::table('users')->get();
        return view('admin.voucher',$data);
    }
    public function add_voucher(Request $request)
    {
        $validator = Validator::make($request->all(),
        ['user' => ['required'],
        'amount' => ['required']
        ]);

        $amount = $request->amount;
        $user = $request->user;
        $p1 = substr(md5(time()),0,5);
        $p2 = substr(sha1($amount) ,2,5);
        $p3 = substr(md5($user) , 3,5);
        $code = "RYU".rand(0,3)."-$p1-$p2-$p3";
        if(!$validator->fails())
        {
            DB::table('vouchers')->insert([
                ['code' => strtoupper($code),
                'status' => 1,
                'amount' => $amount,
                'user_id' => $user]
            ]);

            return redirect('/admin/voucher')->with('success' , strtoupper($code));
        }else{
            return redirect('/admin/voucher')->with('error' , 'Failed to create new voucher');
        }
    }
    public function redeem(Request $request)
    {
        $validator = Validator::make($request->all(),['code' => ['required','string']]);

        if(!$validator->fails()){
        $code = $request->code;
        $check = DB::table('vouchers')->where('code',$code )->where('user_id' , session()->get('user_id'))->first();
        if($check)
        {
            // update status code to invalid
            DB::table('vouchers')->where('code' , $code)->update(['status' => 0]);
            // get current balance
            $balance = DB::table('users')->where('id' , session()->get('user_id'))->first()->balance;
            // jumlahkan balance dari vouchers.
            $amount = $check->amount;
            $balance_update=($balance+$amount);
            DB::table('users')->where('id' , session()->get('user_id'))->update(['balance' => $balance_update]);

            // save to logs

            DB::table('logs')->insert([
                ['user_id' => session()->get('user_id'),
                'type' => 2 ,
                'desc' => 'Redeem voucher : '.$code.' Success !',
                'amount' => $amount,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ]
            ]);

            return redirect('/balance')->with('msg', 'Redeem voucher successfully !');

        }else{
            return redirect('/balance')->with('msg' , 'Oops! invalid code ~');
        }

        }else{

            return redirect('/balance')->with('msg' , 'OOps! something wrong ~');
        }
        
    }

    public function delete_voucher(Request $request){
        DB::table('vouchers')->where('id' , $request->id)->delete();
        return redirect()->back()->with('msg', 'Succesfully delete voucher!');
    }
}
