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
            <h2>Employee Designation History<small>View</small></h2>
           <!-- <ul class="nav navbar-right panel_toolbox">
              <a href="site/create" class="btn btn-primary">Add New Site</a>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>-->
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Reason</th>
            <th>Employee Name</th>
            <th>Company</th>
            <th>Site</th>
            <th>Department</th>
            <th>Date Of Change</th>
            
        </tr>
    </thead>
    <tbody>
        @if(count($transfers)>0)
        @foreach ($transfers as &$transfer)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{get_object_vars($transfer)['type']}}</td>
                    <td>{{get_object_vars($transfer)['employee_name']}}</td>
                    <td>{{get_object_vars($transfer)['employee_company']}}</td>
                    <td>{{get_object_vars($transfer)['employee_site']}}</td>
                    <td>{{get_object_vars($transfer)['employee_department']}}</td>
                    <td>{{get_object_vars($transfer)['created_at']}}</td>
                   
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
