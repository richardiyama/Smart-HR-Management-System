@extends('layouts.dash')

@section('content')


@include('inc.profile_header')






<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- Row -->

<div class="row">

    <!-- Column -->
    <!-- <div class="facts">


        <div class="facts_left">-->


    <div class="col-md-55">




        <div class="thumbnail">
            @foreach ($documents as $item)
            <div class="image view view-first" style="width: 200px;height:320px;">
                <img style="width: 190px;height:200px; " src="{{asset('storage/assets')}}/{{$item->file}}"
                    alt="image" />
                <div class="mask">
                    <p>{{$item->title}}</p>
                    <div class="tools tools-bottom">
                        <!-- <a href="#"><i class="fa fa-link"></i></a>
<a href="#"><i class="fa fa-pencil"></i></a>
<a href="#"><i class="fa fa-times"></i></a> -->
                        <a title="view" target="_blank" href="{{asset('storage/assets')}}/{{$item->file}}"><i
                                class="fa fa-pencil"></i></a>
                    </div>
                </div>
            </div>
            <div class="caption">
                <p><a title="Click for large view" href="{{asset('storage/assets')}}/{{$item->file}}">View
                        {{$item->title}}</a></p>
            </div>



            @endforeach
        </div>


        @if(Auth::user()->role != 2)
        <button class="btn btn-primary btn-block "
            onclick="window.location.href = '{{url('employee')}}/{{$employee_id}}/update';">Edit
            employee detail</button><br>
        @endif



        <button class="btn btn-primary btn-block" title="Print employee detail"
            onclick="window.location.href = 'javascript:print()';">Print</button>
        <!-- <a href="javascript:print()"  style="background:#73879C; color:white" title="Print employee detail" class="button"> Print</span></a> <br>-->
    </div>






    <!-- Column -->
    <!-- Column -->
    <div class="facts_right">
        <div class="col-lg-8 col-xlg-9 col-md-7">
            @if ($personal)
            <div class="row">
                <div class="col-md-6">
                    Employee number:
                    <?php 
                        $employeeno = DB::table('employee_bvn')->where('id', $personal->employee_id)->first();
                     ?>
                    <strong><?php if($employeeno){
                     echo $employeeno->employee_number; 
                } ?></strong>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-6">

                    <strong>Full Name: {{$personal->firstname}} {{$personal->middlename}}
                        {{$personal->lastname}}</strong>
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-6">

                    <strong> Date of birth: {{$personal->date_of_birth}}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">

                    <strong>Gender:{{$personal->gender}}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">

                    <strong>Nationality: {{$personal->nationality}}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Does this employee have driver license ?</span> <br>
                    <strong>
                        <input type="radio" @if ($personal->driver_license==1) checked

                        @endif> Yes
                        <input type="radio" @if ($personal->driver_license==0) checked

                        @endif> No
                    </strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">

                    <strong>Driver license Number: {{$personal->driver_license_no}}</strong>
                </div>
            </div>
            @endif




            <div class="row">
                <div class="x_title">


                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if ($contact)


                    <div class="row">

                        <div class="col-md-6">
                            <h2>Contact Detail</h2>

                            Current company:
                            <strong>
                                <?php 
                    $company = DB::table('companies')->where('id', $contact->company)->first();
                    if($company) {
                        echo $company->name;
                    }
                    ?>
                            </strong>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            Current site:
                            <strong>
                                <?php 
                            $site = DB::table('sites')->where('id', $contact->site)->first();
                            if($site) {
                                echo $site->name;
                            }
                            ?>
                            </strong>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            Current department:
                            <strong>
                                <?php 
                     $department = DB::table('departments')->where('id', $contact->department)->first();
                     if($department) {
                         echo $department->name;
                     }
                     ?>
                            </strong>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            Current position:
                            <strong>
                                <?php 
                             $position = DB::table('positions')->where('id', $contact->job_position)->first();
                             if($position) {
                                 echo $position->name;
                             }
                             ?>
                            </strong>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            Start date:
                            <strong>{{$contact->start_date}}</strong>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endif

    </div>


</div>





<!-- ============================================================== -->
<!-- End Container fluid  -->


@endsection