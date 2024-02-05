<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller{
    //
    function createProduct(Request $request){
        try{

            $request->validate([
                "name"=>"required|string",
                "price"=>"required|numeric",
                "unit"=>"required|string",
                "img_url"=>"required|mimes:png,jpg,jpeg|image"
            ]);

            $user_id=Auth::id();
            //perpare img path and name
            $img=$request->file("img_url");

            $time=time();
            $file_orginal_name=$img->getClientOriginalName();
            $img_name="{$user_id}-{$time}-{$file_orginal_name}";

            //img url

            $img_url="uploads/products/{$img_name}";
            //img upload
            $img_upload=$img->move(public_path("uploads/products"),$img_name);


            //insert product
            Product::create([
                "name"=>$request->input("name"),
                "price"=>$request->input("price"),
                "unit"=>$request->input("unit"),
                "category_id"=>$request->input("category_id"),
                "img_url"=>$img_url,
                "user_id"=>$user_id
            ]);


            return response()->json(["status"=>"success","message"=>"product created successfully"],200);
        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()],200);
        }
    }


    function productList(){
        $user_id=Auth::id();
        $products=Product::where("user_id",$user_id)->with("category")->get();
        return $products;
    }


    function singleProduct(Request $request){
        try{
            $user_id=Auth::id();
            $product_id=$request->input("id");
            $product=Product::where("user_id",$user_id)->where("id",$product_id)->first();
            return $product;
        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }


    function updateProduct(Request $request){
        try{
            $user_id=Auth::id();
            $product_id=$request->input("id");
            if($request->hasFile("img_url")){
                $request->validate([
                    "category_id"=>"required",
                    "name"=>"required|string|max:100",
                    "price"=>"required|numeric",
                    "unit"=>"required",
                    "img_url"=>"required|mimes:png,jpg,jpeg|image"
                ]);

                $product=Product::where("user_id",$user_id)->where("id",$product_id)->first();
                $removed_file=unlink(public_path($product->img_url));

                $img=$request->file("img_url");
                $time=time();
                $file_orginal_name=$img->getClientOriginalName();
                $img_name="{$user_id}-{$time}-{$file_orginal_name}";
                $img_url="uploads/products/{$img_name}";
                $img_upload=$img->move(public_path("uploads/products"),$img_name);
                Product::where("user_id",$user_id)->where("id",$product_id)->update([
                    "category_id"=>$request->input("category_id"),
                    "user_id"=>$user_id,
                    "name"=>$request->input("name"),
                    "price"=>$request->input("price"),
                    "unit"=>$request->input("unit"),
                    "img_url"=>$img_url 
                ]);
                return response()->json(["status"=>"success","message"=>"product updated successfully with image"]);
            }else{
                $request->validate([
                    "category_id"=>"required",
                    "name"=>"required|string|max:100",
                    "price"=>"required|numeric",
                    "unit"=>"required",
                ]);
                Product::where("user_id",$user_id)->where("id",$product_id)->update([
                    "category_id"=>$request->input("category_id"),
                    "user_id"=>$user_id,
                    "name"=>$request->input("name"),
                    "price"=>$request->input("price"),
                    "unit"=>$request->input("unit"),
                    "img_url"=>$request->input("oldImgFromDb")
                ]);
                return response()->json(["status"=>"success","message"=>"product updated successfully without image"]);
            }

        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }

    function deleteProduct(Request $request){
        try{
            $user_id=Auth::id();
            $product_id=$request->input("id");
            $product=Product::where("user_id",$user_id)->where("id",$product_id)->first();
            $removed_file=unlink(public_path($product->img_url));
            Product::where("user_id",$user_id)->where("id",$product_id)->delete();
            return response()->json(["status"=>"success","message"=>"product deleted successfully"]);
        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }
}
