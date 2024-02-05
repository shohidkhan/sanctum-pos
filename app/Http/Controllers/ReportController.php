<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //

    function salesReport(Request $request)
    {
        $user_id=Auth::id();
        $FormDate=date("Y-m-d",strtotime($request->FormDate));
        $ToDate=date("Y-m-d",strtotime($request->ToDate));
        
        $total= Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("total");

        $discount= Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("discount");

        $vat= Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("vat");

        $payable= Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("payable");

        $list=Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->with("customer")->get();


        $data=[
            "total"=>$total,
            "discount"=>$discount,
            "vat"=>$vat,
            "payable"=>$payable,
            "list"=>$list,
            "FormDate"=>$FormDate,
            "ToDate"=>$ToDate
        ];
        
        $pdf=Pdf::loadView("report.SalesReport",$data);
        
        return $pdf->download("invoice.pdf");

        // return $data;
    }
}
