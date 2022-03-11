<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TransactionController extends Controller
{
    public function index()
    {
        return view('balance.index');
    }

    public function get_history(Request $request){
        $data = DB::table('logs')
                ->select('id', 'desc', 'amount', 'ip_address', 'created_at as date')
                ->where('user_id', session()->get('user_id'))
                ->where('type', 2)
                ->paginate(10);
        return ryuReply('SUCCESS', $data, 200);
    }

    public function buyProduct(Request $request){
        $user = DB::table('users')->where('id', session()->get('user_id'))->first();
        $product = DB::table('products')->where('id', $request->id)->first();
        if($product){
            if($user->balance >= $product->price){

                if(DB::table('purchases')->where('user_id' , session()->get('user_id'))->where('product_id' , $product->id )->first() )
                {
                    return redirect('/products')->with('msg', 'Failed, you was purchased this product.');
                }else{

                    DB::table('users')->where('id',session()->get('user_id'))->update(['balance'=> ($user->balance-$product->price)]);
                    
                    DB::table('purchases')->insert([
                        [
                            'product_id' => $product->id,
                            'user_id' => session()->get('user_id'),
                            'signature' => 'RYU'.sha1($user->username.$product->name.date('Y-m-d h:i:s')),
                            'price' => $product->price,
                            'desc' => "Product purchased: " . $product->name
                        ]
                    ]);
                    DB::table('logs')->insert([
                        [
                            'user_id' => session()->get('user_id'),
                            'type' => 2,
                            'desc' => 'Product purchased: ' . $product->name,
                            'amount' => $product->price,
                            'ip_address' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        ]
                    ]);
                    return redirect('/products')->with('msg' , 'Product has been paid!');
                }
            } else {
                return redirect('/products')->with('msg' , 'OOps! Balance less than product price');
            }
            
        } else {
            return redirect('/products')->with('msg' , 'OOps! something wrong ~');
        }
    }

}
