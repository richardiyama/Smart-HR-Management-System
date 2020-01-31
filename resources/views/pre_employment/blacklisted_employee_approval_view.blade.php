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
            <h2>Blacklisted Employee<small>View</small></h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Employeee Name</th>
           
            
            <th>{{__('user_role.table.action')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($blacklists)>0)
            @foreach ($blacklists as &$blacklist)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    
                    <td>{{get_object_vars($blacklist)['employee_name']}}</td>
                    
                    
                   
                    <td>
                        <a href="{{action('EmployeesController@blacklisted_employee_approval', get_object_vars($blacklist)['id'])}}"><span class="label label-warning"> Approve <i class="fa fa-thumbs-o-up"></i></span></a>
                        
                    </td>

                  
                
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
