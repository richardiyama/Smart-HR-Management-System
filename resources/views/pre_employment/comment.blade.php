@extends('layouts.dash')


@section('content')

<div class="row">

    @if (session('success'))
    <div class="x_content bs-example-popovers">
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">×</span>
            </button>
            <strong>Done!</strong> {{ session('success') }}
        </div>
    </div>
    @endif


    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="col-md-2 col-xs-12"></div>
    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
            <h5 class="text-center" style="color: red;">{{$employee_name}}</h5>
                <h4>Let us know why the above employee can be re-employed</h5>


                <div class="clearfix"></div>
            </div>

            <div class="x_content">


                <form class="form-horizontal form-label-left" method="post" action="{{url('add_comments')}}">
                    {{ csrf_field() }}



                    <input type="hidden" value="{{$id}}" name="id">


                    <div class="form-group">
                        <h2 class="text-center">Enter comment</h2>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea name="comment" placeholder="comment" id="" cols="30" class="form-control"
                                rows="10">comment</textarea>
                            <br />
                            <p class="error" style="color: red;"></p>
                            <p class="results" style="color: green;"></p>
                            @if ($errors->has('comment'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('comment') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <button type="submit" class="btn btn-success">Comment<span
                                    class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



@endsection