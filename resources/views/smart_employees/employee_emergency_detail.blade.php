@extends('layouts.dash')

@section('content')
<div class="row">
    <div class="col-md-2 col-xs-12" ></div>
    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
        <a href="{{url('employees')}}"><span class="fa fa-arrow-left" style="color:green"></span>   &nbsp; Back to active employee</a>
          <div class="x_title">
            <h2>Add New Employee <br> <small>Fill in the detail of the employee below then send it for approval</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />

            @if(isset($emergencies))
              <table class="table table-striped">
                <thead>
                  <th>Contact Name</th>
                  <th>Contact Phone</th>
                </thead>
                <tbody>
                  @foreach($emergencies as $item)

                    <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->phone}}</td>
                    </tr>

                  @endforeach
                </tbody>
              </table>
              @endif

          <form class="form-horizontal form-label-left" method="post" action="{{url('add_employee_emergency')}}">
             {{ csrf_field() }}
                <strong>Emergency Contact</strong> <hr>

            <input type="hidden" value="{{$id}}" name="employee_id">
            <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
              @if (isset($success))
          <div class="alert alert-success">{{$success}}</div>
              @endif
    
             
            
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Emergency Contact 1</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" required class="form-control" placeholder="Enter emergency contact 1 name" name="name">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Emergency Contact 1 Phone Number</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" required class="form-control" placeholder="Enter emergency contact 1 phone number" name="phone">
                  </div>
                </div>
              </div>

                <button type="submit" class="btn btn-success btn-sm">Add another emergency contact</button>
            </form>

            <strong>Supporting document</strong> <hr>

            @if(isset($documents))

              <div class="row">
                @foreach ($documents as $item)

                <div class="col-md-55">
                    <div class="thumbnail">
                      <div class="image view view-first">
                      <img style="width: 100%; display: block;" src="{{asset('storage/assets')}}/{{$item->file}}" alt="image" />
                        <div class="mask">
                        <p>{{$item->title}}</p>
                          <div class="tools tools-bottom">
                           <!-- <a href="#"><i class="fa fa-link"></i></a>
                            <a href="#"><i class="fa fa-pencil"></i></a>
                            <a href="#"><i class="fa fa-times"></i></a> -->
                            <a title="view" target="_blank" href="{{asset('storage/assets')}}/{{$item->file}}"><i class="fa fa-pencil"></i></a>
                          </div>
                        </div>
                      </div>
                      <div class="caption">
                      <p><a title="Click for large view" href="{{asset('storage/assets')}}/{{$item->file}}">View {{$item->title}}</a></p>
                      </div>
                    </div>
                  </div>
                    
                @endforeach
              </div>

            @endif

            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="{{url('add_employee_support_document')}}">
              {{ csrf_field() }}
                 <strong>Upload Support Documents</strong> <hr>
 
             <input type="hidden" value="{{$id}}" name="employee_id">
             <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
     
             
             
               <div class="form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12">Select employee document to upload</label>
                 <div class="col-md-9 col-sm-9 col-xs-12">
                   <div class="col-md-9 col-sm-9 col-xs-12">
                     <input type="file" required class="form-control" placeholder="" name="file">
                   </div>
                 </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Document title</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" required class="form-control" placeholder="Document title" name="title">
                    </div>
                  </div>
               </div>

 
                 <button type="submit" class="btn btn-success btn-sm pull-right">Upload</button>
             </form>
          </div>
          <br>
          <br>
        <a href="#" data-backdrop="static"
        data-keyboard="false" class="btn btn-success btn-lg" data-toggle="modal" data-target="#myModal">Add New Employee</a>
        </div>
      </div>
</div>
@if (isset($success))
<div class="alert alert-success">{{$success}}</div>
    @endif






<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
    <Bvnnewemployee :user_id="'{{Auth::user()->id}}'"></Bvnnewemployee>
  
    </div>
  </div>
    
@endsection