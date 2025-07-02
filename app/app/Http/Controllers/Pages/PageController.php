<?php

namespace App\Http\Controllers\Pages;

use Carbon\Carbon;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CurrencyConverter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\BlockchainSimulator;
use Illuminate\Support\Facades\Crypt;

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

    public function schedulePickUpDay()
    {
        $staffs = DB::table('staff')
            ->select([
                'id',
                'names',
            ])
            ->where('soft_delete', 0)
            ->whereNot('role', 1)
            ->orderBy('names', 'ASC')
            ->get();

        $pickUpsData = DB::table('pickup_management AS PU')
            ->join('staff AS S', 'PU.added_by', '=', 'S.id')
            ->select([
                'PU.pick_up_name AS pName',
                'PU.reg_number AS regNo',
                'S.names AS names',
                'PU.id AS id'
            ])
            ->where('PU.soft_delete', 0)
            ->where('S.soft_delete', 0)
            ->orderBy('PU.pick_up_name', 'ASC')
            ->get();

        $pickUpAreas = DB::table('waste_schedule_pickup')
            ->select([
                'location',
            ])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('soft_delete', 0)
            ->groupBy('location')
            ->get();

        $schedules = DB::table('pick_up_date_schedule AS PS')
            ->join('pickup_management AS PM', 'PS.pick_up_id', '=', 'PM.id')
            ->join('staff AS ST', 'PS.staff_id', '=', 'ST.id')
            ->select([
                'PM.pick_up_name AS pName',
                'PS.pickup_day AS day',
                'PS.preferred_time AS time',
                'PS.location AS area',
                'ST.names AS names',
            ])
            ->where('PS.soft_delete', 0)
            ->orderBy('PS.id', 'DESC')
            ->get();

        // dd($pickUpAreas);

        return view('templates.pick-ups', compact([
            'staffs',
            'pickUpsData',
            'pickUpAreas',
            'schedules'
        ]));
    }

    public function storePickUpsData(Request $request)
    {
        $request->validate([
            'pick_up_name' => 'required|string',
            'reg_number' => 'nullable|string',
        ]);

        $pickUpExists = DB::table('pickup_management')
            ->where('pick_up_name', $request->pick_up_name)
            ->where('reg_number', $request->reg_number)
            ->where('soft_delete', 0)
            ->exists();

        if ($pickUpExists === true) {
            return redirect()->back()->with('error', 'This pickup already sxists!');
        }

        DB::table('pickup_management')->insert([
            'pick_up_name' => $request->pick_up_name,
            'reg_number' => $request->reg_number,
            'added_by' => Auth::user()->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Pick Up added successfully!');
    }

    public function storeSchedulesPickUp(Request $request)
    {
        $data = $request->validate([
            'pickup_day' => 'required|date',
            'pick_up_id' => 'required|integer',
            'preferred_time' => 'nullable|string',
            'location' => 'required|string',
        ]);

        $pickUpExsists = DB::table('pick_up_date_schedule')
            ->where('pick_up_id', $request->pick_up_id)
            ->where('location', $request->location)
            ->where('pickup_day', $request->pickup_day)
            ->exists();

        if ($pickUpExsists == true) {
            return redirect()->back()->with('error', 'Pick schedule for ' . ' ' . $request->location . ' ' . ' on' . ' ' . $request->pickup_day . ' ' . 'already available!');
        }

        DB::table('pick_up_date_schedule')->insert([
            'pickup_day' => $request->pickup_day,
            'pick_up_id' => $request->pick_up_id,
            'preferred_time' => $request->preferred_time,
            'location' => $request->location,
            'staff_id' => Auth::user()->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Pick up schedule created successfully!');
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

        $residentsCounter = DB::table('residents')->where('soft_delete', 0)->count();
        $streetCounter = DB::table('waste_schedule_pickup')->select('location')->where('soft_delete', 0)->distinct()->count();
        $requestsCounter = DB::table('waste_schedule_pickup')->where('soft_delete', 0)->count();
        $collectionEarlings = DB::table('payments')->where('soft_delete', 0)->sum('amount');

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $dailyData = DB::table('waste_schedule_pickup')
            ->where('soft_delete', 0)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total_requests, COUNT(DISTINCT user_id) as total_residents')
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->keyBy('day');

        $labels = [];
        $requests = [];
        $residents = [];

        foreach (Carbon::now()->startOfWeek()->daysUntil(Carbon::now()->endOfWeek()) as $date) {
            $dayName = $date->format('l');
            $dayKey = $date->format('Y-m-d');

            $labels[] = $dayName;
            $requests[] = $dailyData[$dayKey]->total_requests ?? 0;
            $residents[] = $dailyData[$dayKey]->total_residents ?? 0;
        }

        $weeklyStats = DB::table('waste_schedule_pickup AS PR')
            ->join('residents AS R', 'PR.user_id', '=', 'R.id')
            ->select([
                'R.id AS residentId',
                'R.name AS names',
                'PR.location AS location',
                DB::raw('COUNT(PR.id) AS totalRequests')
            ])
            ->whereBetween('PR.created_at', [$startOfWeek, $endOfWeek])
            ->where('PR.soft_delete', 0)
            ->groupBy('R.id', 'R.name', 'PR.location')
            ->orderByDesc('totalRequests')
            ->limit(4)
            ->get();

        $allStats = DB::table('waste_schedule_pickup AS PR')
            ->join('residents AS R', 'PR.user_id', '=', 'R.id')
            ->join('payments AS P', 'PR.id', '=', 'P.pick_up_id')
            ->select([
                'R.id AS residentId',
                'R.name AS names',
                'PR.location AS location',
                DB::raw('MAX(PR.created_at) AS dueDate'),
                DB::raw('SUM(P.amount) AS totalPaid'),
                DB::raw('MAX(P.currency) AS currency'),
                DB::raw('COUNT(PR.id) AS totalRequests')
            ])
            ->where('PR.soft_delete', 0)
            ->groupBy('R.id', 'R.name', 'PR.location')
            ->orderByDesc('totalRequests')
            ->limit(10)
            ->get();

        $schedules = DB::table('pick_up_date_schedule AS PS')
            ->join('pickup_management AS PM', 'PS.pick_up_id', '=', 'PM.id')
            ->join('staff AS ST', 'PS.staff_id', '=', 'ST.id')
            ->select([
                'PM.pick_up_name AS pName',
                'PS.pickup_day AS day',
                'PS.preferred_time AS time',
                'PS.location AS area',
                'ST.names AS names',
            ])
            ->whereBetween('PS.created_at', [$startOfWeek, $endOfWeek])
            ->where('PS.soft_delete', 0)
            ->orderBy('PS.id', 'DESC')
            ->get();

        // dd($allStats);

        return view('templates.dashboard', compact(
            'balance',
            'myPickUpRequests',
            'months',
            'totals',
            'residentsCounter',
            'streetCounter',
            'requestsCounter',
            'collectionEarlings',
            'labels',
            'requests',
            'residents',
            'weeklyStats',
            'startOfWeek',
            'endOfWeek',
            'allStats',
            'schedules',
        ));
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

        // $address = 'Dar es salaam Kigamboni Ferry';

        $address = DB::table('residents')->select('address')->where('id', Auth::user()->user_id)->value('address');

        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        return view('templates.schedule-pickups', compact(
            'myPickUpRequests',
            'balance',
            'address',
        ));
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

        $userId = Auth::user()->user_id;

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


        $allRequestsCounter = DB::table('waste_schedule_pickup')
            ->where('soft_delete', 0)
            ->count();

        $pendingRequestsCounter = DB::table('waste_schedule_pickup')
            ->where('status', 'pending')
            ->where('soft_delete', 0)
            ->count();

        $acceptedRequestsCounter = DB::table('waste_schedule_pickup')
            ->where('status', 'accepted')
            ->where('soft_delete', 0)
            ->count();

        $compltedRequestsCounter = DB::table('waste_schedule_pickup')
            ->where('status', 'completed')
            ->where('soft_delete', 0)
            ->count();

        return view('templates.pickup-requests', compact(
            'incompeleteRequests',
            'acceptedRequests',
            'completedRequests',
            'balance',
            'allRequestsCounter',
            'pendingRequestsCounter',
            'acceptedRequestsCounter',
            'compltedRequestsCounter',
        ));
    }

    public function pickUpLocations()
    {
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

        // SET REQUEST FOR THIS WEEK

        $locations = $completedRequests->pluck('pickupLocation')->filter()
            ->unique()
            ->values();

        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();

        return view('templates.pickup-locations', compact('balance','locations'));
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
            ->select(
                'RS.id AS id',
                'RS.title AS materialName',
                'RSC.name AS materialCategory',
                'RS.weight AS weight',
                'R.name AS listedBy',
                'RS.price AS price',
                'RS.image AS image',
            )
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

    public function transactions()
    {
        $allTransactionsCounter = DB::table('payments')
            ->where('soft_delete', 0)
            ->count();

        $pendingTransactionsCounter = DB::table('payments')
            ->where('soft_delete', 0)
            ->where('status', null)
            ->count();

        $canclledTransactionsCounter = DB::table('payments')
            ->whereNot('soft_delete', 0)
            ->count();

        $complteTransactionsCounter = DB::table('payments')
            ->where('soft_delete', 0)
            ->whereIn('status', ['approved', 'Paid'])
            ->count();

        $transactions = DB::table('payments')
            ->select('*')
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $cancelledTransaction = DB::table('payments')
            ->select('*')
            ->whereNot('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        return view('templates.transactions', compact([
            'allTransactionsCounter',
            'pendingTransactionsCounter',
            'canclledTransactionsCounter',
            'complteTransactionsCounter',
            'transactions',
            'cancelledTransaction'
        ]));
    }

    public function contracts($encryptedId)
    {
        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();
        $id = Crypt::decrypt($encryptedId);

        $recyclable = DB::table('recyclables')->where('id', $id)->first();

        $contractData = [
            'recyclable_id' => $recyclable->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $recyclable->user_id,
            'price_usd' => $recyclable->price,
        ];

        $sellerData = DB::table('residents')->where('id', $recyclable->user_id)->first();

        $userData = DB::table('residents')->where('id', Auth::user()->id)->first();

        $block = BlockchainSimulator::createBlock($contractData);

        $contract = Contract::create(array_merge($contractData, [
            'status' => 'Pending',
            'blockchain_data' => json_encode($block)
        ]));

        return view('templates.contracts', compact('balance', 'contract', 'recyclable', 'userData', 'sellerData'));
    }

    public function createContract(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'recyclable_id' => 'required|string',
            'price' => 'required|string',
        ]);

        $price = Crypt::decrypt($request->price);
        $contractId = Crypt::decrypt($request->id);
        $recyclableId = Crypt::decrypt($request->recyclable_id);

        // dd($recyclableId);

        if (DB::table('contracts')->where('id', $contractId)->where('recyclable_id', $recyclableId)->exists() === true) {
            return redirect()->back()->with('error', 'Contract already exists!');
        }

        DB::table('contracts')->where('id', $contractId)->update([
            'status' => 'Approved',
        ]);

        $user = DB::table('recyclables')->where('id', $recyclableId)->first();

        $amountInTSH = CurrencyConverter::convertUsdToTsh($price);

        $userWallet = DB::table('wallets')->where('user_id', $user->user_id)->first();

        $availableBalance = $userWallet->balance;

        DB::table('wallets')->where('user_id', $user->user_id)->update([
            'balance' => $availableBalance + $amountInTSH,
        ]);

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
