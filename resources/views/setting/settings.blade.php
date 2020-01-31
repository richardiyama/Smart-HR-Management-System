@extends('layouts.dash')

@section('content')

<Settings :role_id="'{{Auth::user()->role}}'" :setting = "'{{json_encode($setting)}}'" :user_id="'{{Auth::user()->id}}'" :email="'{{Auth::user()->email}}'"></Settings>

@endsection