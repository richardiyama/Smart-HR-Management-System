<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\EmailNotify;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;

class Payroll
{
    public $sno;
    public $name;
    public $Position;
    public $Age;
    public $StartDate;
    public $Salary;
    public $department;
    public $site;
    public $id;
    public $company;
    public $employee_no;
    public $lumpsum;
    public $overtime;
    public $day_worked;
    public $company_id;
    public $department_id;
    public $site_id;
    public $bank;
    public $account_number;
}

class Salary
{
    public $employee_id;
    public $name;
    public $amount;
    public $lumpsum; //amount + overtime;
    public $month;
    public $overtime;
    public $absent;
    public $present;
    public $day_cost;
    public $company;
    public $site;
    public $department;
    public $position;
    public $age;
    public $start_date;
    public $emp_no;
    public $bank;
    public $account_no;
    public $gross;
    public $paye;
    public $pension;
    public $netpay;
    public $day_worked;
    public $no_of_day;
    public $basic;
}

class Payrollyear
{
    public $name;
    public $jan;
    public $feb;
    public $mar;
    public $apr;
    public $may;
    public $jun;
    public $jul;
    public $aug;
    public $sep;
    public $oct;
    public $nov;
    public $dec;
    public $company_id;
    public $site_id;
    public $department_id;
}

class PayrollApproval
{
    public $approved_by;
    public $approved_time;
    public $remarks;
    public $approve;
    public $total;
    public $payroll_generated_id;
}


