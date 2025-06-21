<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use Omnipay\Omnipay;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\TransactionNotifyMail;
use App\Services\CurrencyConverter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    //
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function checkoutPay(Request $request)
    {
        $request->validate([
            'requestId' => 'required|string',
            'email' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        try {
            $encryptedRequestId = $request->requestId;

            $returnUrl = url('success') . '?' . http_build_query([
                'requestId' => $encryptedRequestId,
                'email' => $request->email,
            ]);

            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => $returnUrl,
                'cancelUrl' => url('cancel'),
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function success(Request $request)
    {
        // dd($request->all());
        if ($request->paymentId && $request->PayerID) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));

            $response = $transaction->send();

            // dd('Mohammed');

            if ($response->isSuccessful()) {
                $arr = $response->getData();

                $decrptedRequestId = null;

                if ($request->has('requestId') && $request->requestId != null) {

                    $decrptedRequestId = Crypt::decrypt($request->requestId);
                }

                $userEmail = $request->email;

                // dd($userEmail);

                $pickUpSchedule = DB::table('waste_schedule_pickup')->where('id', $decrptedRequestId)->where('soft_delete', 0)->first();

                if (!$pickUpSchedule) {
                    return redirect()->route('schedule.pickup')->with('error', 'Can not complete this transaction!');
                }

                DB::table('waste_schedule_pickup')->where('id', $decrptedRequestId)->where('soft_delete', 0)
                    ->update([
                        'status' => 'completed',
                        'updated_at' => Carbon::now(),
                    ]);

                DB::table('payments')->insert([
                    'payment_id' => $arr['id'],
                    'payer_id' => $arr['payer']['payer_info']['payer_id'],
                    'payer_email' => $arr['payer']['payer_info']['email'],
                    'amount' => $arr['transactions'][0]['amount']['total'],
                    'currency' => env('PAYPAL_CURRENCY'),
                    'status' => $arr['state'],
                    'pick_up_id' => $decrptedRequestId,
                    'user_email' => $userEmail,
                    'mode' => 'online pay',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                return redirect()->route('schedule.pickup')->with('success', 'Payment has been successfully completed with ID' . ' ' . $arr['id']);
            } else {
                return $response->getMessage();
            }
        } else {
            return redirect()->route('schedule.pickup')->with('error', 'Payment declined!');
        }
    }

    public function cancel()
    {
        return redirect()->route('schedule.pickup')->with('error', 'User declined the transaction!');
    }


    public function rechargeWallet(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        try {

            $returnUrl = url('success_url') . '?' . http_build_query([
                'email' => $request->email,
            ]);

            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => $returnUrl,
                'cancelUrl' => url('cancel_url'),
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function successUrl(Request $request)
    {
        if ($request->paymentId && $request->PayerID) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                $userEmail = $request->email;

                $userId = DB::table('residents')->where('email', $userEmail)->value('id');

                $walletData = DB::table('wallets')->where('user_id', $userId)->where('soft_delete', 0)->first();

                $walletBalance = $walletData->balance;

                $rechargedAmount = CurrencyConverter::convertUsdToTsh($arr['transactions'][0]['amount']['total']);

                DB::table('wallets')->where('user_id', $userId)->update([
                    'balance' => $walletBalance + $rechargedAmount,
                ]);

                DB::table('wallet_recharge_transaction')->insert([
                    'user_id' => $userId,
                    'payment_id' => $arr['id'],
                    'payer_id' => $arr['payer']['payer_info']['payer_id'],
                    'payer_email' => $arr['payer']['payer_info']['email'],
                    'amount' => $rechargedAmount,
                    'currency' => 'TSH',
                    'status' => $arr['state'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $amount = $rechargedAmount;
                $totalAmount = $walletBalance + $rechargedAmount;

                Mail::to($userEmail)->send(new TransactionNotifyMail($amount, $totalAmount));

                return redirect()->back()->with('success', 'Wallet recharged with TSH' . ' ' . number_format($rechargedAmount, 2) . ' ' . 'successfully!');
            } else {

                return $response->getMessage();
            }
        } else {
            return redirect()->route('schedule.pickup')->with('error', 'Payment declined!');
        }
    }

    public function cancelUrl()
    {
        return redirect()->back()->with('error', 'User cancelled the transaction!');
    }

    public function walletPay(Request $request)
    {
        $request->validate([
            'requestId' => 'required|string',
            'email' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        try {
            $decryptedRequestId = Crypt::decrypt($request->requestId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $randomId = random_int(10000000, 99999999);

        // CHECH IF BALANCE IS SUFFICIENT FOR PAYMENT
        $walletBalance = DB::table('wallets')
            ->where('user_id', Auth::user()->user_id)
            ->where('soft_delete', 0)
            ->where('status', 'active')
            ->first();

        // dd($walletBalance);

        if (!$walletBalance) {
            return redirect()->back()->with('error', 'Wallet information unavaillable!');
        }

        if ($walletBalance->balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance in your wallet!');
        }

        $pickUpSchedule = DB::table('waste_schedule_pickup')->where('id', $decryptedRequestId)->where('soft_delete', 0)->first();

        // dd($pickUpSchedule);

        if (!$pickUpSchedule) {
            return redirect()->route('schedule.pickup')->with('error', 'Can not complete this transaction!');
        }

        DB::table('waste_schedule_pickup')->where('id', $decryptedRequestId)
            ->update([
                'status' => 'completed',
                'updated_at' => Carbon::now(),
            ]);

        // dd($randomId);

        $payment = new Payment();
        $payment->pick_up_id = $decryptedRequestId;
        $payment->user_email = $request->email;
        $payment->payment_id = 'PAYID-TZ' . $randomId;
        $payment->payer_id = 'PAYERID-TZ' . $randomId;
        $payment->payer_email = $request->email;
        $payment->amount = $request->amount;
        $payment->currency = 'TSH';
        $payment->status = 'Paid';
        $payment->mode = 'wallet';
        $payment->created_at = Carbon::now();
        $payment->updated_at = Carbon::now();
        $payment->save();

        // UPDATE WALLET BALANCE
        DB::table('wallets')
            ->where('user_id', Auth::user()->user_id)->where('id', $walletBalance->id)
            ->update([
                'balance' => $walletBalance->balance - $request->amount,
            ]);

        return redirect()->route('schedule.pickup')->with('success', 'Payment has been successfully completed!');

        // dd($payment);
        // dd($decryptedRequestId);
    }
}
