<?php

namespace App\Http\Controllers\Users;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Mail\EmailVerifyMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    //
    public function emailVerify()
    {
        // dd(Carbon::now());
        return view('templates.verify-email');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'password' => 'required|string|max:255',
            'password_confirm' => 'required|string|max:255',
        ]);

        if ($request->password != $request->password_confirm) {
            return redirect()->back()->with('error_msg', 'Passwords do not match!');
        }

        // EXISTING USER IN RESIDENT TABLE
        $existingResident = DB::table('residents')->where('phone', $request->email)->where('soft_delete', 0)->exists();

        // EXISTING USER IN AUTHENTICATION TABLE (users)
        $existsInAuth = DB::table('users')->where('username', $request->email)->where('soft_delete', 0)->exists();

        if ($existingResident == true) {
            return redirect()->back()->with('error', 'You are already in the system!. No need to register again');
        }

        if ($existsInAuth == true) {
            return redirect()->back()->with('error', 'You are already in the system!. No need to register again');
        }

        try {
            $userId = DB::table('residents')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            DB::table('users')->insert([
                'username' => $request->email,
                'password' => Hash::make($request->password),
                'user_id' => $userId,
                'user_type' => 3,
            ]);

            DB::table('wallets')->insert([
                'user_id' => $userId,
                'wallet_type' => 'Saving',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $userEmail = $request->email;

        $otp = random_int(1000, 9999);

        Mail::to($userEmail)->send(new EmailVerifyMail($otp, $userEmail));

        $maskedEmail = substr($userEmail, 0, 4) . '******' . substr($userEmail, -10);

        $checkExistingEmail = DB::table('email_verify')->where('email', $userEmail)->exists();

        if ($checkExistingEmail == true) {
            DB::table('email_verify')->where('email', $userEmail)->update([
                'token' => Crypt::encrypt($otp),
            ]);
        } else {
            DB::table('email_verify')->insert([
                'email' => $userEmail,
                'token' => Crypt::encrypt($otp),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('email.verify')->with([
            'maskedEmail' => $maskedEmail,
            'otp' => $otp,
            'email' => $userEmail,
        ]);
    }

    public function otpVerify(Request $request)
    {
        $request->validate([
            'otp' => 'required|integer|min:1000|max:9999',
            'email' => 'required|string',
        ]);

        $otpVerify = DB::table('email_verify')->where('email', $request->email)->first();

        if (!$otpVerify) {
            return back()->with('error', 'Invalid OTP or email!');
        }

        $otpCreatedAt = Carbon::parse($otpVerify->created_at);

        // dd($otpCreatedAt->diffInMinutes(Carbon::now()));

        if ($otpCreatedAt->diffInMinutes(Carbon::now()) > 30) {
            return back()->with('error', 'OTP has expired. Please request a new one.');
        }

        // IF EVERYTHING IS SET OKY, START SESSION
        $user = User::where('username', $request->email)->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('dashboard')->with(['success' => 'Registered and logged in succefully!']);
        }

        return redirect()->route('register')->with(['error' => 'Something is wrong, try again later!']);
    }

    public function userManagement()
    {
        $roles = UserRole::select([
            'id',
            'name'
        ])
            ->whereIn('id', [1, 2])
            ->get();

        $staffs = DB::table('staff AS S')
            ->join('user_roles AS UR', 'S.role', '=', 'UR.id')
            ->select([
                'S.names AS names',
                'UR.name AS roleName',
                'S.email AS email',
                'S.phone_number AS phone',
                'S.gender AS gender',
                'S.created_at AS regDate',
                'S.is_active AS status',
                'S.id AS autoId',
                'UR.id AS roleId',
            ])
            ->where('S.soft_delete', 0)
            ->orderBy('S.names', 'ASC')
            ->get();

            // dd($staffs);

        return view('templates.user-management', compact('roles','staffs'));
    }

    public function storeStaff(Request $request)
    {
        $validatedData = $request->validate([
            'names' => 'required|string',
            'email' => 'required|string',
            'phone_number' => ['nullable', 'regex:/^0\d{9}$/'],
            'role' => 'required|integer',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'password' => 'required|string|max:20',
            'password_confirm' => 'required|string|max:20',
        ]);

        if ($request->password != $request->password_confirm) {
            return redirect()->back()->with('error', 'Passwords do not match!');
        }

        $userCheck = DB::table('staff')
            ->where('phone_number', $request->phone_number)
            ->orWhere('email', $request->email)
            ->where('soft_delete', 0)
            ->first();

        if ($userCheck != null) {
            return redirect()->back()->with('error', 'User already exists in our database!');
        }

        $authExists = DB::table('users')
            ->where('username', $request->email)
            ->where('soft_delete', 0)
            ->exists();

        if ($authExists == true) {
            return redirect()->back()->with('error', 'User already exists in our database!');
        }

        $filePath = null;

        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('photos', 'public');
        }

        $userId = DB::table('staff')->insertGetId([
            'names' => $request->names,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'photo' => $filePath,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'username' => $request->email,
            'user_type' => $request->role,
            'user_id' => $userId,
            'password' => Hash::make($request->password),
            'login_attempts' => 0,
            'blocked_at' => null,
            'is_new' => 1,
            'soft_delete' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // dd($validatedData);

        return redirect()->back()->with('success', 'New user registered successfully!');
    }
}
