<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Midtrans\Config;
use App\Http\Controllers\Midtrans\Snap;
use App\Models\Order;
use App\Models\ProjectTemporary;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class TalentPoolController extends Controller
{
    public function getExperienceById(Request $request)
    {
        $user = $request->user(); 


        $url = env('TALENT_POOL_URL') . "/api/experiences/talent/{$user->id}";

     
        $response = Http::get($url);

        return $response->json(); 
    }



    public function fetchSubscriptionSnapToken(Request $request)
    {

        DB::beginTransaction();

        if ($request->type != 'create' && $request->type != 'renew') {
            throw new Exception('Invalid type');
        }

        $subscription = Subscription::find($request->subscription_id);

        MidtransConfig::$serverKey = env('MIDTRANS_MODE') == 'sandbox' ? env('MIDTRANS_SERVER_KEY_DEVELOPMENT') : env('MIDTRANS_SERVER_KEY_PRODUCTION');
        MidtransConfig::$isProduction = env('MIDTRANS_MODE') == 'production';
        MidtransConfig::$is3ds = true;

        $user = Auth::user();

        // Generate order data
        $order = Order::create([
            'order_id' => $this->generateOrderId(),
            'user_id' => $user->id,
            'project_id' => $request->type == 'create' ? null : Hashids::decode($request->project_hashid)[0],
            'subscription_id' => $subscription->id,
            'status' => 'waiting_for_payment',
            'gross_amount' => $subscription->price,
            'type' => $request->type == 'create' ? 'create' : 'renew'
        ]);

        // Check if the action is creating project or renewing project
        if ($request->type == 'create') {
            // This temporary project will become the "temporary" storage for project creation. It also
            // act as order identifier towards project data.
            ProjectTemporary::create([
                'user_id' => Auth::user()->id,
                'order_id' => $order->id,
                'name' => $request->name,
                'activity' => $request->activity,
                'job' => $request->job,
                'address' => $request->address,
                'province_id' => Hashids::decode($request->province_id)[0],
                'fiscal_year' => $request->fiscal_year,
                'profit_margin' => $request->margin_profit,
                'ppn' => $request->ppn,
            ]);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->gross_amount,
            ],
            'item_details' => [
                [
                    'id' => $subscription->hashid,
                    'price' => $subscription->price,
                    'quantity' => 1,
                    'name' => 'Project Plan : ' . $subscription->name,
                ]
            ],
            'customer_details' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'address' => $user->address,
                'phone' => $user->phone,
                'email' => $user->email,
            ],
        ];

        $snapToken = null;

        try {
            $snapToken = MidtransSnap::getSnapToken($params);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        $order->midtrans_snap_token = $snapToken;
        $order->save();

        DB::commit();

        return response()->json([
            'status' => 'success',
            'data' => [
                'snap_token' => $snapToken,
            ]
        ]);
    }

    public function setCanceled(Request $request)
    {
        $order = Order::where('midtrans_snap_token', $request->snapToken)->first();

        if ($order->status == 'waiting_for_payment') {
            $order->status = 'canceled';
            $order->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order canceled',
        ]);
    }

    public function setPending(Request $request)
    {

        $order = Order::where('midtrans_snap_token', $request->snapToken)->first();

        // Make sure the current order is still on waiting_for_payment status. Otherwise, it could be
        // reversed status (e.g from completed to pending)
        if ($order->status == 'waiting_for_payment') {
            $order->status = 'pending';
            $order->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Set pending successfully !',
        ]);
    }

    public function addToken(Request $request)
    {
        $user = Auth::user();
        $user->token_amount += $request->token_amount;
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_token_amount' => $user->token_amount,
            ]
        ]);
    }

    private function setOrder($orderId, $customer, $projectId, $grossAmount)
    {
        $order = Order::create([
            'order_id' => $orderId,
            'user_id' => $customer->id,
            'project_id' => $projectId,
            'gross_amount' => $grossAmount,
        ]);

        return $order;
    }

    private function generateOrderId()
    {
        return strtoupper(Str::random(16));
    }

    private function checkStatusOrder($orderId)
    {

        $midtransApiUrl = env('MIDTRANS_MODE') == 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        $statusRequest = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode((env('MIDTRANS_MODE') ? env('MIDTRANS_SERVER_KEY_DEVELOPMENT') : env('MIDTRANS_SERVER_KEY_PRODUCTION')) . ':'),
        ])->get($midtransApiUrl . '/v2' . '/' . $orderId . '/status');

        return $statusRequest->json();
    }
}
