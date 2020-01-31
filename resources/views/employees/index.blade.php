@extends('layouts.dash')
@section('content')
    <!-- page content -->
 <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="row tile_count">
          <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> {{__('employee.label.total_employee')}}</span>
              <div class="count">2500</div>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-building-o"></i> {{__('employee.label.total_employee_year')}}</span>
              <div class="count">123.50</div>
              <span class="count_bottom"><i class="green">12 </i> Pending</span>
          </div>
        <!-- /top tiles -->
        <div class="row">
          @if (session('success'))
            <div class="x_content bs-example-popovers">
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                    <strong>Done!</strong>  {{ session('success') }}
                </div>
            </div>
          @endif
            <div class="col-md-12"> {{__('employee.label.search')}} </div>
              <div class="row calendar-exibit">  
                  <div class="col-md-3">
                      <div class="col-md-1"></div>
                      <span>{{__('employee.label.date')}}</span>
                      <fieldset>
                          <div class="control-group">
                          <div class="controls">
                              <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                                  <input type="text" class="form-control has-feedback-left" id="single_cal1" placeholder="First Name" aria-describedby="inputSuccess2Status">
                              <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                              <span id="inputSuccess2Status" class="sr-only">(success)</span>
                              </div>
                          </div>
                          </div>
                      </fieldset>
                  </div>
                    <div class="col-md-1">

                      <span><i class="fa fa-arrow-right"></i></span>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-1"></div>
                        <span>{{__('employee.label.date')}}</span>
                        <fieldset>
                        <div class="control-group">
                            <div class="controls">
                            <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal2" placeholder="Choose date" aria-describedby="inputSuccess2Status2">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                            </div>
                            </div>
                        </div>
                        </fieldset>
                    </div>
                    
              </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>{{__('employee.label.active_employee')}} <small>{{__('employee.label.view')}}</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><button type="button" onclick="location.href = '{{ action('EmployeesController@create') }}'" class="btn btn-primary">{{__('employee.button.add_new')}}</button></li>
                              <li><button type="button" class="btn btn-primary">{{__('employee.button.bulk')}}</button></li>
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="col-md-12">{{__('employee.label.filter')}}</div>
                            <div class="col-md-12" style="margin-bottom:20px !important">
                                <form class="form-vertical form-label-top">
                                  <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <label>{{__('employee.label.company')}}</label>
                                      <select name="company"class="form-control">
                                        @if(count($companies)>0)
                                          <option>All</option>
                                          @foreach ($companies as $company)
                                          <option>{{$company['name']}}</option>
                                          @endforeach
                                        @endif
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">  
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <label>{{__('employee.label.site')}}</label>
                                      <select name="site" class="form-control">
                                        @if(count($sites)>0)
                                          <option>All</option>
                                          @foreach ($sites as $site)
                                          <option>{{$site->name}}</option>
                                          @endforeach
                                        @endif
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">  
                                      
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                      <label>{{__('employee.label.department')}}</label>    
                                      <select name="department" class="form-control">
                                          @if(count($departments)>0)
                                        <option>All</option>
                                        @foreach ($departments as $department)
                                        <option>{{$department->name}}</option>
                                        @endforeach
                                      @endif
                                      </select>
                                    </div>
                                  </div>
                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-3 col-xs-3" style="margin-top:22px">
                                            <a href="#" class="btn btn-primary">{{__('employee.button.search')}}</a>
                                        </div>
                                      </div>
                                </form>
                            </div>
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                              <thead>
                                <tr>
                                  <th>{{__('employee.table.no')}}</th>
                                  <th>{{__('employee.table.name')}}</th>
                                  <th>{{__('employee.table.company')}}</th>
                                  <th>{{__('employee.table.employee_no')}}</th>
                                  <th>{{__('employee.table.site')}}</th>
                                  <th>{{__('employee.table.department')}}</th>
                                  <th>{{__('employee.table.action')}}</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Tiger Nixon</td>
                                  <td>System Architect</td>
                                  <td>Edinburgh</td>
                                  <td>61</td>
                                  <td>2011/04/25</td>
                                  <td>$320,800</td>
                                  <td>$320,800</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
        </div>
        <br />
  </div>
  <!-- /page content -->    
@endsection
