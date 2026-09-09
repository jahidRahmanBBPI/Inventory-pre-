<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    function product_page(){
        return view('pages.dashboard.product-page');
    }

    function create_product(Request $request){
        $user_id = $request->header('id');
        $category_id = $request->input('category_id');
        $name = $request->input('name');
        $price = $request->input('price');
        $unit = $request->input('unit');

        // Prepare File Name & Path

        $img = $request->file('img');
        $t = time();
        $file_name=$img->getClientOriginalName();
        $img_name="{$user_id}--{$t}--{$file_name}";
        $img_ul="uploads/{$img_name}";

        // Upload File
        $img->move(public_path('uploads/products'), $img_name);


        try{
            $data = Product::insert([
            'user_id' => $user_id,
            'category_id' => $category_id,
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'img_url' => $img_name,
        ]);

        if($data){
            return response()->json([
            'status' => 'Success',
            'message' => 'Product Created Successful',
            ]);
        }else{
            return response()->json([
            'status' => 'Fail',
            'message' => 'Something Went Wrong!',
            ]);
        }
        }catch(Exception $e){
          return response()->json([
            'status' => 'Fail',
            'message' => 'Something Went Wrong!'
          ],401);
        }
    }

    function DeleteProduct(Request $request){
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        $filePath=$request->input('file_path');
        File::delete($filePath);
        return Product::where('id', $product_id)->where('user_id', $user_id)->delete();
    }

    function ProductById(Request $request) {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        return Product::where('id', $product_id)->where('user_id', $user_id)->first();
    }
}
