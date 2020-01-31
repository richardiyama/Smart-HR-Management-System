<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function () {
    Route::post('empoyeebvn', 'EmployeesController@addEmployeeBvn');
    Route::get('employee-emergency/{employee_id}', 'EmployeesController@emergencyContact');
    Route::get('edit-employee-emergency/{id}/{name}/{phone}/{employee_id}', 'EmployeesController@editemergencyContact');
    Route::get('delete-employee-emergency/{id}/{employee_id}', 'EmployeesController@deleteemergencyContact');
    Route::post('add-new-emergency-contact', 'EmployeesController@addemergency');
    Route::get('get-employee-company/{company_id}/{check}', 'EmployeesController@getEmployeeforCompany');
    Route::get('search-by-start-date/{fromdate}/{todate}', 'EmployeesController@searchByDate');
    Route::post('uploadTerminatedDocument', 'EmployeesController@updateTerminatedDocument');
    Route::get('deleteTerminatedDocument/{id}/{employee_id}', 'EmployeesController@deleteTerminatedDocument');
    Route::post('terminatecontract', 'EmployeesController@terminateContract');
    Route::post('generatePayroll', 'PayrollController@generate');

    Route::get('get-employee-company1/{company_id}/{check}', 'TerminateController@getEmployeeforCompany1');
    Route::get('search-by-start-date1/{fromdate}/{todate}', 'TerminateController@searchByDate');
    Route::post('approve-termination', 'TerminateController@approveTermination');
    Route::post('approve-bulk-termination', 'TerminateController@approveBulkTermination');
    Route::post('reject-employee-termination', 'TerminateController@rejectTermination');

    Route::post('updateTodayAttendance', 'AttendancesController@updateTodayAttendance');
    Route::get('get-employee-attendance/{company_id}/{check}', 'AttendancesController@getEmployeeforCompany');

    Route::get('get-employee-attendance2/{company_id}/{check}', 'AttendancesController@getEmployeeforCompany2');

    Route::get('getattendancefromdate/{fromdate}/{todate}', 'AttendancesController@getattendancefromdate');

    
    Route::post('getPastattendanceMonth', 'AttendancesController@getPastattendanceMonth');

    Route::post('getPastattendanceDay', 'AttendancesController@getPastattendanceDay');

    

    Route::get('search_payroll/{month}', 'PayrollController@getPayrollHistory');
    Route::post('get_payroll_option', 'PayrollController@getPayrollOption');

   

    Route::post('changePassword', 'SettingController@changePassword');
    Route::post('/submitTransferEmployees', 'EmployeesController@submitTransferEmployees');

    Route::post('/confirmNewEmployee', 'EmployeesController@confirmNewEmployee');
    Route::post('/rejectNewEmployee', 'EmployeesController@rejectNewEmployee');

    Route::post('addPublicHoliday', 'PayrollController@addPublicHoliday');
    Route::get('removePubDate/{id}', 'PayrollController@removePubDate');
    Route::get('getPublicHolidays', 'PayrollController@getPublicHolidays');
    Route::get('getMonthPendningNew', 'EmployeesController@getNewEmpMonth');

    Route::post('UpdateSettings', 'SettingController@UpdateSettings');

    Route::post('ApprovePayroll', 'PayrollController@ApprovePayroll');

    Route::get('getEmployeeAsPerSite/{site_id}', 'DashboardController@getEmployeeAsPerSite');

    Route::post('changeLumpsum', 'DashboardController@changeLumpsum');

    Route::get('getChangeLumpsumByDates/{fromdate}/{todate}/{check}', 'DashboardController@getChangeLumpsumByDates');

    Route::post('updateLumpsum', 'DashboardController@updateLumpsum');

    Route::get('getthelumpsum/{check}', 'DashboardController@getthelumpsum');

});


