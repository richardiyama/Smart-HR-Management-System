<!-- page content -->
@extends('layouts.dash')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add New Site</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="text-align:center">
                <h4 class="modal-title" id="myModalLabel">Please enter the Details of the New Site</h4>
                <br />
                <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST"
                    action="{{ route('site.store') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Site Name <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required=""
                                    class="form-control col-md-7 col-xs-12">

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_id">Company <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control{{ $errors->has('company_id') ? ' is-invalid' : '' }}"
                                    name="company_id">
                                    @foreach($company_id as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('company_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('company_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{url('site')}}" class="btn btn-default">Back to sites</a>
                            <button type="submit" class="btn btn-success">Add Site</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection