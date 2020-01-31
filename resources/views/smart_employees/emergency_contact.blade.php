@extends('layouts.dash')

@section('content')

@include('inc.profile_header')


<div class="x_title">
    <h2>Emergency Contacts</h2>
    <ul class="nav navbar-right panel_toolbox">
        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    @if ($emergency)
    @foreach ($emergency as $item)
    <div class="row">
        <div class="col-md-6">
            <span class="text-muted">Emergency Contact </span> <br>
            <strong>{{$item->name }}</strong>
        </div>
        <div class="col-md-6">
            <span class="text-muted">Emergency contact phone number</span> <br>
            <strong>
                {{$item->phone}}
            </strong>
        </div>
    </div>
    <br>
    @endforeach

    @endif



</div>

@endsection