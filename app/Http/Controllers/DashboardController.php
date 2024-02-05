<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    function summary()
    {
        $user_id=Auth::id();
        $products=Product::where("user_id",$user_id)->count();
        $categories=Category::where("user_id",$user_id)->count();
        $customers=Customer::where("user_id",$user_id)->count();
        $invoices=Invoice::where("user_id",$user_id)->count();
        $total=Invoice::where("user_id",$user_id)->sum("total");
        $vat=Invoice::where("user_id",$user_id)->sum("vat");
        $payable=Invoice::where("user_id",$user_id)->sum("payable");


        return [
            "products"=>$products,
            "categories"=>$categories,
            "customers"=>$customers,
            "invoices"=>$invoices,
            "total"=>round($total,2),
            "vat"=>round($vat,2),
            "payable"=>round($payable,2),
        ];
    }
}
