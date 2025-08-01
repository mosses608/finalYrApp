<?php

namespace App\Http\Controllers\Users;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Mail\EmailVerifyMail;
use App\Mail\PasswordRestEmail;
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

        return view('templates.user-management', compact('roles', 'staffs'));
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

    public function viewResidents()
    {
        $residents = DB::table('residents')
            ->select('*')
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();
        return view('templates.residents-view', compact('residents'));
    }

    public function forgotPassword()
    {
        return view('templates.forgot-password');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('username', $request->username)
            ->where('soft_delete', 0)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User does not exist!');
        }

        $resident = DB::table('residents')
            ->select('email')
            ->where('id', $user->user_id)
            ->where('soft_delete', 0)
            ->first();

        if (!$resident) {
            return redirect()->back()->with('error', 'Resident does not exist!');
        }

        $otp = random_int(1000, 9999);

        $userExist = DB::table('password_reset_tokens')->where('email', $resident->email)->first();

        if (!$userExist) {
            DB::table('password_reset_tokens')->insert([
                'email' => $resident->email,
                'token' => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]);
        } else {
            DB::table('password_reset_tokens')->where('email', $resident->email)->update([
                'token' => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]);
        }

        $residentEmail = $resident->email;

        // dd($residentEmail);

        Mail::to($residentEmail)->send(new PasswordRestEmail($otp, $residentEmail));

        $maskedEmail = substr($residentEmail, 0, 4) . '******' . substr($residentEmail, -10);

        return redirect()->back()->with('success', 'Rest link has been sent to an email.' . ' ' . $maskedEmail);
    }

    public function changePassword(Request $request)
    {
        $token = $request->query('otp');
        $email = $request->query('email');
        return view('templates.changepassword', compact([
            'token',
            'email',
        ]));
    }

    public function passwordResset(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string',
            'password_reset' => 'required|string',
        ]);

        // dd($request->all());

        if ($request->password != $request->password_reset) {
            return redirect()->back()->with('error', 'Passwords do not match!');
        }

        $decryptedEmail = Crypt::decrypt($request->email);
        $decryptedToken = Crypt::decrypt($request->token);

        // dd($decryptedToken);

        $authUser = DB::table('users')->where('username', $decryptedEmail)->where('soft_delete', 0)->first();

        if (Hash::check($request->password, $authUser->password)) {
            return redirect()->back()->with('error', 'New password can not be same a old password!');
        }

        $userExists = DB::table('password_reset_tokens')
            ->where('email', $decryptedEmail)
            ->first();

        if ($userExists) {
            if (Hash::check($decryptedToken, $userExists->token)) {
                DB::table('users')->where('username', $decryptedEmail)->update([
                    'password' => Hash::make($request->password),
                ]);

                DB::table('password_reset_tokens')
                    ->where('email', $decryptedEmail)->delete();
            }
        }

        return redirect('/')->with('success', 'Password changed successfully!. You can now login');
    }

    public function profile()
    {
        $balance = DB::table('wallets')->where('user_id', Auth::user()->user_id)->where('soft_delete', 0)->where('status', 'active')->first();
        $resident = DB::table('residents')
            ->where('id', Auth::user()->user_id)
            ->first();
        return view('templates.profile', compact('balance', 'resident'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'required|string',
            // 'password' => 'nullable|string',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->user_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $userExists = DB::table('residents')
            ->where('id', $decryptedId)
            ->exists();

        if ($userExists == false) {
            return redirect()->back()->with('error', 'Error!, User not found!');
        }

        try {
            DB::table('residents')->where('id', $decryptedId)
                ->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'email' => $request->email,
                ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success', 'Profile updated successfuly!');
    }

    public function adminProfile()
    {
        $user = DB::table('staff AS S')
            ->join('users AS U', 'S.id', '=', 'U.user_id')
            ->join('user_roles AS R', 'S.role', '=', 'R.id')
            ->select([
                'U.*',
                'S.*',
                'R.name AS roleName',
            ])
            ->where('S.id', Auth::user()->user_id)
            ->first();

        return view('templates.admin-profile', compact('user'));
    }

    public function updateAdminProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            // 'address' => 'nullable|string',
            'email' => 'required|string',
            // 'password' => 'nullable|string',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->user_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $userExists = DB::table('staff')
            ->where('id', $decryptedId)
            ->exists();

        if ($userExists == false) {
            return redirect()->back()->with('error', 'Error!, User not found!');
        }

        try {
            DB::table('staff')->where('id', $decryptedId)
                ->update([
                    'names' => $request->name,
                    'phone_number' => $request->phone,
                    // 'address' => $request->address,
                    'email' => $request->email,
                ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success', 'Profile updated successfuly!');
    }

    public function deleteUser(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|integer',
        ]);

        $staffExists = DB::table('staff')
            ->where('id', $request->staff_id)
            ->where('soft_delete', 0)
            ->exists();

        if ($staffExists == false) {
            return redirect()->back()->with('error', 'Staff does not exists!');
        }

        DB::table('staff')
            ->where('id', $request->staff_id)
            ->where('soft_delete', 0)
            ->update([
                'soft_delete' => 1,
            ]);

        return redirect()->back()->with('success', 'Staff deleted successfully!');
    }

    public function blockUser(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|integer',
        ]);

        $staffExists = DB::table('staff')
            ->where('id', $request->staff_id)
            ->where('soft_delete', 0)
            ->exists();

        if ($staffExists == false) {
            return redirect()->back()->with('error', 'Staff does not exists!');
        }

        DB::table('staff')
            ->where('id', $request->staff_id)
            ->where('soft_delete', 0)
            ->update([
                'is_active' => 0,
            ]);

        return redirect()->back()->with('success', 'Staff blocked successfully!');
    }
}
