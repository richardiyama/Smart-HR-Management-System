@extends('layouts.dash')
@section('content')
<!-- page content -->

<div class="row">
    @if (session('success'))
    <div class="x_content bs-example-popovers">
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">×</span>
            </button>
            <strong>Done!</strong> {{ session('success') }}
        </div>
    </div>
    @endif
    <div class="col-md-12 col-sm-12 col-xs-12">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

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
                            <th>Pre-Employment Code</th>
                            @if(Auth::user()->role == 3)
                            <th>{{__('user_role.table.action')}}</th>
                            @endif
                            @if(Auth::user()->role==13 )
                            <th>{{__('user_role.table.action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($pre_employments)>0)
                        @foreach ($pre_employments as $pre_employment)
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
                            <td>{{get_object_vars($pre_employment)['pre_emp_code']}}</td>


                            <td>
                                <button type="button" class="dbox__action__btn" data-toggle="modal"
                                    data-target="#myModal" id="open">Add
                                    Pre-Employement Code</button>
                                <form method="post" action="{{url('addEmployeeBvn')}}" id="form">
                                    {{ csrf_field() }}
                                    <!-- Modal -->
                                    <div class="modal" tabindex="-1" role="dialog" id="myModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">


                                                <div class="alert alert-danger" style="display:none"></div>
                                                <div class="modal-header">

                                                    <h5 class="modal-title">Pre-Employment Code Checker</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="Name">Code:</label>
                                                            <input type="text" class="form-control" name="code"
                                                                id="code">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="Name">BVN:</label>
                                                            <input type="text" class="form-control" name="bvn" id="bvn" pattern="\d{11}">
                                                            <span style="color:red">BVN must be 11 digit</span>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button class="btn btn-success" id="ajaxSubmit">Check</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

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