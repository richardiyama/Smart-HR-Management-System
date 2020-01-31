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
            <h2>Employee Salary Increment History<small>View</small></h2>
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
            <th>Employee Name</th>
            <th>Previous salary</th>
            <th>Salary increment</th>
            <th>Reason</th>
            <th>Approval Date</th>
            
        </tr>
    </thead>
    <tbody>
        @if(count($salarys)>0)
            @foreach ($salarys as &$salary)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                    <?php 
                        $employee_name = DB::table('employee_personal_details')->where('employee_id', get_object_vars($salary)['employee_id'])->first();
                        if($employee_name) {
                          $name = $employee_name->firstname. ' '. $employee_name->middlename . ' '. $employee_name->lastname;
                      }
                        echo $name;
                        ?>
                        
                    </td>
                    <td>{{get_object_vars($salary)['from']}}</td>
                    <td>{{get_object_vars($salary)['amount']}}</td>
                    <td>{{get_object_vars($salary)['reason']}}
                    <td>{{get_object_vars($salary)['approved_date']}}</td>
                    
                   
                   
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
