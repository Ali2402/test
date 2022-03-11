<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\SUpport\Str;

class ManageController extends Controller
{

    public function index(){

        $data['products'] = DB::table('purchases')
        ->select('purchases.id as id_purchase','products.name as product_name','products.version as version')->where('purchases.user_id' , session()->get('user_id'))
        ->join('products' , 'purchases.product_id', '=','products.id')->get();

        $data['limit_domain'] = DB::table('users')->where('id', session()->get('user_id'))->first()->domain_limit;
        
    
        return view('manage.index',$data);
    }
    
    public function get_logs(Request $request){
        // $data = DB::table('purchases')
        //         ->select('purchases.id', 'products.name', 'purchases.created_at as date', 'domains.domain')
        //         ->where('user_id', session()->get('user_id'))
        //         ->join('products', 'purchases.product_id', '=', 'products.id')
        //         ->join('domains', 'purchases.id', '=', 'domains.purchase_id')
        //         ->paginate(10);

        $data = DB::table('purchases')
                ->select('purchases.id' , 'products.name' , 'purchases.created_at as date' , 'domains.domain' , 'domains.result_email', 'domains.config','domains.id as id_domain','purchases.signature')
                ->where('purchases.user_id' , session()->get('user_id'))
                ->join('products', 'purchases.product_id' , '=','products.id')
                ->join('domains','purchases.id','=','domains.purchase_id')
                ->paginate(10);
        return ryuReply('SUCCESS', $data, 200);
    }

    public function detailPurchase(Request $request){
        $purchases = DB::table('purchases')
                    ->select('products.name', 'purchases.created_at as date', 'version', 'purchases.price', 'purchases.desc', 'products.version')
                    ->where('purchases.id', $request->id)
                    ->where('user_id', session()->get('user_id'))
                    ->join('products', 'purchases.product_id', '=', 'products.id')
                    ->get();
        if($purchases){
            $data = array(
                'name' => $purchases->name,
                'version' => $purchases->version,
                'desc' => $purchases->desc,
                'price' => $purchases->price,
                'date' => $purchases->date,
            );
            return view('manage.detail', compact('data'));
        } else {
            return redirect()->back();
        }
    }
    public function register_domain(Request $request)
    {
        $domain = str_replace(['http','https','://'] , '' , $request->domain);
        $purchase_id = $request->purchase_id;
        $result_email = $request->result_email;

        $get_limit =  DB::table('users')->where('id', session()->get('user_id'))->first()->domain_limit;
        $limit_product=DB::table('domains')->where('purchase_id',$purchase_id)->count();

        if($limit_product < $get_limit){


        
        $save = DB::table('domains')->insertGetId(
            
                [
                    'purchase_id' => $purchase_id,
                    'result_email' => $result_email,
                    'domain' => $domain,
                    'config' => '{}'
                ]
            
                );

                if($save)
                {
                    return redirect('/manage/domain/config?d='.$save);
                }else{
                    return redirect('/manage');
                }
              

        }else{

            return redirect('/manage')->with('msg','OOps ! cant register, maximum domain ');
        }

    }

    public function view_config(Request $request)
    {
        $id = $request->d;
        $data['config'] = json_decode(DB::table('domains')->where('id',$id)->first()->config,true);
        return view('manage.config',$data);
    }
    public function save_config(Request $request)
    {
        $json = json_encode($request->except(['_token','d']),JSON_PRETTY_PRINT);
        $id = $request->d;
        $check_domain = DB::table('domains')->where('id',$id)->first();
        if($check_domain)
        {
            DB::table('domains')->where('id',$id)->update(['config' => $json]);
            return redirect('/manage')->with('response',['color' => 'green' , 'text' => 'Successfully registered domain !']);
        }else{
            return redirect('/manage')->with('response' ,['color' => 'red' ,'text'=> 'OOps ! domain not found or not registered']);
        }
    }
    public function delete_domain(Request $request)
    {
        $id = $request->d;

        DB::table('domains')->where('id',$id)->delete();
        return redirect('/manage');
    }

   
    public function view_stats(Request $request)
    {  $purchases = DB::table('purchases')
        ->select('purchases.id' , 'products.name' , 'purchases.created_at as date' , 'domains.domain' , 'domains.result_email', 'domains.config','domains.id as id_domain','purchases.signature')
        ->where('purchases.user_id' , session()->get('user_id'))
        ->join('products', 'purchases.product_id' , '=','products.id')
        ->join('domains','purchases.id','=','domains.purchase_id')->first();

        if($purchases){
            $data = array(
                'domain' => $purchases->domain,
                'product' => $purchases->name,
                'date' => $purchases->date
            );
            
            return view('manage.stats', compact('data'));
        } else {
            return redirect('manage');
        }
    }
  
    public function download_installer(Request $request)
    {
        $sig = $request->sig;
        $check_sig = DB::table('purchases')->where('signature' , $sig)->first();
        if($check_sig)
        {
            $prod =  DB::table('products')->where('id' , $check_sig->product_id)->first();
            $get_product = $prod->filename;
            $type   = strtolower(str_replace(' ','-',$prod->name));

            $username =DB::table('users')->where('id' , session()->get('user_id'))->first()->username;
            $slugname= strtolower(Str::snake($username));
            $date = date('D,d-m-Y H:i:s').' - ( '.date_default_timezone_get().' ) ';

            $file = str_replace(["{signature_key}","{username}" , "{date}" , "{product}"] , [$sig,$username,$date ,$type], file_get_contents(storage_path('product/'.$get_product)));
            $pathToFile = storage_path('product/installer-'.$slugname.'.php');
             if(@file_put_contents($pathToFile,$file)){
              return response()->download($pathToFile)->deleteFileAfterSend();
             }else{
                 return redirect('/manage')->with('response' , ['color' => 'red' , 'text' => 'failed to download,please try again.']);
             }
        }else{

            return redirect('/manage')->with('response' , ['color' => 'red' , 'text' => 'Signature key not valid !']);
        }
    }
}
