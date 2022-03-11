<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function make(Request $request){
        $method = $request->method;
        $amount = $request->amount;
        $id_users = session()->get('user_id');
        $token = strtoupper(sha1($method.$id_users.$amount.time()));

        $save = DB::table('payment')->insertGetId(['method' => $method , 'amount' => $amount , 'user_id' => $id_users , 'token' => $token,'status' => 'unpaid']);
        
        return redirect('/payment/status/'.$save);
    }
    public function status(Request $request)
    {
        $data['detail'] = DB::table('payment')->where('id',$request->id)->first();
        return view('payment.status',$data);
    }
    public function cp_ipn()
    {
        // Fill these in with the information from your CoinPayments.net account.
		$cp_merchant_id = env('cp_merchant_id');
		$cp_ipn_secret =  env('cp_ipn_secret');
		$cp_debug_email = env('cp_debug_email');
		
	
		//These would normally be loaded from your database, the most common way is to pass the Order ID through the 'custom' POST field.
		$order_currency = 'USD';
		//$order_total = 10.00;
	
		function errorAndDie($error_msg) {
			global $cp_debug_email;
			if (!empty($cp_debug_email)) {
				$report = 'Error: '.$error_msg."\n\n";
				$report .= "POST Data\n\n";
				foreach ($_POST as $k => $v) {
					$report .= "|$k| = |$v|\n";
				}
				file_put_contents(public_path('/error_cp.log'),$report);
				mail($cp_debug_email, 'CoinPayments IPN Error', $report);
			}
			die('IPN Error: '.$error_msg);
		}
	
		if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
			errorAndDie('IPN Mode is not HMAC');
		}
	
		if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
			errorAndDie('No HMAC signature sent.');
		}
	
		$request = file_get_contents('php://input');
		if ($request === FALSE || empty($request)) {
			errorAndDie('Error reading POST data');
		}
	
		if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
			errorAndDie('No or incorrect Merchant ID passed');
		}
	
		$hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
		if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
		//if ($hmac != $_SERVER['HTTP_HMAC']) { <-- Use this if you are running a version of PHP below 5.6.0 without the hash_equals function
			errorAndDie('HMAC signature does not match');
		}
	
		// HMAC Signature verified at this point, load some variables.
	
		$ipn_type = $_POST['ipn_type'];
		$txn_id = $_POST['txn_id'];
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$amount1 = floatval($_POST['amount1']);
		$amount2 = floatval($_POST['amount2']);
		$currency1 = $_POST['currency1'];
		$currency2 = $_POST['currency2'];
		$status = intval($_POST['status']);
		$status_text = $_POST['status_text'];

        $pay = DB::table('payment')->where('id',$_POST['PAYMENT_ID'])->get();
        $user = DB::table('users')->where('id',$pay['user_id'])->get();

        $order_total = $pay['amount'];

		if ($ipn_type != 'button') { // Advanced Button payment
			die("IPN OK: Not a button payment");
		}
	
		//depending on the API of your system, you may want to check and see if the transaction ID $txn_id has already been handled before at this point
	
		// Check the original currency to make sure the buyer didn't change it.
		if ($currency1 != $order_currency) {
			errorAndDie('Original currency mismatch!');
		}
	
		// Check amount against order total
		if ($amount1 < $order_total) {
			errorAndDie('Amount is less than order total!');
		}
	 
		if ($status >= 100 || $status == 2) {
			
				

            DB::table('users')->where('id',$pay['user_id'])->update(['balance' => $user['balance']+$pay['amount']]);
            // insert to history
           

            DB::table('logs')->insert(
                            [['type' => 2 , 
                            'desc' => 'TopUp Balance : \$'.$pay['amount'].' via CoinPayments ',
                            'user_id' => $user['id'] ,
                            'amount' => $pay['amount'],
                            'ip_address' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']
                            ]]);
            // update payment to paid
            DB::table('payment')->where('id',$pay['id'])->update(['status' => 'paid']);

        			file_put_contents(public_path('/success_cp.log'), json_encode($_POST));

		} else if ($status < 0) {
			//payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
			file_put_contents(public_path('/error_cp.log'), json_encode($_POST));
		} else {
			file_put_contents(public_path('/pending.log'), json_encode($_POST));
		}
		die('IPN OK');
    }
    public function perfectmoney()
    {

        if(isset($_POST))
		{
			$string=
      $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
      $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
      $_POST['PAYMENT_BATCH_NUM'].':'.
      $_POST['PAYER_ACCOUNT'].':'.env('ALTERNATE_PHRASE_HASH').':'.
      $_POST['TIMESTAMPGMT'];

        $hash=strtoupper(md5($string));


        	if($_POST['V2_HASH'] == $hash)
        	{
        	
                $pay = DB::table('payment')->where('id',$_POST['PAYMENT_ID'])->get();
        		$user = DB::table('users')->where('id',$pay['user_id'])->get();

        		if($pay['amount'] == $_POST['PAYMENT_AMOUNT'] && 
        			$pay['token'] == $_POST['token'] )
        		{
        			
        			// update balance
        			//$usermod->update_balance($pay['amount'],"plus",$pay['id_users']);
                    
                    DB::table('users')->where('id',$pay['user_id'])->update(['balance' => $user['balance']+$pay['amount']]);
        			// insert to history
        			//$usermod->logs('transaction','TopUp Balance : \$'.$pay['amount'].' via PerfectMoney ',$pay['id_users']);

                    DB::table('logs')->insert(
                                    [['type' => 2 , 
                                    'desc' => 'TopUp Balance : \$'.$pay['amount'].' via PerfectMoney ',
                                    'user_id' => $user['id'] ,
                                    'amount' => $pay['amount'],
                                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                                    ]]);
        			// update payment to paid
        			DB::table('payment')->where('id',$pay['id'])->update(['status' => 'paid']);
        			file_put_contents(public_path('success.log'), json_encode($_POST));

        			return redirect('/payment/status/'.$pay['id']);

        		}

        	
        	}
        	file_put_contents(public_path('fail.log'), json_encode($_POST)." \n\n  HASH :".$hash);
            DB::table('payment')->where('id',$pay['id'])->update(['status' => 'canceled']);
            return redirect('/payment/status/'.$pay['id']);

		}else{
            DB::table('payment')->where('id',$pay['id'])->update(['status' => 'canceled']);
            return redirect('/payment/status/'.$pay['id']);
			
		}
    }
}
