@extends('layouts.dash')
@section('content')
    <!-- page content -->
<Changelumpsum :lumpsum_count = "'{{json_encode($lumpsum_count)}}'" :lumpsum="'{{json_encode($lumpsum)}}'"  :role_id="'{{Auth::user()->role}}'" :user_id="'{{Auth::user()->id}}'"  :departments="'{{json_encode($departments)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" ></Changelumpsum>
  
@endsection
