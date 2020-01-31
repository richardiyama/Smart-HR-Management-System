@extends('layouts.dash')
@section('content')
    <!-- page content -->
@php
  $today = date("l jS \of F Y h:i:s A");
  $this_today = date('Y-m-d');
@endphp
<Pastattendances :the_downloads = "'{{json_encode($downloads)}}'" :employees="'{{json_encode($employees)}}'" :user_id="'{{Auth::user()->id}}'" :this_today="'{{$this_today}}'" :departments="'{{json_encode($departments)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" :today="'{{$today}}'"></Pastattendances>
      
  
  <!-- /page content -->    
@endsection
