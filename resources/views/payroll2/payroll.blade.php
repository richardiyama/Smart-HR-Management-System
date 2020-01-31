@extends('layouts.dash')

@section('content')
<Generatepayroll employees="'{{json_encode($employees)}}'" :user_id="'{{Auth::user()->id}}'" :this_today="'{{$this_today}}'" :departments="'{{json_encode($departments)}}'" :sites="'{{json_encode($sites)}}'" :companies="'{{json_encode($companies)}}'" :today="'{{$today}}'">

</Generatepayroll>

    
@endsection