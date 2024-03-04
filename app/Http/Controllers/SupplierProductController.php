<?php

namespace App\Http\Controllers;

use App\Models\SuplierProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierProductController extends Controller
{
    //

    function createSupplierProduct(Request $request){
        
         try{
            $request->validate([
                "name"=>"required",
                "stock"=>"required",
                "unit"=>"required",
                "purchase_price"=>"required",
                "img_url"=>"required|mimes:png,jpg,jpeg|image",
                "brand_id"=>"nullable",
                "category_id"=>"nullable",
                "suplier_id"=>"required"
            ]);

            if($request->hasFile("img_url")){
                $image=$request->file("img_url");
                $image_name="supplier-product-".time().".".$image->getClientOriginalExtension();
                $image_path="uploads/supplier-products/{$image_name}";
                $image->move(public_path("uploads/supplier-products"),$image_name);
            }

            SuplierProduct::updateOrCreate(
                ["user_id"=>Auth::id(),"category_id"=>$request->input("category_id"),"brand_id"=>$request->input("brand_id"),"suplier_id"=>$request->input("suplier_id"),"name"=>$request->input("name")],
                [
                    "name"=>$request->input("name"),
                    "stock"=>$request->input("stock"),
                    "unit"=>$request->input("unit"),
                    "purchase_price"=>$request->input("purchase_price"),
                    "img_url"=>$image_path,
                    // "user_id"=>Auth::id(),
                    // "category_id"=>$request->input("category_id"),
                    // "brand_id"=>$request->input("brand_id"),
                    // "suplier_id"=>$request->input("suplier_id")
                ]
            );

            return response()->json(["status"=>"success","message"=>"product created successfully"]);
         }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
         }
    }

    function supplierProductList(){
        try{
            $suplierProduct=SuplierProduct::with("user","suplier","category","brand")->where("user_id",Auth::id())->get();
            return $suplierProduct;
        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }

    function singleSupplierProduct(Request $request){
        try{
            $suplierProduct=SuplierProduct::with("user","suplier","category","brand")->where("user_id",Auth::id())->where("id",$request->input("id"))->first();
            return $suplierProduct;
        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }

    function updateSupplierProduct(Request $request){
        try{
            
            
            if($request->hasFile("img_url")){
                $request->validate([
                    "name"=>"required",
                    "stock"=>"required",
                    "unit"=>"required",
                    "purchase_price"=>"required",
                    "img_url"=>"required|mimes:png,jpg,jpeg|image",
                    "brand_id"=>"required",
                    "category_id"=>"required",
                    "suplier_id"=>"required"
                ]);
                $supplierProduct=SuplierProduct::where("user_id",Auth::id())->where("id",$request->input("id"))->first();
                //remove old image
                unlink(public_path($supplierProduct->img_url));
                $new_img=$request->file("img_url");
                $new_img_name="supplier-product-".time().".".$new_img->getClientOriginalExtension();
                $new_img_path="uploads/supplier-products/{$new_img_name}";
                $new_img->move(public_path("uploads/supplier-products"),$new_img_name);

                SuplierProduct::where("user_id",Auth::id())->where("id",$supplierProduct->id)->update([
                    "name"=>$request->input("name"),
                    "stock"=>$request->input("stock"),
                    "unit"=>$request->input("unit"),
                    "purchase_price"=>$request->input("purchase_price"),
                    "img_url"=>$new_img_path,
                    "category_id"=>$request->input("category_id"),
                    "brand_id"=>$request->input("brand_id"),
                    "suplier_id"=>$request->input("suplier_id"),
                ]);

                return response()->json(["status"=>"success","message"=>"product updated successfully"]);
            }else{

                $request->validate([
                    "name"=>"required",
                    "stock"=>"required",
                    "unit"=>"required",
                    "purchase_price"=>"required",
                    "brand_id"=>"required",
                    "category_id"=>"required",
                    "suplier_id"=>"required"
                ]);
                $supplierProduct=SuplierProduct::where("user_id",Auth::id())->where("id",$request->input("id"))->first();

                SuplierProduct::where("user_id",Auth::id())->where("id",$supplierProduct->id)->update([
                    "name"=>$request->input("name"),
                    "stock"=>$request->input("stock"),
                    "unit"=>$request->input("unit"),
                    "purchase_price"=>$request->input("purchase_price"),
                    "img_url"=>$supplierProduct->img_url,
                    "category_id"=>$request->input("category_id"),
                    "brand_id"=>$request->input("brand_id"),
                    "suplier_id"=>$request->input("suplier_id"),
                ]);
                return response()->json(["status"=>"success","message"=>"product updated successfully"]);
            }
        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }


    function deleteSupplierProduct(Request $request){
        try{
            $supplierProduct=SuplierProduct::where("user_id",Auth::id())->where("id",$request->input("id"))->first();

            unlink(public_path($supplierProduct->img_url));
            
            SuplierProduct::where("user_id",Auth::id())->where("id",$supplierProduct->id)->delete();
            return response()->json(["status"=>"success","message"=>"product deleted successfully"]);
        }catch(Exception $e){
             return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }
}
