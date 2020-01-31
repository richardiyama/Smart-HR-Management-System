@extends('layouts.dash')

@section('content')
<Payrollhistory  :user_id="'{{Auth::user()->id}}'" :departments="'{{json_encode($departments)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'">
</Payrollhistory>

    
@endsection