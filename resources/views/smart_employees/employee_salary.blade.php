@extends('layouts.dash')

@section('content')
<div class="row">
    <div class="col-md-2 col-xs-12" ></div>
    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
        <a href="{{url('employees')}}"><span class="fa fa-arrow-left" style="color:green"></span>   &nbsp; Back to active employee</a>
          <div class="x_title">
            <h2>Add New Employee <br> <small>Fill in the detail of the employee below then send it for approval</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />

          <form class="form-horizontal form-label-left" method="post" action="{{url('add_employee_salary')}}">
             {{ csrf_field() }}
                <strong>Salary and Account Details</strong> <hr>

            <input type="hidden" value="{{$id}}" name="employee_id">
            <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
    
             
                 <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Basic Salary</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" required class="form-control" placeholder="Enter employee basic salary (digit only)" name="basic_salary">
                    </div>
                  </div> -->
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Salary : Lumpsum</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" required class="form-control" placeholder="Enter employee salary (digit only)" name="salary" value="@if(isset($salary)){{$salary->salary}}@endif">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                      <select name="bank" id="" class="form-control">
                        <option value="">Select employee bank name</option>
                        <?php 
                        $lists = DB::table('banks')->orderby('name')->get();
                        if($lists) {
                          foreach ($lists as $key => $value) {
                            ?>
                          <option value="{{$value->id}}" @if(isset($salary)){{$salary->bank == $value->id ? 'selected' : ''}}@endif>{{$value->name}}</option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                        </div>
                      </div>



                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Account Number <span class="required">*</span>
                          </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                           <input type="text" maxlength="10" placeholder="Enter employee account number" required name="account_number" class="form-control" value="@if(isset($salary)){{$salary->account_number}}@endif">
                          </div>
                        </div>

                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pension Fund Administrator(PFA) <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                      <select name="pfa" id="" class="form-control">
                        <option value="">Select employee PFA</option>
                        <?php 
                        $lists = DB::table('pfa')->orderby('name')->get();
                        if($lists) {
                          foreach ($lists as $key => $value) {
                            ?>
                          <option value="{{$value->id}}" @if(isset($salary)){{$salary->pfa == $value->id ? 'selected' : ''}}@endif>{{$value->name}}</option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                        </div>
                      </div>


                        
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Pension Number</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" required class="form-control" placeholder="Enter employee pension number" name="pension_number" value="@if(isset($salary)){{$salary->pension_number}}@endif">
                      </div>
                    </div>
            
  

              
              
              
              
         <strong>Next of Kin</strong> <hr>

         <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Next of Kin Name</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="text" required class="form-control" placeholder="Enter employee next of kin name" name="next_kin_name" value="@if(isset($salary)){{$salary->next_kin_name}}@endif">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Next of Kin Phone Number</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="text"  class="form-control" placeholder="Enter employee next of kin phone number" name="next_kin_phone" value="@if(isset($salary)){{$salary->next_kin_phone}}@endif">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Next of Kin Relationship</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="text"  class="form-control" placeholder="Enter employee next of kin relationship" name="next_kin_relationship" value="@if(isset($salary)){{$salary->next_kin_relationship}}@endif">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Next of Kin Address</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="text"  class="form-control" placeholder="Enter employee next of kin address" name="next_kin_address" value="@if(isset($salary)){{$salary->next_kin_address}}@endif">
          </div>
        </div>

           
                 
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                                <button type="submit" class="btn btn-success">Emergency Contact   <span class="fa fa-arrow-right"></span></button>
                              <span style="color:red">Please fill in all fields</span>
                            </div>
                </div>
              
            </form>
          </div>
        </div>
      </div>
</div>


    
@endsection