<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\CurrencyConverter;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    //
    public function login()
    {
        return view('templates.index');
    }

    public function register()
    {
        return view('templates.register');
    }

    public function dashboard()
    {
        $myPickUpRequests = DB::table('waste_schedule_pickup')
            ->select([
                'pickup_date',
                'preferred_time',
                'frequency',
                'location',
                'status',
                'id'
            ])
            ->where('soft_delete', 0)
            ->where('user_id', Auth::user()->user_id)
            ->orderByDesc('id')
            ->get();

        $monthlyRequests = DB::table('waste_schedule_pickup')
            ->select([
                DB::raw('MONTH(pickup_date) as month'),
                DB::raw('COUNT(*) as total_requests')
            ])
            ->where('soft_delete', 0)
            ->where('user_id', Auth::user()->user_id)
            ->groupBy(DB::raw('MONTH(pickup_date)'))
            ->orderBy(DB::raw('MONTH(pickup_date)'))
            ->get();

        $months = [];
        $totals = [];

        foreach ($monthlyRequests as $item) {
            $months[] = \Carbon\Carbon::create()->month($item->month)->format('F');
            $totals[] = $item->total_requests;
        }

        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();
        // dd($balance);
        return view('templates.dashboard', compact('balance', 'myPickUpRequests', 'months','totals'));
    }

    public function myWallet()
    {
        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        $walletTransactions = DB::table('payments')
            ->select('*')
            ->where('mode', 'wallet')
            ->where('user_email', Auth::user()->username)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $walletRechargeTransactions = DB::table('wallet_recharge_transaction')
            ->select('*')
            ->where('user_id', Auth::user()->user_id)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        // dd($walletRechargeTransactions);

        return view('templates.wallet', compact('walletTransactions', 'balance', 'walletRechargeTransactions'));
    }

    public function schedulePickUp()
    {
        $myPickUpRequests = DB::table('waste_schedule_pickup')
            ->select([
                'pickup_date',
                'preferred_time',
                'frequency',
                'location',
                'status',
                'id'
            ])
            ->where('soft_delete', 0)
            ->where('user_id', Auth::user()->user_id)
            ->orderByDesc('id')
            ->get();
        // dd($myPickUpRequests);

        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        return view('templates.schedule-pickups', compact('myPickUpRequests', 'balance'));
    }

    public function storeSchedules(Request $request)
    {
        $request->validate([
            'frequency' => 'nullable|string|max:30',
            'pickup_date' => 'required|date',
            'preferred_time' => 'required|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'mylocation' => 'nullable|string|max:255',
        ]);

        $userId = Auth::user()->id;

        // $duplicatedRequest = DB::table('waste_schedule_pickup')->where('')

        if ($request->pickup_date <= Carbon::today()) {
            return redirect()->back()->with('error', 'Pick up date can not be behind' . ' ' . Carbon::now()->format('M d, Y') . '  ' . 'Please select date above' . ' ' . Carbon::now()->format('M d, Y'));
        }

        if ($request->has('location') && $request->location != null) {
            DB::table('waste_schedule_pickup')->insert([
                'user_id' => $userId,
                'frequency' => $request->frequency,
                'pickup_date' => $request->pickup_date,
                'preferred_time' => $request->preferred_time,
                'location' => $request->location,
            ]);
        }

        if ($request->has('mylocation') && $request->mylocation != null) {
            DB::table('waste_schedule_pickup')->insert([
                'user_id' => $userId,
                'frequency' => $request->frequency,
                'pickup_date' => $request->pickup_date,
                'preferred_time' => $request->preferred_time,
                'location' => $request->mylocation,
            ]);
        }

        return redirect()->back()->with('success', 'Pick-Up Request sent successfully!');

        // dd('Nancy Mushi');
    }

    public function pickUpRequests()
    {
        $incompeleteRequests = DB::table('waste_schedule_pickup AS IPR')
            ->join('residents AS R', 'IPR.user_id', '=', 'R.id')
            ->select([
                'R.name AS name',
                'IPR.pickup_date AS pickupDate',
                'IPR.preferred_time AS pickupTime',
                'IPR.frequency AS frequency',
                'IPR.location AS pickupLocation',
                'IPR.status AS status',
                'IPR.id AS id',
            ])
            ->where('IPR.status', 'pending')
            ->where('IPR.soft_delete', 0)
            ->orderByDesc('IPR.id')
            ->get();

        $acceptedRequests = DB::table('waste_schedule_pickup AS IPR')
            ->join('residents AS R', 'IPR.user_id', '=', 'R.id')
            ->select([
                'R.name AS name',
                'IPR.pickup_date AS pickupDate',
                'IPR.preferred_time AS pickupTime',
                'IPR.frequency AS frequency',
                'IPR.location AS pickupLocation',
                'IPR.status AS status',
                'IPR.id AS id',
            ])
            ->where('IPR.status', 'accepted')
            ->where('IPR.soft_delete', 0)
            ->orderByDesc('IPR.id')
            ->get();

        $completedRequests = DB::table('waste_schedule_pickup AS IPR')
            ->join('residents AS R', 'IPR.user_id', '=', 'R.id')
            ->select([
                'R.name AS name',
                'IPR.pickup_date AS pickupDate',
                'IPR.preferred_time AS pickupTime',
                'IPR.frequency AS frequency',
                'IPR.location AS pickupLocation',
                'IPR.status AS status',
                'IPR.id AS id',
            ])
            ->where('IPR.status', 'completed')
            ->where('IPR.soft_delete', 0)
            ->orderByDesc('IPR.id')
            ->get();

        // dd($acceptedRequests);
        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        return view('templates.pickup-requests', compact('incompeleteRequests', 'acceptedRequests', 'completedRequests', 'balance'));
    }

    public function viewRequest($encryptedId)
    {
        try {
            $pickupId = Crypt::decrypt($encryptedId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $thisRequests = DB::table('waste_schedule_pickup AS IPR')
            ->join('residents AS R', 'IPR.user_id', '=', 'R.id')
            ->select([
                'R.name AS name',
                'IPR.pickup_date AS pickupDate',
                'IPR.preferred_time AS pickupTime',
                'IPR.frequency AS frequency',
                'IPR.location AS pickupLocation',
                'IPR.status AS status',
                'IPR.id AS id',
            ])
            // ->where('IPR.status', 'pending')
            ->where('IPR.soft_delete', 0)
            ->where('IPR.id', $pickupId)
            ->orderByDesc('IPR.id')
            ->first();

        $paymentDetails = DB::table('payments')
            ->select('*')
            ->where('pick_up_id', $pickupId)
            ->where('soft_delete', 0)
            ->get();
        // dd($thisRequests);

        if (!$thisRequests) {
            return redirect()->back()->with('error', 'Pick-up request not found!');
        }

        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        return view('templates.view-request', compact('thisRequests', 'paymentDetails', 'balance'));
        // dd($pickupId);
    }

    public function acceptRequest(Request $request)
    {
        $request->validate([
            'pickupId' => 'required|string',
        ]);

        $decryptedId = Crypt::decrypt($request->pickupId);

        $existingPickUp = DB::table('waste_schedule_pickup')
            ->where('id', $decryptedId)
            ->exists();

        if ($existingPickUp == false) {
            return redirect()->back()->with('error', 'Can not update this pick-up request due to missing data!');
        }

        DB::table('waste_schedule_pickup')
            ->where('id', $decryptedId)->update([
                'status' => 'accepted',
                'updated_at' => Carbon::now(),
            ]);

        return redirect()->route('pickup.requests')->with('success', 'Pick-up request accepted successfully!');

        // dd($decryptedId);
    }

    public function requestDetails($encryptedId)
    {
        try {
            $myPickUpId = Crypt::decrypt($encryptedId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $myPickUpRequest = DB::table('waste_schedule_pickup')
            ->select([
                'pickup_date',
                'preferred_time',
                'frequency',
                'location',
                'status',
                'id'
            ])
            ->where('soft_delete', 0)
            ->where('id', $myPickUpId)
            ->orderByDesc('id')
            ->first();

        $paymentDetails = DB::table('payments')
            ->select('*')
            ->where('pick_up_id', $myPickUpId)
            ->where('soft_delete', 0)
            ->get();

        // dd($paymentDetails);

        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        if (!$myPickUpRequest) {
            return redirect()->back()->with('error', 'Pick-Up request not found!');
        }

        return view('templates.request-details', compact('myPickUpRequest', 'myPickUpId', 'paymentDetails', 'balance'));
    }

    public function recycleExchange()
    {
        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        $recyclebleExchangeCategory = DB::table('recyclable_material_category')
            ->select('name', 'id')
            ->where('soft_delete', 0)
            ->get();

        $recyclables = DB::table('recyclables AS RS')
            ->join('recyclable_material_category AS RSC', 'RS.material_type', '=', 'RSC.id')
            ->join('residents AS R', 'RS.user_id', '=', 'R.id')
            ->select([
                'RS.title AS materialName',
                'RSC.name AS materialCategory',
                'RS.weight AS weight',
                'R.name AS listedBy',
                'RS.price AS price',
                'RS.image AS image',
            ])
            ->where('RS.soft_delete', 0)
            ->orderByDesc('RS.id')
            ->get();

        // dd($balance);

        return view('templates.recycle-exchange', compact('balance', 'recyclables', 'recyclebleExchangeCategory'));
    }

    public function recyclablePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'material_type' => 'required|integer',
            'weight' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $userId = Auth::user()->user_id;

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // dd($userId);

        DB::table('recyclables')->insert([
            'user_id' => $userId,
            'title' => $request->title,
            'material_type' => $request->material_type,
            'weight' => $request->weight,
            'price' => $request->price,
            'image' => $imagePath,
            'description' => $request->description ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Item added to the recyclable list successfully!');

        // dd($request->all());
    }
}
