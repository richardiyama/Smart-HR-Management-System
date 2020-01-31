@extends('layouts.dash')

@section('content')
@include('inc.profile_header')

<div class="x_panel"> <br>

    <div class="x_title">
        <h2>Work Schedule</h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @if ($contact)


        <div class="row">
            <div class="col-md-6">
                <input type="radio" @if ($contact->work_time == 1)
                checked
                @endif> Full time
                &nbsp; &nbsp;
                <input type="radio" @if ($contact->work_time == 0)
                checked
                @endif> Part time
            </div>

        </div>
        <br>

        <div class="row">
            <div class="col-md-2">
                <span>Monday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->moday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->moday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <span>Tuesday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->tuesday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->tuesday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <span>Wednesday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->wednesday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->wednesday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <span>Thurseday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->thurseday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->thurseday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <span>Friday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->friday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->friday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <span>Saturday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->saturday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->saturday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <span>Sunday</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Resumption time</span> <br>
                <?php $mt = DB::table('morning_period')->where('id', $contact->sunday_morning)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Closing time</span> <br>
                <?php $mt = DB::table('evening_period')->where('id', $contact->sunday_evening)->first(); ?>
                <strong>
                    @if ($mt)
                    {{date('h:i A', strtotime($mt->time))}}
                    @endif

                </strong>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">Does this employee qualify for overtime ? </span> <br>
                <strong>
                    <input type="radio" @if ($contact->overtime==1) checked

                    @endif> Yes
                    <input type="radio" @if ($contact->overtime==0) checked

                    @endif> No
                </strong>
            </div>
        </div>
        @endif

    </div>
</div>






<!-- ============================================================== -->
<!-- End Container fluid  -->


@endsection