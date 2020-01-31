@extends('layouts.dash')
@section('content')
    <!-- page content -->

        <!-- top tiles -->
        <div class="row tile_count">
          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i>{{__('attendance.label.total_hours')}}</span>
              <div class="count">2500</div>
          </div>
          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-building-o"></i>{{__('attendance.label.total_company')}}</span>
              <div class="count">123.50</div>
              {{-- <span class="count_bottom"><i class="green">12 </i> Pending</span> --}}
          </div>
        </div>
        <!-- /top tiles -->

        <div class="row">
            <div class="col-md-12">{{__('attendance.label.view_hours')}}</div>
                <div class="row calendar-exibit">  
                    <div class="col-md-3">
                        <div class="col-md-1"></div>
                        <span>{{__('attendance.label.date')}}</span>
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

                      <span>{{__('attendance.label.to')}}:</span>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-1"></div>
                        <span>{{__('attendance.label.date')}}</span>
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
                            <h2>{{__('attendance.label.attendance')}} <small>{{__('attendance.label.report')}}</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="col-md-12">{{__('attendance.label.filter')}}</div>
                            <div class="col-md-12" style="margin-bottom:20px !important">
                                <form class="form-vertical form-label-top">
                                  <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <label>{{__('attendance.label.company')}}</label>
                                      <select class="form-control">
                                        <option>All</option>
                                        <option>Option one</option>
                                        <option>Option two</option>
                                        <option>Option three</option>
                                        <option>Option four</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">  
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <label>{{__('attendance.label.site')}}</label>
                                      <select class="form-control">
                                        <option>All</option>
                                        <option>Option one</option>
                                        <option>Option two</option>
                                        <option>Option three</option>
                                        <option>Option four</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">  
                                      
                                      <div class="col-md-3 col-sm-3 col-xs-12">
                                          <label>{{__('attendance.label.department')}}</label>    
                                        <select class="form-control">
                                          <option>All</option>
                                          <option>Option one</option>
                                          <option>Option two</option>
                                          <option>Option three</option>
                                          <option>Option four</option>
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
                                    <th>{{__('attendance.table.no')}}</th>
                                    <th>{{__('attendance.table.name')}}</th>
                                    <th>{{__('attendance.table.company')}}</th>
                                    <th>{{__('attendance.table.site')}}</th>
                                    <th>{{__('attendance.table.department')}}</th>
                                    <th>{{__('attendance.table.hours')}}</th>
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
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
        </div>
        <br />
  
  <!-- /page content -->    
@endsection
