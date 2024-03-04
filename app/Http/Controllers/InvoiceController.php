<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //


    function createInvoice(Request $request)
    {   DB::beginTransaction();
        try{
            $request->validate([
                "total"=>"required|string|max:50",
                "discount"=>"required|string|max:50",
                "vat"=>"required|string|max:50",
                "payable"=>"required|string|max:50",
                "customer_id"=>"required|string|max:50",
            ]);

            $user_id=Auth::id();
            $total=$request->input("total");
            $discount=$request->input("discount");
            $vat=$request->input("vat");
            $payable=$request->input("payable");
            $customer_id=$request->input("customer_id");
            //make unique inovice number for invoice tracking
            $invoice_no = "INV-".time();
            $invoice=Invoice::create([
                "invoice_no"=>$invoice_no,
                "user_id"=>$user_id,
                "total"=>$total,
                "discount"=>$discount,
                "vat"=>$vat,
                "payable"=>$payable,
                "customer_id"=>$customer_id
            ]);

            $invoice_id=$invoice->id;

            $products=$request->input("products");

            foreach($products as $EachProduct){
                InvoiceProduct::create([
                    "invoice_id"=>$invoice_id,
                    "product_id"=>$EachProduct["product_id"],
                    "user_id"=>$user_id,
                    "qty"=>$EachProduct["qty"],
                    "sale_price"=>$EachProduct["sale_price"],         
                ],);
            }

            DB::commit();
            
            return response()->json(["status"=>"success","message"=>"Invoice created successfully"],200);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json(["error"=>false,"message"=>$e->getMessage()]);
        }
    }

    function invoiceList()  {
        try{
            $user_id=Auth::id();
            return Invoice::where("user_id",$user_id)->with("customer")->get();
        }catch(Exception $e){
            return response()->json(["error"=>false,"message"=>$e->getMessage()]);
        }
        
    }

    function invoiceDetails(Request $request){
        try{
            $user_id=Auth::id();
            $customer=Customer::where("user_id",$user_id)->where("id",$request->input("customer_id"))->first();
            $invoice=Invoice::where("user_id",$user_id)->where("id",$request->input("invoice_id"))->first();
            $product=InvoiceProduct::where("user_id",$user_id)->where("invoice_id",$request->input("invoice_id"))->with("product")->get();

            return array(
                "customer"=>$customer,
                "invoice"=>$invoice,
                "product"=>$product,
            );
        }
        catch(Exception $e){
            return response()->json(["error"=>false,"message"=>$e->getMessage()]);
        }
    }
    function deleteInvoice(Request $request){
        DB::beginTransaction();
        try{

            $user_id=Auth::id();
            InvoiceProduct::where("invoice_id",$request->input("invoice_id"))->where("user_id",$user_id)->delete();
            Invoice::where("id",$request->input("invoice_id"))->delete();
            DB::commit();
            return response()->json(["status"=>"success","message"=>"Invoice deleted successfully"],200);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json(["error"=>false,"message"=>$e->getMessage()]);
        }
    }
}
