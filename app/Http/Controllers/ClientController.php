<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    
  

    public function validate_domain(Request $request)
    {
        $signature = $request->signature;
        $domain = $request->domain;

        $check = DB::table('purchases')->where('signature' , $signature)->first();
        if(!$check)
        {
            $response = ['status' => 'invalid',
                        'code' => 0,
                        'msg' => 'invalid signature ID.'
                        ];
        }else{
            $domain_check = DB::table('domains')->where('domain' , $domain)->first();

            if(!$domain_check)
            {
                $response = [
                    'status' => 'invalid',
                    'code' => 2,
                    'msg' => 'Domain not registered !'
                ];
            }else{
                $get = DB::table('users')
                ->select('users.username','users.id','domains.*')
                ->join('purchases','purchases.user_id' , '=','users.id')
                ->rightJoin('domains','domains.purchase_id','=','purchases.id')
                ->where('domains.domain' , $domain)->first();

                $response = [
                    'status' => 'valid',
                    'code' => 1,
                    'msg' => 'Domain valid',
                    'data' => [
                        'username' => $get->username,
                        'domain' => $get->domain,
                        'result_email' => $get->result_email,
                        'domain' => $get->domain,
                        'created_at' => $get->created_at,
                        'signature' => $signature,
                        'config' => $get->config
                    ]
                ];
            }
        }



        return response()->json($response);
    }

    public function getUser(Request $request)
    {
        $signature = $request->signature;
        $udb= DB::table('purchases')->where('signature' , $signature)->first();
        if(!$udb)
        {
            $response = ['status' => 'not_found',
                         'code' => 404,
                         'msg' => 'User not found '
                        ];
        }else{
            $userx = DB::table('users')->where('id' , $udb->user_id)->first();
            $prod = DB::table('products')->where('id' , $udb->product_id)->first();
            $response = ['status' => 'valid',
                        'code' => 200,
                    'msg' => 'user found',
                'data' => ['username' => $userx->username,
                        'email' => $userx->email,
                        'product' => $prod->name,
                        'version' => $prod->version,
                        'buy_date' => $prod->created_at,
                        'filename' => $prod->filename
                        ]
                    ];
        }
        return response()->json($response);
    }
    public function install(Request $request)
    {
        $signature = $request->signature;
        $product = str_replace(' ','',$request->pid);

    

        $udb= DB::table('purchases')->where('signature' , $signature)->first();
        if(!$udb)
        {
            $response = ['status' => 'not_found',
                         'code' => 404,
                         'msg' => 'User not found '
                        ];
        }else{
          
            $filename=url('repo/'.$product.'.zip');
            @copy(storage_path('zipfile/'.$product.'.zip') ,public_path('repo/'.$product.'.zip') );
            $userx = DB::table('users')->where('id' , $udb->user_id)->first();
            $prod = DB::table('products')->where('id' , $udb->product_id)->first();
            $response = ['status' => 'valid',
                        'code' => 200,
                    'msg' => 'user found',
                'data' => ['username' => $userx->username,
                        'email' => $userx->email,
                        'product' => $prod->name,
                        'version' => $prod->version,
                        'buy_date' => $prod->created_at,
                        'filename' => $filename
                        ]
                    ];
            if($request->d)
            {
                @unlink(public_path('repo/'.$product.'.zip'));
            }
            
        }
        return response()->json($response);
    }

    public function clientApi($domain,$method)
    {
        $c = curl_init();
        $setup =[CURLOPT_URL=> 'http://'.$domain.'/client.php?method='.$method,
                CURLOPT_SSL_VERIFYHOST=>false,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_COOKIE=>public_path('cookie-'.$domain.'.txt'),
                CURLOPT_USERAGENT=>'@Ryu-Client',
                CURLOPT_CONNECTTIMEOUT=>20, 
                CURLOPT_TIMEOUT=>30,
                ];
           curl_setopt_array($c,$setup);
           $get = curl_exec($c);
           $info = curl_getinfo($c,CURLINFO_RESPONSE_CODE );
           if($info == 200){
           
            return json_decode($get,true);
           }else{
               return ['status' => 'domain dead or not registered','code' => $info,'domain' => $domain];
           }
    }
    public function get_stats(Request $request)
    {
        $domain = $request->domain;
        $response= $this->clientApi($domain,'stats');
        return response()->json($response);

    }
    public function get_logs(Request $request)
    {
        $domain = $request->domain;

        $response = $this->clientApi($domain,'logs');

        return response()->json($response);
    }
    public function get_link(Request $request)
    {
        $domain = $request->domain;
        $response = $this->clientApi($domain,'visit');

        return response()->json($response);
    }
    public function get_reset(Request $request)
    {
        $domain = $request->domain;
        $response = $this->clientApi($domain,'reset');

        return $response;
    }
}
