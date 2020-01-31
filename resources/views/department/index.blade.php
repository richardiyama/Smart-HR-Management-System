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
            <h2>Department<small>View</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <a href="department/create" class="btn btn-primary">Add New Department</a>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Department Name</th>
            <th>Site Name</th>
            
            <th>{{__('user_role.table.action')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($departments)>0)
            @foreach ($departments as $department)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$department['name']}}</td>
                    <td>
                    <?php 
                        $the_site = DB::table('sites')->where('id',$department['site_id'])->first();
                        echo $the_site->name;
                        ?>
                        
                    </td>
                   
                    <td>
                        <a href="{{action('DepartmentController@edit', $department['id'])}}"><span class="label label-warning"> Edit <i class="fa fa-edit"></i></span></a>
                        <a onclick="return confirm('Are you sure you want to delete this Department ? ')" href="{{ route('Department.delete', $department['id']) }}"><span class="label label-danger">Delete<i class="fa fa-trash"></i></span></a>
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
