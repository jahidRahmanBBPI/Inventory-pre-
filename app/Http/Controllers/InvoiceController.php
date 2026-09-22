<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function InvoicePage(){
        return view('pages.dashboard.invoice-page');
    }

    function SalePage(){
        return view('pages.dashboard.sale-page');
    }

    function ReportPage(){
        return view('pages.dashboard.report-page');
    }

    function invoiceCreate(Request $request){
        DB::beginTransaction();

        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');

            $customer_id = $request->input('customer_id');

            $invoice = Invoice::create([
                'total'=>$total,
                'discount'=>$discount,
                'vat'=>$vat,
                'payable'=>$payable,
                'customer_id'=>$customer_id,
                'user_id'=>$user_id
            ]);

            $invoiceID = $invoice->id;

            $products = $request->input('products');

            foreach ($products as $EachProduct){
                InvoiceProduct::create([
                    'invoice_id'=>$invoiceID,
                    'user_id'=>$user_id,
                    'product_id'=>$EachProduct['product_id'],
                    'qty'=>$EachProduct['qty'],
                    'sale_price'=>$EachProduct['sale_price'],
                ]);
            }

            DB::commit();

            return 1;
        } catch(Exception $e){
            DB::rollBack();
            return 0;
        }
    }

    function invoiceSelect(Request $request){
        $user_id = $request->header('id');
        return Invoice::where('user_id', $user_id)->with('customer')->get();
    }

    function InvoiceDetails(Request $request){
        $user_id = $request->header('id');
        $customerDetails = customer::where('user_id', $user_id)->where('id', $request->input('cus_id'))->first();
        $InvoiceTotal = Invoice::where('user_id', $user_id)->where('id', $request->input('inv_id'))->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->input('inv_id'))->where('user_id', $user_id)->get();

        return array(
            'customer'=>$customerDetails, 
            'invoice'=>$InvoiceTotal,
            'product'=>$invoiceProduct,
        );
    }

    function invoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            $user_id= $request->header('id');
            $validity = InvoiceProduct::where('invoice_id', $request->input('inv_id'))
                ->where('user_id',$user_id)
                ->delete();
            if(!$validity){
                return "Invoice Not Found!";
            }
            Invoice::where('id',$request->input('inv_id'))->delete();
            DB::commit();
            return 1;
        } catch(Exception $e){
            DB::rollBack();
            return 0;
        }
    }
}
