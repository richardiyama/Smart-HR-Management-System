<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Role;
use App\UserRole;
use App\User;
use Illuminate\Support\Facades\DB;
use App\EmailNotify;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;
use Auth;

class UsersController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all()->toArray();
        $users = User::all();
        return view('users.index', compact('roles','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create')->with('roles',$roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $users = new User([
            'name'      => $request['name'],
            'email'     => $request['email'],
            'role'      => $request['role'],
            'password' => bcrypt($request['password']),
          ]);

          

          $rules = [
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'role'       => 'required|not_in:0',
            'password'   => 'required|string|min:6|confirmed',
        ];  
        $this->validate($request, $rules);

          $users->save();
          DB::table('settings_user')->insert([
            'email_confirmation_approval' => 1,
            'email_all_activities' => 1,
            'user_id' => $users->id
        ]);

        //send email
        $user = DB::table('users')->where('id', Auth::user()->id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->bvn = $request->bvn;
        $email->subject = "User Creation Notification";
        $email->body = "This is to notify you of creation of new user(".$request['name'].") in the smartHR. ";

        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        
          


        return redirect('users')->with('success', 'User added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $roles=Role::all();
        // $user= User::find($id);
        // return view('users.edit', compact('roles'))->with('user',$user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $roles=Role::all();
        $user= User::find($id);
        return view('users.edit', compact('roles'))->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       

        $user =  User::find($id);
        $user->name = $request->input('name');
       // $user->email = $request->input('email');
        $user->role = $request->input('role');
        
        // if(!empty($request->input('password')))
        // {
        //     $user->password = bcrypt($request->input('password'));
        // }
          
        $rules = [
            'name'       => 'required|string|max:255',
            //'email'      => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role'       => 'required|not_in:0',
            // 'password'   => 'required|string|min:6|different:current_password|confirmed',
        ];
        $this->validate($request, $rules);
          

          $user->save();
        return redirect('users')->with('success','User Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = User::find($id);
        $users->delete();

        return redirect()->back()->with('success','User was deleted successfully');
    }
}
