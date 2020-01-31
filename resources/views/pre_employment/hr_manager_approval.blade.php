@extends('layouts.dash')
@section('content')
    <!-- page content -->

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
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Pre-Employement Request<small>View</small></h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Job Title</th>
            <th>Section</th>
            <th>Position Status</th>
            <th>Proposed Monthly Lump sum</th>
            <th>Requesting Supervisor</th>
            @if(Auth::user()->role == 3)
            <th>{{__('user_role.table.action')}}</th>
            @endif
            @if(Auth::user()->role==13 )
            <th>{{__('user_role.table.action')}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if(count($hr_pre_employments)>0)
            @foreach ($hr_pre_employments as $pre_employment)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                    <?php 
                        $job_title = DB::table('positions')->where('id', get_object_vars($pre_employment)['job_title'])->first();
                        if($job_title) {
                          $name = $job_title->name;
                      }
                        echo $name;
                        ?>
                        
                    </td>
                    <td>
                    <?php 
                        $section = DB::table('departments')->where('id', get_object_vars($pre_employment)['section'])->first();
                        if($section) {
                          $name = $section->name;
                      }
                        echo $name;
                        ?>
                        
                    </td>
                    <td>
                    <?php 
                        $position_status = DB::table('position_status')->where('id', get_object_vars($pre_employment)['position_status'])->first();
                        if($position_status) {
                          $name = $position_status->name;
                      }
                        echo $name;
                        ?>
                        
                    </td>
                    <td>{{get_object_vars($pre_employment)['amount']}}</td>
                    <td>{{get_object_vars($pre_employment)['request_supervisor']}}</td>
                    
                    @if(Auth::user()->role == 3)
                    <td>
                        <a href="{{action('PreEmployementController@pre_employment_hr_manager_approval', get_object_vars($pre_employment)['id'])}}"><span class="label label-warning"> Approve <i class="fa fa-thumbs-o-up"></i></span></a>
                        
                    </td>
                    @endif
                    @if(Auth::user()->role == 13)
                    <td>
                        <a href="{{action('PreEmployementController@pre_employment_project_manager_approval', get_object_vars($pre_employment)['id'])}}"><span class="label label-warning"> Approve <i class="fa fa-thumbs-o-up"></i></span></a>
                        
                    </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
          </div>
        </div>
      </div>
    </div>
    <br />

  <!-- /page content -->    
@endsection
