 <!-- sidebar menu -->
 <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
     <div class="menu_section">
         <h3>General</h3>
         <ul class="nav side-menu">
             <li><a href="{{url('dashboard')}}"><i class="fa fa-home"></i> {{__('dashboard.menu.home')}}</a></li>

           
             <li><a><i class="fa fa-building"></i> Pre-Employement <span class="fa fa-chevron-down"></span></a>
                 <ul class="nav child_menu">
                     @if(Auth::user()->role != 13 && Auth::user()->role !=3 )
                     <li><a href="{{url('create')}}">pre_employment</a></li>
                     @endif

                     @if(Auth::user()->role == 13 || Auth::user()->role == 1 )
                     <li><a href="{{url('pre_employment')}}">Project manager approval</a></li>
                     @endif


                     @if(Auth::user()->role == 3 || Auth::user()->role == 1 )
                     <li><a href="{{url('hr_manager_approval')}}">Hr manager approval</a></li>
                     @endif
                 </ul>
             </li>

             
             <li><a><i class="fa fa-users"></i> {{__('dashboard.menu.employee')}} <span
                         class="fa fa-chevron-down"></span></a>
                 <ul class="nav child_menu">
                     <li><a href="{{url('addPreEmployementCodeView')}}">Add New Employee</a></li>

                     <li>
                         <a href="{{route('pending')}}">Pending Employees</a>
                     </li>
                     <li><a href="{{url('change-in-lumpsum')}}">Change in Lumpsum</a></li>
                     <li><a href="{{url('employees')}}/terminate">{{__('dashboard.menu.terminated_emp')}}</a></li>
                     <li>
                         <a href="{{route('pending-termination')}}">Pending Termination</a>
                     </li>
                     <li><a href="{{url('blacklisted_employee_approval_view')}}">Blacklisted</a></li>
                 </ul>
             </li>

            

             <li><a><i class="fa fa-edit"></i> {{__('dashboard.menu.attendance')}} <span
                         class="fa fa-chevron-down"></span></a>
                 <ul class="nav child_menu">
                     <li><a href="{{url('attendance')}}">{{__('dashboard.menu.today_attendance')}}</a></li>
                     <li><a href="{{url('past-attendance')}}">{{__('dashboard.menu.past_attendance')}}</a></li>
                     <li><a href="{{url('attendance')}}/report">{{__('dashboard.menu.attendance_report')}}</a></li>
                 </ul>
             </li>
             <li><a><i class="fa fa-money"></i> {{__('dashboard.menu.payroll')}} <span
                         class="fa fa-chevron-down"></span></a>
                 <ul class="nav child_menu">
                     <li><a href="{{route('payroll')}}">{{__('dashboard.menu.generate_payroll')}}</a></li>

                     <!--<li><a href="{{route('payroll-approval')}}">Payroll Approval</a></li> -->

                     <li><a href="{{url('payroll')}}/history">Payroll history</a></li>
                     <li><a href="{{url('payroll')}}/report">{{__('dashboard.menu.payroll_report')}}</a></li>
                 </ul>
             </li>
             <li><a><i class="fa fa-user-plus"></i>{{__('dashboard.menu.user_role')}}<span
                         class="fa fa-chevron-down"></span></a>
                 <ul class="nav child_menu">
                     <li><a href="{{url('users')}}">{{__('dashboard.menu.users')}}</a></li>
                     <li><a href="{{url('users')}}/roles">{{__('dashboard.menu.role')}}</a></li>
                 </ul>
             </li>
             <li><a><i class="fa fa-cog fa-fw"></i> {{__('dashboard.menu.setting')}}<span
                         class="fa fa-chevron-down"></span></a>
                 @if(Auth::user()->role == 3 || Auth::user()->role==2 || Auth::user()->role==1 )
             
                 <ul class="nav child_menu">
                 <li><a href="{{url('settings')}}">general settings</a></li>
                     <li><a href="{{url('company')}}">companies</a></li>
                     <li><a href="{{url('site')}}">sites</a></li>
                     <li><a href="{{url('department')}}">departments</a></li>
                 </ul>
                 @endif
             </li>
            
         </ul>
     </div>
 </div>
 <!-- /sidebar menu -->