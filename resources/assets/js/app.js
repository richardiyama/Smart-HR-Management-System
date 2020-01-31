/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component("example", require("./components/Example.vue"));
import Employeemanager from "./components/employees/Employeemanager.vue";
//import Bvnnewemployee from "./components/employees/Bvnnewemployee.vue";
import Emergencycontact from "./components/employees/Emergencycontact.vue";
import Terminateemployee from "./components/employees/Terminateemployee.vue";
import Terminatedemployeemanager from "./components/terminated_employees/Terminatedemployeemanager.vue";
import Approvetermination from "./components/terminated_employees/Approvetermination.vue";
import Attendance from "./components/attendance/Attendance.vue";
import Pastattendances from "./components/attendance/Pastattendances.vue";
import Attendancerecord from "./components/attendance/Attendancerecord.vue";
import Dashboard from "./components/Dashboard/Dashboard.vue";
import Generatepayroll from "./components/Payroll/Generatepayroll.vue";
import Payrollhistory from "./components/Payroll/Payrollhistory";
import Payrollreport from "./components/Payroll/Payrollreport.vue";
import Settings from "./components/Settings.vue";
import Pendingemployees from "./components/employees/Pendingemployees.vue";
import Confirmemployee from "./components/employees/Confirmemployee.vue";
import Rejectemployee from "./components/employees/Rejectemployee.vue";
import Payrollapproval from "./components/Payroll/Payrollapproval.vue";
import changelumpsum from "./components/request/Changelumpsum.vue";
import Pendingterminated from "./components/terminated_employees/Pendingterminated.vue";

import VueApexCharts from "vue-apexcharts";
Vue.component("apexchart", VueApexCharts);

//Vue.store = Vue.prototype.APP_URL = "/smarthr/public";
Vue.store = Vue.prototype.APP_URL = ""; //local

const app = new Vue({
    el: "#app_smarthr",
    components: {
        Employeemanager,
        //Bvnnewemployee,
        Emergencycontact,
        Terminateemployee,
        Terminatedemployeemanager,
        Approvetermination,
        Attendance,
        Pastattendances,
        Attendancerecord,
        Dashboard,
        Generatepayroll,
        Payrollhistory,
        Payrollreport,
        Settings,
        Pendingemployees,
        Confirmemployee,
        Rejectemployee,
        Payrollapproval,
        changelumpsum,
        Pendingterminated
    }
});