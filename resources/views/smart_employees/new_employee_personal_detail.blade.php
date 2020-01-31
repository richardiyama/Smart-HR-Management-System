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
    <div class="col-md-2 col-xs-12"></div>
    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
            <a href="{{url('employees')}}"><span class="fa fa-arrow-left" style="color:green"></span> &nbsp; Back to
                active employee</a>
            <div class="x_title">
                <h2>Add New Employee <br> <small>Fill in the detail of the employee below then send it for
                        approval</small></h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />

                <form class="form-horizontal form-label-left" method="post" action="{{url('add_employee_detail')}}">
                    {{ csrf_field() }}
                    <strong>Personal Detail</strong>
                    <hr>

                    <input type="hidden" value="{{$id}}" name="employee_id">
                    <input type="hidden" value="{{Auth::user()->id}}" name="user_id">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">First Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" required class="form-control @error('firstname') is-invalid @enderror"
                                placeholder="Enter First Name" name="firstname" value="{{ old('firstname') }}">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" required class="form-control @error('lastname') is-invalid @enderror"
                                placeholder="Enter Employee Last name" value="{{ old('lastname')}}" name="lastname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Middle name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" name="middlename"
                                class="form-control @error('middlename') is-invalid @enderror"
                                placeholder="Enter Middle name" value="{{old('middlename')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span
                                class="required text-danger">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <!--  <input @if(isset($personal)) type="text" @else type="date" @endif  required name="date_of_birth" class="form-control" value="@if(isset($personal)) {{$personal->date_of_birth}} @endif"> -->
                            <input type="date" required name="date_of_birth" class="form-control"
                                value="{{old('date_of_birth')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}"
                                name="gender" required>
                                <option value="">Select employee gender</option>

                                <option value="M">Male</option>
                                <option value="F">Female</option>

                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nationality</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" placeholder="Nationality" name="nationality"
                                value="{{old('nationality')}}" id="autocomplete-custom-append"
                                class="form-control col-md-10" />
                        </div>
                    </div>





                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Does this employee has driver license?
                        </label>

                        <div class="col-md-9 col-sm-9 col-xs-12">

                            <div class="radio">
                                <label>
                                    <input type="radio" checked="" value="1" id="driver_license" name="driver_license">
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" value="0" id="driver_license" name="driver_license"> No
                                </label>
                            </div>
                        </div>
                    </div>

                    <strong>Contact Details</strong>
                    <hr>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Country Code</label>
                        <div class="col-md-2 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="Enter employee country code"
                                name="country_code" value="{{old('country_code')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Phone Number</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="tel" class="form-control" placeholder="Enter employee phone number"
                                name="phone" value="{{old('phone')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="email" class="form-control" placeholder="Enter Employee email" name="email"
                                value="{{old('email')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea name="address" placeholder="address" id="" cols="30" class="form-control"
                                rows="10">{{old('address')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <button type="submit" class="btn btn-success">Company and Position Details <span
                                    class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



@endsection