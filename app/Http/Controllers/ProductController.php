<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    public function index(){
        return view('products.index');
    }

    public function get_products(Request $request){
        $data = DB::table('products')
                ->select('id', 'name', 'version', 'desc', 'price', 'created_at as date')->paginate(10);
        return view('SUCCESS', $data, 200);
    }

    public function admin_index()
    {

        return view('admin.product');
    }

    public function detailProduct(Request $request){
        $product = DB::table('products')->where('id', $request->id)->first();
        if($product){
            $data = array(
                'name' => $product->name,
                'version' => $product->version,
                'desc' => $product->desc,
                'price' => $product->price,
                'date' => $product->created_at,
            );
            return view('products.detail', compact('data'));
        } else {
            return redirect()->back();
        }
    }

    public function addProductAdmin(Request $request){
        DB::table('products')->insert([
            [
                'name' => $request->name,
                'version' => $request->version,
                'desc' => $request->desc,
                'price' => $request->price,
                'filename' => $request->filename
            ]
        ]);
        return ryuReply('SUCCESS', array(
            'msg' => 'Product '.$request->name.' has been added!'
        ), 200);
    }

    public function updateProductAdmin(Request $request){
        DB::table('products')->where('id', $request->id)->update([
            'name' => $request->name,
            'version' => $request->version,
            'desc' => $request->desc,
            'price' => $request->price,
            'filename' => $request->filename
        ]);
        return ryuReply('SUCCESS', array(
            'msg' => 'Product '.$request->name.' successfully updated!'
        ), 200);
    }

    public function deleteProductAdmin(Request $request){
        DB::table('products')->where('id', $request->id)->delete();
        return redirect('/admin/product');
    }

    public function get_products_admin(Request $request){
        $data = DB::table('products')
                ->select('id', 'name', 'version', 'desc', 'price', 'filename', 'created_at as date')
                ->paginate(10);
        return ryuReply('SUCCESS', $data, 200);
    }
}
