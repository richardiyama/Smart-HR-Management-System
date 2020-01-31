@extends('layouts.dash')
@section('content')
    <!-- page content -->
<Employeemanager :pending="'{{$pending}}'" :role_id="'{{Auth::user()->role}}'" :user_id="'{{Auth::user()->id}}'" :jobs="'{{json_encode($jobs)}}'" :departments="'{{json_encode($departments
)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" :employee_count="'{{$employee_count}}'" :employee_year_count="'{{$employee_year_count}}'" :employees="'{{json_encode($employees)}}'"></Employeemanager>
  <!-- /page content -->  
  
  
  <!-- Modal -->
  

<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
  <Bvnnewemployee :user_id="'{{Auth::user()->id}}'"></Bvnnewemployee>

  </div>
</div>
@endsection
