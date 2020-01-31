@extends('layouts.dash')
@section('content')
    <!-- page content -->
 
    <div class="row">
        <div class="success">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
          <h2>Role<small>View</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add">Add a New Role</button></li>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            {{-- role modal --}}
          <div class="modal fade bs-example-modal-lg" id="add" tabindex="-1" role="dialog" aria-hidden="true">
                  
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Give your New Role a Name</h4>
                  </div>
                  <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{route('Role.store')}}">
                      {{ csrf_field() }}
                    <div class="modal-body">
                      <div class="form-group {{ $errors->has('role_name') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="edit-role-name" name="role_name" value="{{ old('role_name') }}"  required class="form-control col-md-7 col-xs-12">
                          @if ($errors->has('role_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('role_name') }}</strong>
                            </span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
                </div>
              </div>
            </div>
            {{-- end role modal --}}
            {{-- edit role model --}}

            @foreach($roles as $role)
            @endforeach
            <div class="modal fade bs-example-modal-lg" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
                  
                <div class="modal-dialog modal-lg">
                  <div class="" style="background:#fff; box-shadow: 0 5px 15px rgba(0,0,0,.5);border-radius: 6px;">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                      </button>
                      <h4 class="modal-title" id="myModalLabel">Update Role a Name</h4>
                    </div>
                    <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ route('Role.update', $role['id'])}}">
                      {{ method_field('PATCH') }}
                      {{ csrf_field() }}
                      <div class="modal-body">
                        <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role Name <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="edit-name" name="role" value="{{$role['role_name']}}"  required class="form-control col-md-7 col-xs-12">
                            @if ($errors->has('role'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('role') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                      </div>
                  </form>
                  </div>
                </div>
              </div>
              
              {{-- end edit role modal --}}
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Role</th>
                  <th style="text-align:center">Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($roles as $role)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{$role['role_name']}}</td>
                  <td style="text-align:center">
                 <!-- <a   href="{{url('roles/edit')}}/{{$role['id']}}"><span class="label label-warning"> Edit <i class="fa fa-edit"></i></span></a>
                      <a onclick="return confirm('Are you sure you want to delete this role ? ')" href="{{ route('Role.delete', $role['id']) }}"><span class="label label-danger"> Delete <i class="fa fa-trash"></i></span></a>-->
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
 
  <!-- /page content -->    
@endsection
