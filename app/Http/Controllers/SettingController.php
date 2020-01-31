<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class Setting
{
    public $confirmation_email;
    public $activity_email;
    public $tax;
    public $pension;
}

class SettingController extends Controller
{
    public function changePassword(Request $request)
    {
        $check =  $this->guard()->attempt(['email' => $request->email, 'password' => $request->old_password]);

        if ($check) {
            DB::table('users')->where('email', $request->email)->update([
                'password' => bcrypt($request->new_password)
            ]);
            return response()->json([
                'check' => 1
            ]);
        } else {
            return response()->json([
                'check' => $check
            ]);
        }
    }
    protected function guard()
    {
        return Auth::guard();
    }
    public function UpdateSettings(Request $request)
    {
        $confirm_email = $request->confirmation_email == true ? 1 : 0;
        $activity_email = $request->activity_email == true ? 1 : 0;
        DB::table('settings_user')->where('user_id', $request->user_id)->update([
            'email_confirmation_approval' => $confirm_email,
            'email_all_activities' => $activity_email,
        ]);

        DB::table('settings_global')->update([
            'tax' => $request->tax,
            'pension' => $request->pension
        ]);

        $set =  $this->getSetting($request->user_id);

        return response()->json([
            'setting' => $set
        ]);
    }
    public function index()
    {
        $set =  $this->getSetting(Auth::user()->id);
        return view('setting.settings', ['setting' => $set]);
    }

    public function getSetting($id)
    {
        $user_setting = DB::table('settings_user')->where('user_id', $id)->first();
        $set = new Setting();
        if ($user_setting) {
            $set->confirmation_email = $user_setting->email_confirmation_approval;
            $set->activity_email = $user_setting->email_all_activities;
        }
        $global = DB::table('settings_global')->first();
        $set->tax = $global->tax;
        $set->pension = $global->pension;
        return $set;
    }
}
