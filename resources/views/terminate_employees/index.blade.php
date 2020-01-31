@extends('layouts.dash')
@section('content')
    <!-- page content -->
<Terminatedemployeemanager :departments="'{{json_encode($departments
)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" :employee_count="'{{$employee_count}}'" :employee_count_awaiting="'{{$employee_count_awaiting}}'" :employees="'{{json_encode($employees)}}'"></Terminatedemployeemanager>
  <!-- /page content -->  
  

@endsection
