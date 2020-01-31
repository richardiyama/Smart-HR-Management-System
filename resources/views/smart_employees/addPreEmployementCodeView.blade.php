@extends('layouts.dash')

@section('content')
<!-- top tiles -->



<div class="container">
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

        <div class="col-md-4">
            <div class="dbox dbox--color-3">
                <div class="dbox__icon">
                    <i class="glyphicon glyphicon-edit"></i>
                </div>
                <div class="dbox__body">
                    <span class="dbox__count">Begin employment process</span>

                </div>

                <div class="dbox__action">
                    <!-- Trigger the modal with a button -->
                    <button type="button" class="dbox__action__btn" data-toggle="modal" data-target="#myModal"
                        id="open">Add
                        Pre-Employement Code</button>


                </div>
            </div>
        </div>



        <div class="col-md-4">
            <div class="dbox dbox--color-1">
                <div class="dbox__icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="dbox__body">
                    <span class="dbox__count"> Total Number of Employees</span>
                    <span
                        class="dbox__title"><?php echo DB::table('employee_bvn')->where('active', 1)->count() ?></span>

                </div>
                <div class="dbox__action">
                    <button class="dbox__action__btn"></button>
                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="dbox dbox--color-2">
                <div class="dbox__icon">
                    <i class="fa fa-building-o"></i>
                </div>
                <div class="dbox__body">
                    <span class="dbox__count">
                        <?php echo DB::table('pre_employments')->where('project_manager_approval',1)->where('hr_manager_approval',1)->count(); ?></span>
                    <span class="dbox__title">Total Number of Approved Pre-Employement</span>
                </div>

                <div class="dbox__action">
                    <button class="dbox__action__btn"></button>
                </div>
            </div>
        </div>





        <form method="post" action="{{url('addEmployeeBvn')}}" id="form">
            {{ csrf_field() }}
            <!-- Modal -->
            <div class="modal" tabindex="-1" role="dialog" id="myModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="alert alert-danger" style="display:none">

                        </div>
                        <div class="modal-header">

                            <h5 class="modal-title">Pre-Employment Code Checker</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="Name">Code:</label>
                                    <input type="text" class="form-control" name="code" id="code">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Name">BVN:</label>
                                    <input type="text" class="form-control" name="bvn" id="bvn" pattern="\d{11}">
                                    <span style="color:red">BVN must be 11 digit</span>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-success" id="ajaxSubmit">Check</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>

</div>
<!-- /page content -->
@endsection