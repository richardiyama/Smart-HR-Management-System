@extends('layouts.dash')

@section('content')
<div class="row">

@if (session('success'))
        <div class="x_content bs-example-popovers">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
                <strong>Done!</strong>  {{ session('success') }}
            </div>
          </div>
      @endif


    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="col-md-2 col-xs-12">
         
                   
                    
                
    </div>
    <div class="col-md-8 col-xs-12">
    <span class="count_top"><i class="fa fa-user"></i> Total Number of Approved Pre-Employement</span>
                    <div class="count" style="font-size: 50px"><?php echo DB::table('pre_employments')->where('project_manager_approval',1)->where('hr_manager_approval',1)->count(); ?></div>
                    <form class="form-horizontal form-label-left" method="get" action="{{url('approvedPre_employment_request')}}">
                    {{ csrf_field() }}
                            <button type="submit" class="btn btn-success">View Approved Pre_employment Request</button>
                    </form>
        <div class="x_panel">

            <div class="x_title">
                <h2>Pre-Employment Request Form <br> <small>Use this form to initiate the recruitment process for all
                        new and existing staff. Please complete all applicable sections of this form and then send it
                        for approval</small></h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />

                <form class="form-horizontal form-label-left" method="post" action="{{route('pre_employment.store')}}">
                    {{ csrf_field() }}
                    <strong>Position Requested</strong>
                    <hr>


                    <div class="form-group {{ $errors->has('job_title') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="job_title">Job Title <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}"
                                    name="job_title">
                                    <option value="">Select Job Title</option>
                                    @foreach($job_title as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('job_title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('job_title') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('section') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section">Section <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                    name="section">
                                    <option value="">Select Section</option>
                                    @foreach($section as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('section'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('site') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site">Site <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control{{ $errors->has('site') ? ' is-invalid' : '' }}"
                                    name="site">
                                    <option value="">Select site</option>
                                    @foreach($site as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                                
                                @if ($errors->has('site'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('site') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <strong>Position Status</strong>
                    <hr>

                    <div class="form-group {{ $errors->has('position_status') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position_status">Position Status <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control{{ $errors->has('position_status') ? ' is-invalid' : '' }}"
                                    name="position_status">
                                    <option value="">Select position status</option>
                                    @foreach($position_status as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('position_status'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('position_status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Expected Start Date <span
                                class="required text-danger">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <!--  <input @if(isset($personal)) type="text" @else type="date" @endif  required name="date_of_birth" class="form-control" value="@if(isset($personal)) {{$personal->date_of_birth}} @endif"> -->
                            <input type="date" required name="start_date" class="form-control"
                                value="{{old('start_date')}}">
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Proposed Monthly Lump sum <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="amount" name="amount" value="{{ old('amount') }}" required="" class="form-control col-md-7 col-xs-12">
                    
                    @if ($errors->has('amount'))
                      <span class="help-block">
                          <strong>{{ $errors->first('amount') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>




                   

                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <button type="submit" class="btn btn-success">Send For Approval <span
                                    class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



@endsection