class PayrollController extends Controller
{
    public function ApprovePayroll(Request $request)
    {

        //check if user is a finance:
        $user = DB::table('users')->where('id', $request->user_id)->first();
        $name = "";
        if ($user) {
            if ($user->role == 1 || $user->role == 6) { //admin or finance
                $lumpsum = DB::table('payrolls')->where('generate_payroll_id', $request->payroll_id)->sum('lumpsum');

                $name = $user->name;

                $overtime = DB::table('payrolls')->where('generate_payroll_id', $request->payroll_id)->sum('overtime');

                $the_total = $lumpsum + $overtime;

                DB::table('generated_payroll')->where('id', $request->payroll_id)->update([
                    'approve' => 1,
                    'remarks' => $request->remarks,
                    'approved_by' => $request->user_id,
                    'approved_datetime' => date('Y-m-d : H:m:s'),
                    'total' => $the_total
                ]);
                $thepay = DB::table('generated_payroll')->where('id', $request->payroll_id)->first();
                $user = DB::table('users')->where('id', $request->user_id)->first();
                $email = new EmailNotify();
                $email->email = $user->email;
                $email->user = $user->name;
                $email->confirmation = 1;
                $email->subject = "Payroll Approval Notification for " . $thepay->month_year;
                $email->body = "This is to notify you of payroll approval for the year-month of " . $thepay->month_year . ', Total amount: ' . number_format($the_total, 2);
                $date = date('Y-m-d H:m:s');
                $carbon_date = Carbon::parse($date);
                SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));


                return response()->json([
                    'data' => 1,
                    'approved_by' => $name,
                    'approved_time' => date('Y-m-d H:m:s'),
                    'remarks' => $request->remarks,
                    'total' => $the_total,
                    'approve' => 1
                ]);
            } else {
                //do not have priviledges
                return response()->json([
                    'data' => 2
                ]);
            }
        } else {
            return response()->json([
                'data' => 3
            ]);
        }
    }
    public function getNetpay($gross, $paye, $pension, $loan = 0, $absent)
    {
        $total_net =  $gross - $paye - $loan - $absent - $pension;
        return number_format($total_net, 2);
    }
    public function getAbsent($lumpsum, $day_work, $no_of_days)
    {
        $total = ($lumpsum / $no_of_days) * $day_work;
        $absent = -$total + $lumpsum;
        return $absent;
    }
    public function paye($lumpsum, $day_work, $no_of_days)
    {
        /*return ((+if ( X3 > 3200000, 560000 + (X3 - 3200000) * 24 %, if (X3 > 1600000, 224000 + (X3 - 1600000) * 21 %, if (X3 > 1100000, 129000 + (X3 - 1100000) * 19 %, if (X3 > 600000, 54000 + (X3 - 600000) * 15 %, if (X3 > 300000, 21000 + (X3 - 300000) * 11 %, if (X3 < 300000, (X3 * 7 %)))))))) / 12) / AM3 * AF3
        */
        $taxable_income = $this->TaxableIncome($lumpsum, $day_work, $no_of_days);

        $payme = 0;
        if ($taxable_income > 3200000) {
            $payme = 560000 + (($taxable_income - 3200000) * 0.24);
        } else if ($taxable_income > 1600000) {
            $payme = 224000 + (($taxable_income - 1600000) * 0.21);
        } else if ($taxable_income > 1100000) {
            $payme = 129000 + (($taxable_income - 1100000) * 0.19);
        } else if ($taxable_income > 600000) {
            $payme = 54000 + (($taxable_income - 600000) * 0.15);
        } else if ($taxable_income > 300000) {
            $payme = 21000 + (($taxable_income - 300000) * 0.11);
        } else {
            $payme = $taxable_income * 0.07;
        }

        $monthly_paye = ($payme / 12) / $no_of_days;
        return $monthly_paye * $day_work;
    }

    public function TaxableIncome($lumpsum, $day_work, $no_of_days)
    {
        $annual_gross = $this->AnnualGross($lumpsum);
        $relief = $this->Relief($annual_gross);
        $pension = $this->Pension($lumpsum, $day_work, $no_of_days);
        return $annual_gross - (($pension * 12) + $relief);
    }
    public function Pension($lumpsum, $day_work, $no_of_days)
    {
        $setting = DB::table('settings_global')->first();
        $pen_percentage = 0.08;
        if ($setting) {
            $pen_percentage = $setting->pension / 100;
        }
        $basic = $lumpsum * 0.25;
        $housing = $lumpsum * 0.20;
        $transport = $lumpsum * 0.225;
        $total = $basic + $housing + $transport;
        $total_per = $total * $pen_percentage / $no_of_days;
        $pension =  $total_per * $day_work;
        return $pension;
    }
    public function Relief($annual_gross)
    {
        $check_annual = ($annual_gross * 0.2) + 200000;

        if ($check_annual > ($annual_gross * 0.21)) {
            return 200000 + ($annual_gross * 0.20);
        } else {
            return $annual_gross * 0.21;
        }
    }
    public function AnnualGross($lumpsum)
    {
        $basic = $lumpsum * 0.25;
        $housing = $lumpsum * 0.20;
        $transport = $lumpsum * 0.225;
        $medical = $lumpsum * 0.15;
        $meal_subsidy = $lumpsum * 0.175;
        $total = $basic + $housing + $transport + $medical + $meal_subsidy;
        $annual =  $total * 12;
        return $annual;
    }
    public function CalculateGross($lumpsum, $overtime, $bonus = 0)
    {
        $basic = $lumpsum * 0.25;
        $housing = $lumpsum * 0.20;
        $transport = $lumpsum * 0.225;
        $medical = $lumpsum * 0.15;
        $meal_subsidy = $lumpsum * 0.175;
        //ordinary overtime, 
        //sunday overtime, saturday overtime and public overtime= overtime
        $overtime = $overtime;
        $bonus = $bonus;
        $gross = $basic + $housing + $transport + $medical + $meal_subsidy + $overtime + $bonus;
        return $gross;
    }
    public function getPayrollOption(Request $request)
    {
        $year = $request->year;
        $setting = DB::table('settings_global')->first();
        $pension = $setting->pension / 100;
        $tax = $setting->tax / 100;
        $payroll_year = DB::table('payrolls')->where('year_int', $year)->groupby('employee_id')->get();
        $the_payroll = array();
        switch ($request->option) {
            case 1:
                if ($payroll_year) {
                    foreach ($payroll_year as $item2) {
                        $pay = new Payrollyear();
                        $employee = DB::table('employee_personal_details')->where('employee_id', $item2->employee_id)->first();
                        if ($employee) {
                            $pay->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                        }
                        //jan
                        for ($i = 1; $i <= 12; $i++) {
                            $item = DB::table('payrolls')->where([
                                'month_int' => $i,
                                'year_int' => $year,
                                'employee_id' => $item2->employee_id
                            ])->first();

                            switch ($i) {
                                case 1:
                                    if ($item) {

                                        $pay->jan = number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 2:
                                    if ($item) {
                                        $pay->feb = number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 3:
                                    if ($item) {
                                        $pay->mar =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 4:
                                    if ($item) {
                                        $pay->apr =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 5:
                                    if ($item) {
                                        $pay->may =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 6:
                                    if ($item) {
                                        $pay->jun =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 7:
                                    if ($item) {
                                        $pay->jul =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 8:
                                    if ($item) {
                                        $pay->aug =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 9:
                                    if ($item) {
                                        $pay->sep =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 10:
                                    if ($item) {
                                        $pay->oct =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 11:
                                    if ($item) {
                                        $pay->nov =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                                case 12:
                                    if ($item) {
                                        $pay->dec =  number_format($this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus), 2);
                                    }
                                    break;
                            }
                        }


                        $work = DB::table('employee_work_shedules')->where('employee_id', $item2->employee_id)->first();
                        if ($work) {

                            $pay->department_id = $work->department;
                            $pay->site_id = $work->site;
                            $pay->company_id = $work->company;
                        }

                        $the_payroll[] = $pay;
                    }
                }
                break;

            case 2: // net income
                if ($payroll_year) {
                    foreach ($payroll_year as $item2) {
                        $pay = new Payrollyear();
                        $employee = DB::table('employee_personal_details')->where('employee_id', $item2->employee_id)->first();
                        if ($employee) {
                            $pay->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                        }
                        //jan
                        for ($i = 1; $i <= 12; $i++) {
                            $item = DB::table('payrolls')->where([
                                'month_int' => $i,
                                'year_int' => $year,
                                'employee_id' => $item2->employee_id
                            ])->first();

                            switch ($i) {
                                case 1:
                                    if ($item) {

                                        $pay->jan = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->jan = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 2:
                                    if ($item) {
                                        $pay->feb = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->feb = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 3:
                                    if ($item) {
                                        $pay->mar = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->mar = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 4:
                                    if ($item) {
                                        $pay->apr = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->apr = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 5:
                                    if ($item) {
                                        $pay->may = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->may = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 6:
                                    if ($item) {
                                        // $annual_gross = $this->AnnualGross($item->lumpsum);
                                        // $pay->jun = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus); // correct
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->jun = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 7:
                                    if ($item) {
                                        // $annual_gross = $this->AnnualGross($item->lumpsum);
                                        // $pay->jul = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->jul = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 8:
                                    if ($item) {
                                        $pay->aug = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->aug = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 9:
                                    if ($item) {
                                        $pay->sep = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->sep = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 10:
                                    if ($item) {
                                        $pay->oct = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->oct = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 11:
                                    if ($item) {
                                        $pay->nov = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->nov = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                                case 12:
                                    if ($item) {
                                        $pay->dec = 0;
                                        $gross = $this->CalculateGross($item->lumpsum, $item->overtime, $item->bonus);
                                        $paye = $this->paye($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $pension2 = $this->Pension($item->lumpsum, $item->workdays, $item->no_of_days);
                                        $absent = $this->getAbsent($item->lumpsum, $item->workdays, $item->no_of_days);

                                        $pay->dec = $this->getNetpay($gross, $paye, $pension2, $item->loan, $absent);
                                    }
                                    break;
                            }
                        }


                        $work = DB::table('employee_work_shedules')->where('employee_id', $item2->employee_id)->first();
                        if ($work) {

                            $pay->department_id = $work->department;
                            $pay->site_id = $work->site;
                            $pay->company_id = $work->company;
                        }

                        $the_payroll[] = $pay;
                    }
                }
                break;

                // case 3 total hours
            case 3: //hour
                if ($payroll_year) {
                    foreach ($payroll_year as $item2) {
                        $pay = new Payrollyear();
                        $employee = DB::table('employee_personal_details')->where('employee_id', $item2->employee_id)->first();
                        if ($employee) {
                            $pay->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                        }
                        //jan
                        for ($i = 1; $i <= 12; $i++) {
                            $item = DB::table('payrolls')->where([
                                'month_int' => $i,
                                'year_int' => $year,
                                'employee_id' => $item2->employee_id
                            ])->first();
                            switch ($i) {
                                case 1:
                                    if ($item) {
                                        $pay->jan = number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 2:
                                    if ($item) {
                                        $pay->feb =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 3:
                                    if ($item) {
                                        $pay->mar =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 4:
                                    if ($item) {
                                        $pay->apr =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 5:
                                    if ($item) {
                                        $pay->may =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 6:
                                    if ($item) {
                                        $pay->jun =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 7:
                                    if ($item) {
                                        $pay->jul =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 8:
                                    if ($item) {
                                        $pay->aug =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 9:
                                    if ($item) {
                                        $pay->sep =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 10:
                                    if ($item) {
                                        $pay->oct =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 11:
                                    if ($item) {
                                        $pay->nov =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                                case 12:
                                    if ($item) {
                                        $pay->dec =  number_format($item->total_hour, 2) . ' Hrs';
                                    }
                                    break;
                            }
                        }


                        $work = DB::table('employee_work_shedules')->where('employee_id', $item2->employee_id)->first();
                        if ($work) {

                            $pay->department_id = $work->department;
                            $pay->site_id = $work->site;
                            $pay->company_id = $work->company;
                        }

                        $the_payroll[] = $pay;
                    }
                }
                break;

            case 4:  // overtime
                if ($payroll_year) {
                    foreach ($payroll_year as $item2) {
                        $pay = new Payrollyear();
                        $employee = DB::table('employee_personal_details')->where('employee_id', $item2->employee_id)->first();
                        if ($employee) {
                            $pay->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                        }
                        //jan
                        for ($i = 1; $i <= 12; $i++) {
                            $item = DB::table('payrolls')->where([
                                'month_int' => $i,
                                'year_int' => $year,
                                'employee_id' => $item2->employee_id
                            ])->first();
                            switch ($i) {
                                case 1:
                                    if ($item) {
                                        $pay->jan = $item->overtime;
                                    }
                                    break;
                                case 2:
                                    if ($item) {
                                        $pay->feb =  $item->overtime;
                                    }
                                    break;
                                case 3:
                                    if ($item) {
                                        $pay->mar =  $item->overtime;
                                    }
                                    break;
                                case 4:
                                    if ($item) {
                                        $pay->apr =  $item->overtime;
                                    }
                                    break;
                                case 5:
                                    if ($item) {
                                        $pay->may =  $item->overtime;
                                    }
                                    break;
                                case 6:
                                    if ($item) {
                                        $pay->jun =  $item->overtime;
                                    }
                                    break;
                                case 7:
                                    if ($item) {
                                        $pay->jul =  $item->overtime;
                                    }
                                    break;
                                case 8:
                                    if ($item) {
                                        $pay->aug = $item->overtime;
                                    }
                                    break;
                                case 9:
                                    if ($item) {
                                        $pay->sep =  $item->overtime;
                                    }
                                    break;
                                case 10:
                                    if ($item) {
                                        $pay->oct =  $item->overtime;
                                    }
                                    break;
                                case 11:
                                    if ($item) {
                                        $pay->nov =  $item->overtime;
                                    }
                                    break;
                                case 12:
                                    if ($item) {
                                        $pay->dec =  $item->overtime;
                                    }
                                    break;
                            }
                        }


                        $work = DB::table('employee_work_shedules')->where('employee_id', $item2->employee_id)->first();
                        if ($work) {

                            $pay->department_id = $work->department;
                            $pay->site_id = $work->site;
                            $pay->company_id = $work->company;
                        }

                        $the_payroll[] = $pay;
                    }
                }
                break;

            case 7:  // pension
                if ($payroll_year) {
                    foreach ($payroll_year as $item2) {
                        $pay = new Payrollyear();
                        $employee = DB::table('employee_personal_details')->where('employee_id', $item2->employee_id)->first();
                        if ($employee) {
                            $pay->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                        }
                        //jan
                        for ($i = 1; $i <= 12; $i++) {
                            $item = DB::table('payrolls')->where([
                                'month_int' => $i,
                                'year_int' => $year,
                                'employee_id' => $item2->employee_id
                            ])->first();
                            switch ($i) {
                                case 1:
                                    if ($item) {
                                        $pay->jan = number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 2:
                                    if ($item) {
                                        $pay->feb =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 3:
                                    if ($item) {
                                        $pay->mar =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 4:
                                    if ($item) {
                                        $pay->apr = number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 5:
                                    if ($item) {
                                        $pay->may =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 6:
                                    if ($item) {
                                        $pay->jun =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 7:
                                    if ($item) {
                                        $pay->jul =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 8:
                                    if ($item) {
                                        $pay->aug =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 9:
                                    if ($item) {
                                        $pay->sep =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 10:
                                    if ($item) {
                                        $pay->oct =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 11:
                                    if ($item) {
                                        $pay->nov =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 12:
                                    if ($item) {
                                        $pay->dec =  number_format($this->Pension($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                            }
                        }


                        $work = DB::table('employee_work_shedules')->where('employee_id', $item2->employee_id)->first();
                        if ($work) {

                            $pay->department_id = $work->department;
                            $pay->site_id = $work->site;
                            $pay->company_id = $work->company;
                        }

                        $the_payroll[] = $pay;
                    }
                }
                break;

            case 8:  // tax
                if ($payroll_year) {
                    foreach ($payroll_year as $item2) {
                        $pay = new Payrollyear();
                        $employee = DB::table('employee_personal_details')->where('employee_id', $item2->employee_id)->first();
                        if ($employee) {
                            $pay->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                        }
                        //jan
                        for ($i = 1; $i <= 12; $i++) {
                            $item = DB::table('payrolls')->where([
                                'month_int' => $i,
                                'year_int' => $year,
                                'employee_id' => $item2->employee_id
                            ])->first();
                            switch ($i) {
                                case 1:
                                    if ($item) {
                                        $pay->jan = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 2:
                                    if ($item) {
                                        $pay->feb = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 3:
                                    if ($item) {
                                        $pay->mar = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 4:
                                    if ($item) {
                                        $pay->apr = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 5:
                                    if ($item) {
                                        $pay->may = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 6:
                                    if ($item) {
                                        $pay->jun = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 7:
                                    if ($item) {
                                        $pay->jul = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 8:
                                    if ($item) {
                                        $pay->aug = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 9:
                                    if ($item) {
                                        $pay->sep = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 10:
                                    if ($item) {
                                        $pay->oct = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 11:
                                    if ($item) {
                                        $pay->nov = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                                case 12:
                                    if ($item) {
                                        $pay->dec = number_format($this->paye($item->lumpsum, $item->workdays, $item->no_of_days), 2);
                                    }
                                    break;
                            }
                        }


                        $work = DB::table('employee_work_shedules')->where('employee_id', $item2->employee_id)->first();
                        if ($work) {

                            $pay->department_id = $work->department;
                            $pay->site_id = $work->site;
                            $pay->company_id = $work->company;
                        }

                        $the_payroll[] = $pay;
                    }
                }
                break;
        }
        return response()->json([
            'employees' => $the_payroll,
            'payroll_year' => $payroll_year,
        ]);
    }
    public function payrollReport()
    {
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('payroll2.payroll_report', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments]);
    }
    public function getPayrollHistory($month)
    {
        //$month = date('n');
        $emp = DB::table('payrolls')->where('month', $month)->get();
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Payroll();
                $employee->sno = $count;
                $employee->employee_id = $value->employee_id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;

                    $employee->age = date_diff(date_create($personal->date_of_birth), date_create('now'))->y;
                }
                $company = DB::table('companies')->where('id', $value->company_id)->first();
                if ($company) {
                    $employee->company = $company->name;
                }
                $site = DB::table('sites')->where('id', $value->site_id)->first();
                if ($site) {
                    $employee->site = $site->name;
                    $employee->site_id = $site->id;
                }

                $department = DB::table('departments')->where('id', $value->department_id)->first();
                if ($department) {
                    $employee->department = $department->name;
                }

                $position = DB::table('positions')->where('id', $value->position_id)->first();


                if ($position) {
                    $employee->position = $position->name;
                }

                $salary_detail = DB::table('employee_salary_details')->where('employee_id', $value->employee_id)->first();
                if ($salary_detail) {
                    $bank = DB::table('banks')->where('id', $salary_detail->bank)->first();
                    if ($bank) {
                        $employee->bank = $bank->name;
                    }
                    $employee->account_number = $salary_detail->account_number;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $employee->employee_no = $work->empno;
                    $employee->StartDate = $work->start_date;
                }
                $employee->overtime = $value->overtime;
                $employee->day_worked = $value->workdays;
                $employee->lumpsum = $value->lumpsum;

                $employee->company_id = $value->company_id;
                $employee->department_id = $value->department_id;
                $employee->company_id = $value->company_id;

                $the_employee[] = $employee;
            }
        }
        return response()->json([
            'employees' => $the_employee
        ]);
        // return view('payroll2.payroll_history', ['employees' => $the_employee]);

    }
    public function payrollHistory()
    {
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('payroll2.payroll_history', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments]);
    }
    public function generate(Request $request)
    {
        $date = $request->month . "-01";
        $selected_month = date('n', strtotime($request->month));
        $selected_year = date('Y', strtotime($request->month));

        $generate_payroll_id = 0;

        //check of the payroll has been approved.
        $check_payroll = DB::table('generated_payroll')->whereMonth('date', $selected_month)->whereYear('date', $selected_year)->where('approve', 1)->first();
        if ($check_payroll) {
            $user = DB::table('users')->where('id', $check_payroll->approved_by)->first();
            $name = "";
            if ($user) {
                $name = $user->name;
            }
            return response()->json([
                'data' => 3, //payroll has been approved.
                'approved_by' => $name,
                'approved_time' => $check_payroll->approved_datetime,
                'remarks' => $check_payroll->remarks,
                'total' => $check_payroll->total,
                'approve' => $check_payroll->approve
            ]);
        }
        //check if the payroll has been generated before...
        $emp1 = DB::table('payrolls')->whereMonth('date', $selected_month)->whereYear('date', $selected_year)->count();

        if ($emp1 > 0) {
            //delete to regenerate...
            $emp1 = DB::table('payrolls')->whereMonth('date', $selected_month)->whereYear('date', $selected_year)->delete();
        }

        $employees = DB::table('employee_bvn')->join('attendance_records', 'attendance_records.employee_id', 'employee_bvn.id')->join('attendances', 'attendances.id', 'attendance_records.attendance_id')->whereMonth('attendances.date', $selected_month)->whereYear('attendances.date', $selected_year)->select('employee_bvn.id')->groupby('employee_bvn.id')->get();

        /*return response()->json([
            'employees' => $employees,
        ]);*/



        if ($employees && count($employees) > 0) {

            //create 
            $generate_payroll_id =  DB::table('generated_payroll')->insertGetId([
                'month_year' => $request->month,
                'date' => $date,
                'month' => $selected_month,
                'year' => $selected_year,
                'generated_by' => $request->user_id,
            ]);

            foreach ($employees as $emp) {
                $salary = new Salary();
                $salary->employee_id = $emp->id;
                $employee = DB::table('employee_personal_details')->where('employee_id', $emp->id)->first();

                $employee_salary = DB::table('employee_salary_details')->where('employee_id', $emp->id)->first();

                if ($employee) {
                    $salary->name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                }


                $employee_work = DB::table('employee_work_shedules')->where('employee_id', $emp->id)->first();

                //get work details
                if ($employee_work) {
                    $salary->company = $employee_work->company;
                    $salary->site = $employee_work->site;
                    $salary->department = $employee_work->department;
                    $salary->position = $employee_work->job_position;
                    $salary->start_date = $employee_work->start_date;
                }

                //employee age
                $salary->age = date_diff(date_create($employee->date_of_birth), date_create('now'))->y;

                //get the attendance for this month for all sites
                $month = date('n');
                $max_day_month = date('t');

                //lumpsum
                if ($employee_salary) {
                    $salary->amount = $employee_salary->salary;
                    $salary->account_no = $employee_salary->account_number;
                    $salary->basic = $employee_salary->salary;
                    $bank = DB::table('banks')->where('id', $employee_salary->bank)->first();
                    if ($bank) {
                        $salary->bank = $bank->name;
                    }
                }


                //salary month
                $salary->month = $month;
                //get the unit day allocation for the salary
                $day_cost = $salary->amount / $max_day_month;
                $salary->day_cost = $day_cost;

                //start date


                $attendance = DB::table('attendances')->join('attendance_records', 'attendances.id', 'attendance_records.attendance_id')->whereMonth('attendances.date', $selected_month)->whereYear('attendances.date', $selected_year)->where('employee_id', $emp->id)->get();
                $count_present = 0;
                $count_absent = 0;
                $total_overtime_hour = 0;
                $total_hour = 0;
                $total_overtime_amount = 0;
                if ($attendance) {
                    //there is attendance for this employee for this month
                    foreach ($attendance as $attend) {
                        if ($attend->present == 1) {
                            $count_present++;
                        }
                        if ($attend->absent == 1) {
                            $count_absent++;
                        }
                        if ($attend->present == 1) {
                            //get total hours spend on this day.
                            $start = strtotime($attend->time_in);
                            $end = strtotime($attend->time_out);
                            $diff = $end - $start;
                            $total_hr_spent = round(abs($diff / 3600), 2);
                            $total_hour += $total_hr_spent;

                            //get the normal hour
                            //$emp_start = DB::table('morning_period')->where('id', )
                            $day = date('l', strtotime($attend->date));
                            if ($day == "Monday") {
                                if ($employee_work->moday_morning && $employee_work->moday_evening) {

                                    $normal_start_time = strtotime($this->getMorning($employee_work->moday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->moday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            } else if ($day == "Tuesday") {
                                if ($employee_work->tuesday_morning && $employee_work->tuesday_evening) {
                                    $normal_start_time = strtotime($this->getMorning($employee_work->tuesday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->tuesday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            } else if ($day == "Wednesday") {
                                if ($employee_work->wednesday_morning && $employee_work->wednesday_evening) {
                                    $normal_start_time = strtotime($this->getMorning($employee_work->wednesday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->wednesday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            } else if ($day == "Thursday") {
                                if ($employee_work->thurseday_morning && $employee_work->thurseday_evening) {
                                    $normal_start_time = strtotime($this->getMorning($employee_work->thurseday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->thurseday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            } else if ($day == "Friday") {
                                if ($employee_work->friday_morning && $employee_work->friday_evening) {
                                    $normal_start_time = strtotime($this->getMorning($employee_work->friday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->friday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            } else if ($day == "Saturday") {
                                if ($employee_work->saturday_morning && $employee_work->saturday_evening) {
                                    $normal_start_time = strtotime($this->getMorning($employee_work->saturday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->saturday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            } else if ($day == "Sunday") {
                                if ($employee_work->sunday_morning && $employee_work->sunday_evening) {
                                    $normal_start_time = strtotime($this->getMorning($employee_work->sunday_morning));
                                    $normal_end_time = strtotime($this->getEvening($employee_work->sunday_evening));
                                    $diff_normal = $normal_end_time - $normal_start_time;
                                    $normal_hour_set = round(abs($diff_normal / 3600), 2);

                                    $overtime2 = $total_hr_spent - $normal_hour_set;
                                    if ($overtime2 > 0) {
                                        $overtimeAmt = $this->calculateOvertime($salary->basic, $overtime2, $attend->date);
                                        $total_overtime_amount += $overtimeAmt;
                                        $total_overtime_hour += $overtime2;
                                    }
                                }
                            }
                        }
                    }


                    $salary->present = $count_present;
                    $salary->absent = $count_present;
                }

                if ($count_absent == 0) {
                    $salary->amount = $salary->basic;
                } else {
                    $absent_amount  = $day_cost * $count_absent;
                    $salary->amount = $salary->basic - $absent_amount;
                }




                $lumpsum = $salary->amount +  $total_overtime_amount; //salary + overtime

                //get working_days in a month
                //$date = $request->month . "-01";
                $start_date =  $date;
                $end_date = date("Y-m-t", strtotime($start_date));
                $no_of_days = $this->getNoOfDays($start_date, $end_date);

                //save record to database
                DB::table('payrolls')->insert([
                    'employee_id' => $emp->id,
                    'lumpsum' => $lumpsum,
                    'month' => $request->month,
                    'overtime' => $total_overtime_amount,
                    'workdays' => $count_present,
                    'absent' => $count_absent,
                    'day_cost' => $day_cost,
                    'site_id' => $salary->site,
                    'department_id' => $salary->department,
                    'company_id' => $salary->company,
                    'basic_salary' => $employee_salary->basic_salary,
                    'date' => $date,
                    'position_id' => $salary->position,
                    'overtime_hours' => $total_overtime_hour,
                    'month_int' => $selected_month,
                    'year_int' => $selected_year,
                    'total_hour' => $total_hour,
                    'bonus' => 0, //not provided
                    'loan' => 0,  //not provided
                    'no_of_days' => $no_of_days,
                    // 'bank' => $salary->bank,
                    //'account_no' => $salary->account_no,
                    'generate_payroll_id' => $generate_payroll_id
                ]);
            }
            return $this->getGeneratedPayroll($selected_month, $selected_year, $request->user_id, $generate_payroll_id);
        } else {
            return response()->json([
                'data' => 2
            ]);
        }
    }

    function getGeneratedPayroll($selected_month, $selected_year, $user_id, $generate_payroll_id)
    {
        //get the payroll that just generated
        //$emp = DB::table('payrolls')->whereMonth('date', $selected_month)->whereYear('date', $selected_year)->get();
        $emp = DB::table('payrolls')->where('generate_payroll_id', $generate_payroll_id)->get();
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Payroll();
                $employee->sno = $count;
                $employee->employee_id = $value->employee_id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;

                    $employee->age = date_diff(date_create($personal->date_of_birth), date_create('now'))->y;
                }


                $company = DB::table('companies')->where('id', $value->company_id)->first();
                if ($company) {
                    $employee->company = $company->name;
                }
                $site = DB::table('sites')->where('id', $value->site_id)->first();
                if ($site) {
                    $employee->site = $site->name;
                    $employee->site_id = $site->id;
                }

                $department = DB::table('departments')->where('id', $value->department_id)->first();
                if ($department) {
                    $employee->department = $department->name;
                }

                $position = DB::table('positions')->where('id', $value->position_id)->first();


                if ($position) {
                    $employee->position = $position->name;
                }

                $salary_detail = DB::table('employee_salary_details')->where('employee_id', $value->employee_id)->first();
                if ($salary_detail) {
                    $bank = DB::table('banks')->where('id', $salary_detail->bank)->first();
                    if ($bank) {
                        $employee->bank = $bank->name;
                    }
                    $employee->account_number = $salary_detail->account_number;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $employee->employee_no = $work->empno;
                    $employee->StartDate = $work->start_date;
                }
                $employee->overtime = $value->overtime;
                $employee->day_worked = $value->workdays;
                $employee->lumpsum = $value->lumpsum;

                $employee->company_id = $value->company_id;
                $employee->department_id = $value->department_id;
                $employee->company_id = $value->company_id;

                $the_employee[] = $employee;
            }
        }

        //send email
        $user = DB::table('users')->where('id', $user_id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->confirmation = 1;
        $email->subject = "Payroll Notification";
        $email->body = "This is to notify you of payroll generated for " . $selected_year . "-" . $selected_month . " in the smartHR. ";
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));

        return response()->json([
            //'date' => $date,
            'employees' => $the_employee,
            'month' => $selected_month,
            'data' => 1,
            'generate_payroll_id' => $generate_payroll_id,
        ]);
    }
    public function getNormalTime($date)
    { }
    public function getEvening($id)
    {
        //if ($id) {
        $evening = DB::table('evening_period')->where('id', $id)->first();
        return $evening->time;
        //}
    }
    public function getMorning($id)
    {
        //if ($id) {
        $morning = DB::table('morning_period')->where('id', $id)->first();
        return $morning->time;
        // }
    }
    public function calculateOvertime($basic_salary, $overtime, $attendance_date)
    {
        $total_overtime = 0;
        $day = date('l', strtotime($attendance_date));
        if ($day == "Saturday") {
            $total_overtime = ($basic_salary * 26 * $overtime * 1.5) / 2392;
        } else if ($day == "Sunday") {
            $total_overtime = ($basic_salary * 26 * $overtime * 2) / 2392;
        } else {
            $total_overtime = ($basic_salary * 26 * $overtime * 1.25) / 2392; //this works for public holiday and normal too
        }

        return $total_overtime;
    }
    public function index()
    {
        $month = date('n');
        $emp = DB::table('payrolls')->whereMonth('date', $month)->get();
        $the_employee = array();
        $count = 0;
        $generated_pay_id = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Payroll();
                $employee->sno = $count;
                $employee->employee_id = $value->employee_id;
                $generated_pay_id = $value->generate_payroll_id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;

                    $employee->age = date_diff(date_create($personal->date_of_birth), date_create('now'))->y;
                }
                $company = DB::table('companies')->where('id', $value->company_id)->first();
                if ($company) {
                    $employee->company = $company->name;
                }
                $site = DB::table('sites')->where('id', $value->site_id)->first();
                if ($site) {
                    $employee->site = $site->name;
                    $employee->site_id = $site->id;
                }

                $department = DB::table('departments')->where('id', $value->department_id)->first();
                if ($department) {
                    $employee->department = $department->name;
                }

                $position = DB::table('positions')->where('id', $value->position_id)->first();


                if ($position) {
                    $employee->position = $position->name;
                }

                $salary_detail = DB::table('employee_salary_details')->where('employee_id', $value->employee_id)->first();
                if ($salary_detail) {
                    $bank = DB::table('banks')->where('id', $salary_detail->bank)->first();
                    if ($bank) {
                        $employee->bank = $bank->name;
                    }
                    $employee->account_number = $salary_detail->account_number;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $employee->employee_no = $work->empno;
                    $employee->StartDate = $work->start_date;
                }
                $employee->overtime = $value->overtime;
                $employee->day_worked = $value->workdays;
                $employee->lumpsum = $value->lumpsum;

                $employee->company_id = $value->company_id;
                $employee->department_id = $value->department_id;
                $employee->company_id = $value->company_id;

                $the_employee[] = $employee;
            }
        }
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();

        //check the approval 
        $check_payroll = DB::table('generated_payroll')->where('id', $generated_pay_id)->first();

        $pay2 = new PayrollApproval();

        if ($check_payroll) {
            $user = DB::table('users')->where('id', $check_payroll->approved_by)->first();
            if ($user) {
                $pay2->approved_by = $user->name;
            }

            $pay2->approved_time = $check_payroll->approved_datetime;
            $pay2->remarks = $check_payroll->remarks;
            $pay2->approve = $check_payroll->approve;
            $pay2->total = $check_payroll->total;
            $pay2->payroll_generated_id = $check_payroll->id;
        }


        return view('payroll.index', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'employees' => $the_employee, 'pay2' => $pay2]);
    }

    public function payrollApproval()
    {
        $month = date('n');
        $emp = DB::table('payrolls')->whereMonth('date', $month)->get();
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Payroll();
                $employee->sno = $count;
                $employee->employee_id = $value->employee_id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;

                    $employee->age = date_diff(date_create($personal->date_of_birth), date_create('now'))->y;
                }
                $company = DB::table('companies')->where('id', $value->company_id)->first();
                if ($company) {
                    $employee->company = $company->name;
                }
                $site = DB::table('sites')->where('id', $value->site_id)->first();
                if ($site) {
                    $employee->site = $site->name;
                    $employee->site_id = $site->id;
                }

                $department = DB::table('departments')->where('id', $value->department_id)->first();
                if ($department) {
                    $employee->department = $department->name;
                }

                $position = DB::table('positions')->where('id', $value->position_id)->first();


                if ($position) {
                    $employee->position = $position->name;
                }

                $salary_detail = DB::table('employee_salary_details')->where('employee_id', $value->employee_id)->first();
                if ($salary_detail) {
                    $bank = DB::table('banks')->where('id', $salary_detail->bank)->first();
                    if ($bank) {
                        $employee->bank = $bank->name;
                    }
                    $employee->account_number = $salary_detail->account_number;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $employee->employee_no = $work->empno;
                    $employee->StartDate = $work->start_date;
                }
                $employee->overtime = $value->overtime;
                $employee->day_worked = $value->workdays;
                $employee->lumpsum = $value->lumpsum;

                $employee->company_id = $value->company_id;
                $employee->department_id = $value->department_id;
                $employee->company_id = $value->company_id;

                $the_employee[] = $employee;
            }
        }
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('payroll.payroll_approval', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'employees' => $the_employee]);
    }



    public function addPublicHoliday(Request $request)
    {
        DB::table('public_holidays')->insert($request->all());

        $holidays = DB::table('public_holidays')->orderby('date')->get();

        return response()->json([
            'holidays' => $holidays
        ]);
    }
    public function removePubDate($id)
    {
        DB::table('public_holidays')->where('id', $id)->delete();

        $holidays = DB::table('public_holidays')->orderby('date')->get();

        return response()->json([
            'holidays' => $holidays
        ]);
    }

    public function getPublicHolidays()
    {
        $holidays = DB::table('public_holidays')->orderby('date')->get();

        return response()->json([
            'holidays' => $holidays
        ]);
    }

    public function getNoOfDays($start, $end)
    {
        //if public holiday falls on weekend, what do we do....
        $from = Carbon::parse($start);
        $to = Carbon::parse($end);
        $working_days =  $to->diffInWeekdays($from);
        $public_holidays = DB::table('public_holidays')->whereBetween('date', [$start, $end])->count();
        $total_days =  $working_days - $public_holidays;
        return $total_days; //add the last day.
    }
}
