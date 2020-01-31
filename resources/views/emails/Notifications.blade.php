@component('mail::message')
Hello ! <br>
# {{$email->subject}}

{{$email->body}}


<br><br>

Regards <br>
{{$email->user}}


@component('mail::button', ['url' => URL::to('/dashboard')])
Go to Smart HR
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
