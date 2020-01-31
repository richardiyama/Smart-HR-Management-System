@extends('layouts.dash')

@section('content')

@include('inc.profile_header')
<?php 
         $emp = DB::table('employee_bvn')->where('id', $employee_id)->first();
         if($emp->is_terminated_request == 1 ) {
             $termination = DB::table('terminated_employees')->where('id', $emp->terminate_id)->first();
             $terminated_documents= DB::table('terminated_employee_documents')->where('employee_id', $emp->id)->get();
             
             ?>

<section id="terminated">
    <div class="x_panel" id=""> <br>
<div class="x_title">
    <h2>Termination Details</h2>
    <ul class="nav navbar-right panel_toolbox">
        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>
<div class="x_content" id="termination-details">

    <div class="row">
        <div class="col-md-6">
            <span class="text-muted">Reason for termination</span> <br>
            <strong>{{$termination->terminated_reason}}</strong>
        </div>
        <div class="col-md-6">
            <span class="text-muted">Date of termination</span> <br>
            <strong>{{$termination->date}}</strong>
        </div>
    </div> <br>
    <div class="row">
        <div class="col-md-12">
            <span class="text-muted">Further detail explaining termination</span> <br>
            <p>
                {{$termination->details}}
            </p>
        </div>
    </div>

    @if ($terminated_documents)
    <div class="row">
        @foreach ($terminated_documents as $item)
        <div class="col-md-6">
            <div class="thumbnail">
                <div class="image view view-first">
                    <img title="Click the pencil icon view document" style="width: 100%; display: block;"
                        src="{{asset('storage/assets/')}}/{{$item->file}}" />
                    <div class="mask">
                        <p>{{$item->created}}</p>
                        <div class="tools tools-bottom">
                            <a href="{{asset('storage/assets/')}}/{{$item->file}}" title="open this file"><i
                                    class="fa fa-pencil"></i></a>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>{{$item->document_title}}</p>
                </div>
            </div>
        </div>

        @endforeach
    </div>

    @endif



</div>
     

    </div>
  </section>

<?php
         }
         ?>

@endsection