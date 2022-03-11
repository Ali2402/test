<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/payment/cp_ipn' , 'PaymentController@cp_ipn');
Route::post('/payment/perfectmoney','PaymentController@perfectmoney');

Route::get('/login', 'Auth\AuthController@login');
Route::get('/register', 'Auth\AuthController@register');
Route::post('/auth/login', 'Auth\AuthController@doLogin');
Route::post('/auth/register', 'Auth\AuthController@doRegister');


Route::group(['middleware' => ['admin']], function(){
    
    Route::group(['prefix' => '/admin'] , function()
    {
        Route::get('/' , 'HomeController@admin');
        Route::get('/user', 'UserController@index');
        Route::post('/user/add', 'UserController@addUserAdmin');
        Route::post('/user/update', 'UserController@updateUserAdmin');
        Route::get('/user/delete/{id}', 'UserController@deleteUserAdmin');
        Route::get('/product' , 'ProductController@admin_index');
        Route::get('/product/get' , 'ProductController@get_products_admin');
        Route::post('/product/add' , 'ProductController@addProductAdmin');
        Route::post('/product/update' , 'ProductController@updateProductAdmin');
        Route::get('/product/delete/{id}' , 'ProductController@deleteProductAdmin');
        Route::get('/issues' , 'IssuesController@admin_index');
        Route::post('/issues/update', 'IssuesController@update_issue');
        Route::get('/issues/delete/{id}', 'IssuesController@delete_issue');
        Route::get('/voucher' , 'VoucherController@admin_index');
        Route::post('/voucher/add' , 'VoucherController@add_voucher');
        Route::get('/voucher/delete/{id}' , 'VoucherController@delete_voucher');
        Route::get('/invitecode/generate' , 'UserController@generate_invitecode');

        Route::group(['prefix' => '/api'] , function() {
            Route::get('/logs' , 'HomeController@get_logs_admin');
            Route::get('/getuser','UserController@api_get_user'); 
            Route::get('/getvoucher' , 'VoucherController@api_get_voucher');
            Route::get('/getinvitecode' , 'UserController@api_get_invitecode');
        });
    });
});

Route::group(['middleware' => ['auth']], function(){
    // ROUTE FOR LOGGED IN USER ONLY
    Route::get('/home', 'HomeController@index');
    Route::get('/docs' , 'HomeController@documentation');
    Route::get('/user' , 'UserController@index');

    Route::group(['prefix' => '/payment'] , function(){
        Route::post('/make' , 'PaymentController@make');
        Route::get('/status/{id}' , 'PaymentController@status');
        
    });
    Route::group(['prefix' => '/balance'], function(){
        Route::get('/' , 'TransactionController@index');
        Route::get('/transaction/history', 'TransactionController@get_history');
        Route::post('/redeem' , 'VoucherController@redeem');
    });

    Route::group(['prefix' => '/products'], function(){
        Route::get('/' , 'ProductController@index');
        Route::get('/get' , 'ProductController@get_products');
        Route::get('/detail/{id}', 'ProductController@detailProduct');
        Route::get('/buy/{id}', 'TransactionController@buyProduct');
    });

    Route::group(['prefix' => '/manage' , 'middleware' => ['manage']], function(){
        Route::get('/' , 'ManageController@index');
        Route::get('/get' , 'ManageController@get_logs');
        Route::get('/detail/{id}', 'ManageController@detailPurchase');

        Route::group(['prefix' => '/domain'] , function(){
            Route::post('/register' , 'ManageController@register_domain');
            Route::get('/config','ManageController@view_config');
            Route::get('/delete' , 'ManageController@delete_domain');
            Route::get('/stats','ManageController@view_stats');
            Route::post('/save' , 'ManageController@save_config');
            Route::get('/download' ,'ManageController@download_installer' );
           
        });
    });

    Route::group(['prefix' => '/issues'], function(){
        Route::get('/' , 'IssuesController@index');
        Route::post('/add' , 'IssuesController@add');
        Route::get('/get' , 'IssuesController@get_issues');
        Route::get('/detail/{id}', 'IssuesController@detailIssue');
    });

    Route::group(['prefix' => '/account'], function(){
        Route::get('/' , 'UserController@account_index');
        Route::post('/update' , 'UserController@updatePassowrd');
    });
});





Route::get('/auth', function(){
    dd("only auth");
})->middleware('auth');


Route::post('logout', function(){
    $fullip = explode(".",$_SERVER['REMOTE_ADDR']);
    $ip_addr =  $fullip[0].".".$fullip[1];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    DB::table('user_devices')->where('user_agent',$useragent)->where('ip','like',"$ip_addr%")->delete();
    session()->flush();
    return redirect('/login');
});



