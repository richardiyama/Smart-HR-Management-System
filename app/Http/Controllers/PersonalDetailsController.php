<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Day;
use App\Company;
use App\Site;
use App\Department;
use App\Position;
use App\Bank;

class PersonalDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('employees.create.personalDetails');  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    public function personal(Request $request)
    {
        try{
            $rule = [
                'f_name'        => 'required|string|max:191',
                'l_name'        => 'required|string|max:191',
                'm_name'        => 'required|string|max:191',
                'dob'           => 'required|date_format:mm/dd/YYYY|before:today',
                'gender'        => 'required|not_in:0',
                'nationality'   => 'required|string|max:50',
                'license'       => 'required|in:yes,No',
                'phone_no'      => 'required|digits:15',
                'email'         => 'required|string|max:50|unique:employees',
                'address'       => 'required|string|max:191',
            ];
    
            $valid= Validator($request->all(), $rule);
            if($valid->fails())
            {
                return var_dump($valid->error());
            }
    
            $days=Day::all();
            $companies= Company::all();
            $sites= Site::all();
            $departments= Department::all();
            $positions= Position::all();
            return view('employees.company.index')->with('days',$days)->with('companies',$companies)->with('sites',$sites)->with('departments',$departments)->with('positions',$positions);
        }catch(\Exception $error)
        {   
            return $error->getMessage();
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $rules = [
            'bvn'        => 'required|numeric|digits:10|unique:employees',

        ];  
        $this->validate($request, $rules);
        
        $request->session()->put('bvn',$request->input('bvn'));
        return view('employees.create.personalDetails');
        // $ruler = [
        //     'f_name'        => 'required|string|max:191',
        //     'l_name'        => 'required|string|max:191',
        //     'm_name'        => 'required|string|max:191',
        //     'dob'           => 'required|date_format:mm/dd/YYYY|before:today',
        //     'gender'        => 'required|not_in:0',
        //     'nationality'   => 'required|string|max:50',
        //     'license'       => 'required|in:yes,No',
        //     'phone_no'      => 'required|digits:15',
        //     'email'         => 'required|string|max:50|unique:employees',
        //     'address'       => 'required|string|max:191',
        // ];  
        // $this->validate($request, $ruler);
        // $request->session()->put([  'f_name',       $request->input('f_name'),
        //                             'l_name',       $request->input('l_name'),
        //                             'm_name',       $request->input('m_name'),
        //                             'dob',          $request->input('dob'),
        //                             'gender',       $request->input('gender'),
        //                             'nationality',  $request->input('nationality'),
        //                             'licesen',      $request->input('license'),
        //                             'phone_no',     $request->input('phone_no'),
        //                             'email',        $request->input('email'),
        //                             'address',      $request->input('address'),]);
        // $days=Day::all();
        // $companies= Company::all();
        // $sites= Site::all();
        // $departments= Department::all();
        // $positions= Position::all();
        // return view('employees.company.index')->with('days',$days)->with('companies',$companies)->with('sites',$sites)->with('departments',$departments)->with('positions',$positions);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
