<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@index');

Route::get('employees/create', 'EmployeesController@create');
Route::post('employees/create/personalDetails', 'PersonalDetailsController@store');
Route::post('employees/company', 'CompanyDetailsController@store');
Route::post('employees/salary', 'EmployeesController@salary');
Route::post('employees/nextKin', 'EmployeesController@nextkin');

Route::get('attendance', 'AttendancesController@index');


//Route::get('payroll/index', 'PagesController@payroll');





Auth::routes();
//Route::resource('Employee', 'EmployeesController');
Route::resource('personalDetail', 'PersonalDetailsController');
Route::resource('CompanyDetail', 'CompanyDetailsController');
Route::resource('company','CompanyController');

Route::resource('site','SiteController');
Route::resource('department','DepartmentController');

Route::resource('pre_employment','PreEmployementController');

Route::group(['middleware' => 'auth'], function () {

    Route::get('dashboard', 'DashboardController@index');
    Route::get('settings', 'SettingController@index');

    Route::get('attendance', 'AttendancesController@index');
    Route::get('change-in-lumpsum', 'DashboardController@changeInLumpsum');

    Route::post('addEmployeeBvn', 'EmployeesController@addEmployeeBvn');

    Route::get('index', 'EmployeesController@index');
    Route::get('employee/{employee_id}/update', 'EmployeesController@updateemployee');

    Route::get('employee/{employee_id}/edit', 'EmployeesController@editemployee');
    Route::get('employee/{employee_id}/work', 'EmployeesController@employeeworkview');
    Route::get('employee/{employee_id}/salary', 'EmployeesController@employeesalaryview');
    Route::get('employee/{employee_id}/emergency', 'EmployeesController@employeeemergencyview');
    Route::get('employee/{employee_id}/terminate', 'EmployeesController@employeeterminatedview');

    Route::get('company','CompanyController@index');
    Route::get('create','CompanyController@create');
    Route::get('company/delete/{Company}', ['as' => 'Company.delete', 'uses' => 'CompanyController@destroy']);
    
    Route::get('site','SiteController@index');
    Route::get('create','SiteController@create');
    Route::get('site/delete/{Site}', ['as' => 'Site.delete', 'uses' => 'SiteController@destroy']);
    
    Route::get('department','DepartmentController@index');
    Route::get('create','DepartmentController@create');
    Route::get('department/delete/{Department}', ['as' => 'Department.delete', 'uses' => 'DepartmentController@destroy']);

    Route::get('approvedPre_employment_request','PreEmployementController@approvedPre_employment_request');
    Route::get('pre_employment','PreEmployementController@index');
    Route::get('hr_manager_approval','PreEmployementController@hr_manager_approval');
    Route::get('create','PreEmployementController@create');
    
    Route::get('addPreEmployementCodeView','PreEmployementController@addPreEmployementCodeView');

    Route::get('blacklisted_employee_approval_view','EmployeesController@blacklisted_employee_approval_view');
    Route::get('pre_employment_hr_manager_approval/{id}', 'PreEmployementController@pre_employment_hr_manager_approval');
    Route::get('pre_employment_project_manager_approval/{id}', 'PreEmployementController@pre_employment_project_manager_approval');


    Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware', 'auth'], function () {
        Route::get('users', 'UsersController@index');
        Route::get('users/roles', 'RolesController@index');
        Route::get('users/create', 'UsersController@create');
        Route::get('roles/edit/{id}', 'RolesController@edit');
        Route::post('update1', 'RolesController@update');
        // Route::get('attendance', 'AttendancesController@index');


        Route::resource('Role', 'RolesController');
        Route::resource('UserRole', 'UsersController');
        Route::get('Role/delete/{Role}', ['as' => 'Role.delete', 'uses' => 'RolesController@destroy']);
        Route::get('User/delete/{User}', ['as' => 'User.delete', 'uses' => 'UsersController@destroy']);
    });


    Route::group(['middleware' => 'App\Http\Middleware\SiteOfficer', 'auth'], function () {
        //  Route::get('attendance', 'AttendancesController@index');
        //Route::get('attendance', 'AttendancesController@index');
    });




    Route::group(['middleware' => ['App\Http\Middleware\HrManager', 'auth']], function () {

        Route::get('employees/pending', 'EmployeesController@getPending')->name('pending');

        Route::post('recall-termination', 'TerminateController@recallTermination');


        Route::get('past-attendance', 'AttendancesController@PastAttendance');

        Route::get('attendance/report', 'AttendancesController@attendancerecord');




        Route::get('employee-detail/{id}', 'EmployeesController@employeeDetails');
        Route::post('add_employee_detail', 'EmployeesController@add_employee_detail');
        Route::post('send_blacklisted_approval', 'EmployeesController@send_blacklisted_approval');
        Route::post('add_comments', 'EmployeesController@add_comments');
        Route::get('blacklisted_employee_approval/{id}', 'EmployeesController@blacklisted_employee_approval');


       

        Route::get('company_position_details/{id}', 'EmployeesController@add_employee_position');
        Route::post('add_employee_company_position', 'EmployeesController@addWorkshedule');
        Route::post('add_employee_salary', 'EmployeesController@add_employee_salary');
        Route::post('add_employee_emergency', 'EmployeesController@add_employee_emergency');
        Route::get('employee_designation_change_history/{employee_id}', 'EmployeesController@employee_designation_change_history');

        Route::get('employee_salary_increment/{employee_id}', 'DashboardController@employee_salary_increment');

        Route::get('employee_transfer_history/{employee_id}', 'EmployeesController@employee_transfer_history');

        Route::post('add_employee_support_document', 'EmployeesController@add_employee_support_document');

        Route::post('add_employee_support_document1', 'EmployeesController@add_employee_support_document1');

        Route::get('delete-employee-file/{id}/{employee_id}', 'EmployeesController@deleteEmployeeDocuments');

        Route::get('delete-employee-file1/{id}/{employee_id}', 'EmployeesController@deleteEmployeeDocuments1');




        Route::get('employee/{employee_id}/approve', 'EmployeesController@approveemployee');

        

        Route::post('update-employee1', 'EmployeesController@updateEmployee1');


        Route::get('employees/terminate', 'TerminateController@index');

        Route::get('terminated-employee/{id}/view', 'TerminateController@editemployee');

        Route::get('employees/pending-termination', 'TerminateController@pendingTermination')->name('pending-termination');
    });





    Route::group(['middleware' => 'App\Http\Middleware\Finance', 'auth'], function () {

        Route::get('payroll', 'PayrollController@index')->name('payroll');
        Route::get('payroll-approval', 'PayrollController@payrollApproval')->name('payroll-approval');

        Route::get('payroll/history', 'PayrollController@payrollHistory');

        Route::get('payroll/report', 'PayrollController@payrollReport');
    });
});
