<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('pages.index');
    }

    public function login(){
        return view('login.index');
    }

    public function terminate(){
        return view('employees.terminate');
    }

    public function attendance(){
        return view('attendance.index');
    }
    public function pastAttendance(){
        return view('attendance.pastAttendance');
    }

    public function attendanceReport(){
        return view('attendance.report');
    }

    public function payroll(){
        return view('payroll.index');
    }

    public function payrollHistory(){
        return view('payroll.history');
    }

    public function payrollReport(){
        return view('payroll.report');
    }

    


}
