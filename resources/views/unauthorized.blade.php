@extends('layouts.dash')

@section('content')
<div class="title m-b-md">
    <div class="alert alert-danger">You cannot access this page! This is for only {{$role}}</div>
</div>
@endsection