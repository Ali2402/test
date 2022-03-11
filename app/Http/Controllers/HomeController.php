<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    //
    
    public function index(){
        $data = array(
            'users' => DB::table('users')->get()->count(),
            'products' => DB::table('products')->count(),
            'purchases' => DB::table('purchases')->where('user_id', session()->get('user_id'))->count(),
            'balance' => DB::table('users')->where('id', session()->get('user_id'))->first()->balance,
            'user_activity' => DB::table('logs')->where('user_id', session()->get('user_id'))->where('type', 1)->orderBy('id', 'DESC')->limit(10)->get(),
            'bugs' => DB::table('bugs')->orderBy('id', 'DESC')->limit(10)->get(),
        );
        return view('home', compact('data'));
    }

    public function admin()
    {
        return view('admin.index');
    }

    public function get_logs_admin(Request $request){
        $data = DB::table('logs')
                ->select('users.username', 'users.email', 'type', 'desc', 'amount', 'ip_address', 'user_agent', 'logs.created_at as date')
                ->join('users', 'logs.user_id', '=', 'users.id')
                ->paginate(25);

        return ryuReply('SUCCESS', $data, 200);
    }

    public function documentation()
    {
       
            $data['table_of_content'] = ['Getting started','Download product' , 'Register domain', 'Configuration','Installation','Troubleshooting'];
    
            $data['content'] = ['link'=> ['getting_started','download_product','register_domain','configuration','installation','troubleshooting'], 
                                'desc'=> ['We provide products that you can use for your work, misuse is the responsibility of the user. before starting make sure you have bought the product you need after that you can read the documentation in more detail','Previously you had to buy the product first, the product can be downloaded when your domain is registered on the Ryujin website. We do not provide a special menu to download the product you have purchased.','Make sure you have purchased the product, after that you can going to <a href="/manage"><b>Manage</b></a> and click the <i>register domain</i>, fill in all the required forms. note: you can only choose the product you buy, the maximum domain registration is only 3 domains for each product' , 'After you register domain, automatically redirected to config page. you can setting your website there. next, just save your configuration. Notes : you can change the configuration everytime you needed.','So, here installation proccess.<br><br>You must download the installer file after register you domain, you can click the download icon in <a href="/manage"><b>Manage</b></a> , just upload the installer in your hosting or cpanel or server , after that access it simple ( for example : http://www.yourdomain.com/installer-yourname.php ) and automatically called all files required.','<b>There is common problem might you found.</b><br><br><li><b>Can\'t access installer</b> - Maybe you are wrong access filename or wrong folder when uploading files, make sure you upload to public_html or www or public folder</li><br><li><b>Failed to install after access the installer</b> - Make sure your server or hosting support <i>PHP-CURL</i> with <i>PHP VERSION MINIMUM 7.1 or HIGHER</i> , and make sure you have stable connection.</li><br><li><b>Wrong signature key</b> - if you do the right steps, this will not happen. make sure you have purchased the product you registered and with the correct domain</li><li><b>other problems you can submit to the issues menu</b></li>']
    ];return view('docs' , $data)->render();
        
    }
}
