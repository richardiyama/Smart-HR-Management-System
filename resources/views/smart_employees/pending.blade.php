@extends('layouts.dash')
@section('content')
    <!-- page content -->
<Pendingemployees :new_employees="'{{json_encode($new_employees)}}'" :total_pending="'{{$total_pending}}'" :jobs="'{{json_encode($jobs)}}'" :departments="'{{json_encode($departments
)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" :employee_count="'{{$employee_count}}'" :employee_year_count="'{{$employee_year_count}}'" :employees="'{{json_encode($employees)}}'"></Pendingemployees>
  <!-- /page content -->  
  
@endsection
