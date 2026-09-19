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
        $img = $request->file('img_url');
        // return $img->getClientOriginalName();

        if ($request->hasFile('img_url')) {
        
            $img_name = time().'_' . md5(uniqid()) . '.'. $img->getClientOriginalExtension();
            $img->move(public_path('uploads/products'), $img_name);


            try{
            $data = Product::insert([
            'img_url' => $img_name,
            'user_id' => $user_id,
            'category_id' => $category_id,
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            // 'img_url' => $img_name,
        ]);

            return response()->json([
            'status' => 'Success',
            'message' => 'Product Created Successful',
            ]);
        
        }
         
        catch(Exception $e){
          return response()->json([
            'status' => 'Fail',
            'message' => 'Something Went Wrong!'
          ],401);
        }

            // return "File received";
        }else {
             try{
            $data = Product::insert([
            'user_id' => $user_id,
            'category_id' => $category_id,
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            // 'img_url' => $img_name,
        ]);

            return response()->json([
            'status' => 'Success',
            'message' => 'Product Created Successful',
            ]);
        
        }
         
        catch(Exception $e){
          return response()->json([
            'status' => 'Fail',
            'message' => 'Something Went Wrong!'
          ],401);
        }
    }
       
    }

    function DeleteProduct(Request $request){
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        $image_name=$request->input('file_path');
        // File::delete($filePath);
        if($image_name && file_exists(public_path('uploads/products/'. $image_name))){
            unlink(public_path('uploads/products/'. $image_name));
        }
        return Product::where('id', $product_id)->where('user_id', $user_id)->delete();
    }

    function ProductById(Request $request) {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        return Product::where('id', $product_id)->where('user_id', $user_id)->first();
    }

    function ProductList(Request $request){
        $user_id=$request->header('id');
        return Product::where('user_id', $user_id)->get();
    }

    function UpdateProduct(Request $request){
        $user_id=$request->header('id');
        $product_id=$request->input('id');

        $product = Product::where('id', $product_id)
        ->where('user_id', $user_id)
        ->first();

        $oldImage = $product->img_url;
    if (!$product) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Product not found!'
        ], 404);
    }
     // Save old image name


        if($request->hasFile('img')){
            // Upload new File
            $img=$request->file('img'); 
            $img_name = "new".time().'_' . md5(uniqid()) . '.'. $img->getClientOriginalExtension();
            $img->move(public_path('uploads/products'), $img_name);


            // Delete Old File
             if ($oldImage && file_exists(public_path('uploads/products/' . $oldImage))) {
            unlink(public_path('uploads/products/' . $oldImage));
            }


            // Update Product
            Product::where('id', $product_id)->where('user_id', $user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'img_url'=>$img_name,
                'category_id'=>$request->input('category_id')
            ]);
             
            return response()->json([
                'status'=>'success',
                'message'=>'Product updated successfully!'
            ]);
            
        }else{
            Product::where('id', $product_id)->where('user_id', $user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'category_id'=>$request->input('category_id'),
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>'Product updated without image successfully!'
            ]);
        }   
    }
}
