<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Auth;
use Illuminate\Support\Facades\DB;
use App\EmailNotify;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $roles = Role::all()->toArray();
        // $count=Role::count();

        return view('users.roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.roles');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roles = new Role([
            'role_name' => $request['role_name'],
        ]);

        $rules = [
            'role_name' => 'required|string|max:50|unique:role',
        ];
        $this->validate($request, $rules);

        $roles->save();

        $user = DB::table('users')->where('id', Auth::user()->id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->bvn = $request->bvn;
        $email->subject = "User Role Notification";
        $email->body = "This is to notify you of creation of  new role (" . $request['role_name'] . ") in the smartHR. ";
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        return redirect()->back()->with('success', 'Role successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return view('users.editRole', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
    public function update(Request $request)
    {
        $role =  Role::find($request->id);
        $role->role_name = $request->role_name;
        //$role->save();
        //return redirect('/users/roles')->with('status', 'Role updated successfully');
        //dd($role);
        // $role->role_name = $request->get('role_name');
       // dd($role);


        $rules = [
            'role_name' => 'required|string|max:50|unique:role',
        ];
        $this->validate($request, $rules);

        $role->save();
       // return 1;
        //return redirect()->back()->with('success', 'Role Updated Successfully.');
        return redirect('/users/roles')->with('status', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();

        return redirect()->back()->with('success', 'Role was deleted successfully');
    }
}
