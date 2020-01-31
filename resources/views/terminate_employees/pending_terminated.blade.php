@extends('layouts.dash')
@section('content')
    <!-- page content -->
<Pendingterminated :user_id="'{{Auth::user()->id}}'" :departments="'{{json_encode($departments
)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" :employee_count="'{{$employee_count}}'"  :employees="'{{json_encode($employees)}}'"></Pendingterminated>
  <!-- /page content -->  
  

@endsection
