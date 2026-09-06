<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function CustomerPage(){
        return view('pages.dashboard.customer-page');
    }

    function customerCreate(Request $request){
        $user_id = $request->header('id');
        try {
            customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $user_id
            ]);
            return response()->json(['status' => 'success', 'message' => 'Customer created successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'message' => 'Failed to create customer: ' . $e->getMessage()], 200);
        }
        // $data = customer::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'mobile' => $request->mobile,
        //     'user_id' => $user_id
        // ]);
        // if($data){
        //     return response()->json(['status' => 'success', 'message' => 'Customer created successfully'], 201);
        // } else {
        //     return response()->json(['status' => 'error', 'message' => 'Failed to create customer'], 500);
        // }
    }

    function CustomerList(Request $request){
        $user_id = $request->header('id');
        return customer::where('user_id', $user_id)->get();
    }

    function CustomerDelete(Request $request){
        $customer_id= $request->input('id');
        $user_id = $request->header('id');
        return customer::where('id', $customer_id)->where('user_id', $user_id)->delete();
    }

    function CustomerById(Request $request){
        $customer_id = $request->input('id');
        $user_id = $request->header('id');
        // return "id:".$user_id . " customer_id:".$customer_id;
        return customer::where('id', $customer_id)->where('user_id',$user_id)->first();
    }

    function CustomerUpdate(Request $request){
        $customer_id = $request->input('id');
        $user_id= $request->header('id');
        customer::where('id', $customer_id)->where('user_id', $user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]);
        return response()->json(['status' => 'success', 'message' => 'Customer updated successfully'], 200);
    }
}

