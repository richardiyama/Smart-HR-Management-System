@extends('layouts.dash')

@section('content')
<div class="row">

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="col-md-2 col-xs-12" ></div>
    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
        <a href="{{url('index')}}"><span class="fa fa-arrow-left" style="color:green"></span>   &nbsp; Back to active employee</a>
          <div class="x_title">
            <h2>Add New Employee <br> <small>Fill in the detail of the employee below then send it for approval</small></h2>
            
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />

            @if($approve == 1)

            <form class="form-horizontal form-label-left" method="post" action="{{url('send_blacklisted_approval')}}">
             {{ csrf_field() }}
             <h2 style="color:red;font-weight: bolder">This Employee have been blacklisted..Please seek approval from management for any further actions </h2>
             <input type="hidden" value="{{$id}}" name="employee_id">
             
             <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                                <button type="submit" class="btn btn-danger">Seek for Approval <span class="fa fa-arrow-right"></span></button>
                            </div>
                </div>


                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">First Name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control @error('firstname') is-invalid @enderror" placeholder="Enter First Name" name="firstname" value="@if(isset($personal)) {{$personal->firstname}} @endif" disabled>
                  
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control @error('lastname') is-invalid @enderror" placeholder="Enter Employee Last name" value="@if(isset($personal)) {{$personal->lastname}} @endif" name="lastname" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Middle name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" name="middlename" class="form-control @error('middlename') is-invalid @enderror" placeholder="Enter Middle name" value="@if(isset($personal)) {{$personal->middlename}} @endif" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required text-danger">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
               <!--  <input @if(isset($personal)) type="text" @else type="date" @endif  required name="date_of_birth" class="form-control" value="@if(isset($personal)) {{$personal->date_of_birth}} @endif"> -->
               <input type="date" name="date_of_birth" class="form-control" value="{{$personal->date_of_birth}}" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select name="gender"  id="" class="form-control" disabled>
                      <option value="">Select employee gender</option>
                      <option value="M" @if(isset($personal)) {{$personal->gender=="M" ? 'selected': ''}} @endif>Male</option>
                      <option value="F"  @if(isset($personal)) {{$personal->gender=="F" ? 'selected': ''}} @endif>Female</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nationality</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" placeholder="Nationality" name="nationality" value="@if(isset($personal)) {{$personal->nationality}} @endif" id="autocomplete-custom-append" class="form-control col-md-10" disabled/>
                </div>
              </div>
             
            
             
          
    
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12 control-label">Does this employee has driver license?
                </label>
    
                <div class="col-md-9 col-sm-9 col-xs-12">
                  
                  <div class="radio">
                    <label>
                      <input type="radio" checked="" value="1" id="driver_license" name="driver_license" value="@if(isset($personal)) {{$personal->driver_license == 1 ? 'checked' : ''}} @endif" disabled> Yes
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" value="0"  id="driver_license" name="driver_license"  value="@if(isset($personal)) {{$personal->driver_license == 1 ? 'checked' : ''}} @else checked @endif" disabled> No
                    </label>
                  </div>
                </div>
              </div>

              <strong>Contact Details</strong> <hr>


              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Country Code</label>
                    <div class="col-md-2 col-sm-9 col-xs-12">
                      <input type="text" class="form-control" placeholder="Enter employee country code" name="country_code" value="@if(isset($personal)) {{$personal->country_code}} @endif" disabled>
                    </div>
                  </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Phone Number</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="tel" class="form-control" placeholder="Enter employee phone number" name="phone" value="@if(isset($personal)) {{$personal->phone}} @endif" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="email" class="form-control" placeholder="Enter Employee email" name="email" value="@if(isset($personal)) {{$personal->email}} @endif" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                     <textarea name="address" placeholder="address" id="" cols="30" class="form-control" rows="10" disabled>@if(isset($personal)) {{$personal->address}} @endif</textarea>
                    </div>
                </div>
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                                <button type="submit" class="btn btn-success" disabled>Company and Position Details <span class="fa fa-arrow-right"></span></button>
                            </div>
                </div>
             </form>
          
    

                @else
                <form class="form-horizontal form-label-left" method="post" action="{{url('add_employee_detail')}}">
             {{ csrf_field() }}
                <strong>Personal Detail</strong> <hr>

            <input type="hidden" value="{{$id}}" name="employee_id">
            <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
    

                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">First Name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" required class="form-control @error('firstname') is-invalid @enderror" placeholder="Enter First Name" name="firstname" value="@if(isset($personal)) {{$personal->firstname}} @endif">
                  
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" required class="form-control @error('lastname') is-invalid @enderror" placeholder="Enter Employee Last name" value="@if(isset($personal)) {{$personal->lastname}} @endif" name="lastname">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Middle name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" name="middlename" class="form-control @error('middlename') is-invalid @enderror" placeholder="Enter Middle name" value="@if(isset($personal)) {{$personal->middlename}} @endif">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required text-danger">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
               <!--  <input @if(isset($personal)) type="text" @else type="date" @endif  required name="date_of_birth" class="form-control" value="@if(isset($personal)) {{$personal->date_of_birth}} @endif"> -->
               <input type="date" required name="date_of_birth" class="form-control" value="{{$personal->date_of_birth}}">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select name="gender"  id="" class="form-control" required>
                      <option value="">Select employee gender</option>
                      <option value="M" @if(isset($personal)) {{$personal->gender=="M" ? 'selected': ''}} @endif>Male</option>
                      <option value="F"  @if(isset($personal)) {{$personal->gender=="F" ? 'selected': ''}} @endif>Female</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nationality</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" placeholder="Nationality" name="nationality" value="@if(isset($personal)) {{$personal->nationality}} @endif" id="autocomplete-custom-append" class="form-control col-md-10"/>
                </div>
              </div>
             
            
             
          
    
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12 control-label">Does this employee has driver license?
                </label>
    
                <div class="col-md-9 col-sm-9 col-xs-12">
                  
                  <div class="radio">
                    <label>
                      <input type="radio" checked="" value="1" id="driver_license" name="driver_license" value="@if(isset($personal)) {{$personal->driver_license == 1 ? 'checked' : ''}} @endif"> Yes
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" value="0"  id="driver_license" name="driver_license"  value="@if(isset($personal)) {{$personal->driver_license == 1 ? 'checked' : ''}} @else checked @endif"> No
                    </label>
                  </div>
                </div>
              </div>

              <strong>Contact Details</strong> <hr>


              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Country Code</label>
                    <div class="col-md-2 col-sm-9 col-xs-12">
                      <input type="text" class="form-control" placeholder="Enter employee country code" name="country_code" value="@if(isset($personal)) {{$personal->country_code}} @endif">
                    </div>
                  </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Phone Number</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="tel" class="form-control" placeholder="Enter employee phone number" name="phone" value="@if(isset($personal)) {{$personal->phone}} @endif">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="email" class="form-control" placeholder="Enter Employee email" name="email" value="@if(isset($personal)) {{$personal->email}} @endif">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                     <textarea name="address" placeholder="address" id="" cols="30" class="form-control" rows="10">@if(isset($personal)) {{$personal->address}} @endif</textarea>
                    </div>
                </div>
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                                <button type="submit" class="btn btn-success">Company and Position Details <span class="fa fa-arrow-right"></span></button>
                            </div>
                </div>
                @endif
               
              
            </form>
          </div>
        </div>
      </div>
</div>


    
@endsection