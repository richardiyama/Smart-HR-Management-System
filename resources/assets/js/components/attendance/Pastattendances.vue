<template>
  <div>
    <div class="row">

 <div class="col-md-12">View attendance from </div>
                <div class="row calendar-exibit">  
                    <div class="col-md-3">
                        <div class="col-md-1"></div>
                        <span>Year</span>
                       <select v-model="record.year" class="form-control">
                         <option value="">Select year</option>
                         <option value="2019">2019</option>
                         <option value="2020">2020</option>
                         <option value="2021">2021</option>
                         <option value="2022">2022</option>
                         <option value="2023">2023</option>
                       </select>
                    </div>
                   <!-- <div class="col-md-1">

                      <span>To</span>
                    </div> -->

                    <div class="col-md-3">
                        <div class="col-md-1"></div>
                        <span>Month</span>
                        <select class="form-control" @change="getPastattendanceMonth" v-model="record.month">
                        <option value="">Select Month</option>
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                        </select>
                    </div>

                     <div class="col-md-3">
                        <div class="col-md-1"></div>
                        <span>Day</span>
                        <select @change="getPastattendanceDay" v-model="record.day" class="form-control">
                        <option value="">Select Day</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                      <br>
                        <div class="col-md-1"></div>
                       <button
                        type="button"
                        data-backdrop="static"
                        data-keyboard="false"
                        data-toggle="modal"
                        data-target="#download"
                        class="btn btn-success"
                      > <span class="fa fa-download"></span> Download Uploaded Attendance </button>
                    </div>
                </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
         <loading
        :active.sync="visible"
        :can-cancel="false"
        :is-full-page="fullPage"
      ></loading>
        <div class="x_panel">
          <div class="x_title">
           
            <ul class="nav navbar-right ">
              <li>
               
              </li>
              
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-12">Filter by:</div>
            <div class="col-md-12" style="margin-bottom:20px !important">
              <form class="form-vertical form-label-top">
                <div class="form-group">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <label>Company</label>
                    <select class="form-control" v-model="company_id" @change="getCompany()">
                      <option value="">All</option>
                      <option
                        :value="item.id"
                        v-for="item in the_companies"
                        :key="item.id"
                      >{{item.name}}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <label>Site</label>
                    <select class="form-control" v-model="site_id" @change="getSite()">
                      <option value="">All</option>
                      <option
                        :value="item.id"
                        v-for="item in the_sites"
                        :key="item.id"
                      >{{item.name}}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <label>Department</label>
                    <select class="form-control" v-model="department_id" @change="getDepartment()">
                      <option value="">All</option>
                      <option
                        :value="item.id"
                        v-for="item in the_departments"
                        :key="item.id"
                      >{{item.name}}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-3 col-sm-3 col-xs-3" style="margin-top:22px">
                    <a :href="APP_URL+'/past-attendance'" class="btn btn-default">
                      <span class="fa fa-refresh"></span> Refresh
                    </a>
                  </div>
                </div>
              </form>
            </div>
            <!--
            <div class="col-md-12" style="margin-bottom:20px !important; text-align:right">
              <p>
                <label>Mark all as</label>
                Present
                <input type="radio" name="action" v-on:change="presentAll">
                Absent
                <input type="radio" name="action" v-on:change="absentAll">
              </p>
            </div> -->
            <table id="data" class="table table-condensed jambo_table bulk_action">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Site</th>
                  <th>Company</th>
                  <th>Attendance</th>
                  <th>Present</th>
                  <th>Absent</th>
                  <th>Time in</th>
                  <th>Time out</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="item in the_employees" :key="item.id">
                  <td>{{item.name}}</td>
                  <td>{{item.site}}</td>
                  <td>{{item.company}}</td>
                  <td>{{item.attendance}}</td>
                  <td>
                    <input
                      type="radio"
                      value="1"
                      :name="'attend'+item.id"
                      v-model="item.present"
                      v-on:change="singleAttendPresent(item.id)"
                    >
                  </td>
                  <td>
                    <input
                      type="radio"
                      value="1"
                      :name="'attend'+item.id"
                      v-model="item.absent"
                      v-on:change="singleAttendAbsent(item.id)"
                    >
                  </td>
                  <td>
                    <input type="time" v-model="item.time_in" class="form-control">
                  </td>
                  <td>
                    <input type="time" v-model="item.time_out" class="form-control">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div id="download" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Download Uploaded Attendance</h4>
          </div>
          <div class="modal-body">
            <div>
            
              <div style="overflow:auto; height=200px;">
                <table class="table table-condensed">
                  <thead>
                    <th>Attendance</th>
                    <th>Created</th>
                    <th>Created by</th>
                    <th>Download</th>
                  </thead>
                  <tbody>
                    <tr v-for="item in downloads" :key="item.id">
                      <td>{{item.date}}</td>
                      <td>{{item.created}}</td>
                      <td>{{item.created_by}}</td>
                      <td><a target="_blank" :href="APP_URL+'/storage/assets/'+item.file"><span class="fa fa-download"></span> Download</a></td>
                    
                    </tr>
                  </tbody>
                </table>
              </div>    
            </div>
           
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// Import component
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";
export default {
  name: "Pastattendances",
  props: [
    "today",
    "companies",
    "sites",
    "departments",
    "this_today",
    "user_id",
    "employees",
    "the_downloads"
  ],
  data() {
    return {
      the_companies: [],
      the_sites: [],
      the_departments: [],
      the_employees: [],
      the_employees1: [],
      downloads: [],
      company_id: "",
      site_id: "",
      department_id: "",
      record: {
        month: "",
        day: "",
        year: ""
      },
      upload_today: {
        today_date: this.this_today,
        user_id: this.user_id,
        file: null,
        employees: this.the_employees
      },
      visible: false,
      fullPage: true,
      fromdate: null,
      todate: null,
      //APP_URL: ""
      // APP_URL: "/smarthr2/public"
    };
  },
  components: {
    Loading
  },
  methods: {

    getCompany() {
      if(this.company_id == "") {
        this.the_employees = this.the_employees1;
      }else {
         let emp = this.the_employees1.filter(r=>r.company_id==this.company_id);
         this.the_employees = emp;
      }
     

    },
    getSite() {
      if(this.site_id == "") {
        this.the_employees = this.the_employees1;
      }else {
         let emp = this.the_employees1.filter(r=>r.site_id==this.site_id);
         this.the_employees = emp;
      }

    },
    getDepartment() {

      if(this.department_id == "") {
        this.the_employees = this.the_employees1;
      }else {
         let emp = this.the_employees1.filter(r=>r.department_id==this.department_id);
         this.the_employees = emp;
      }

    },
   
    getPastattendanceMonth() {
      if(this.record.year == "") {
        alert("Please select year first");
        this.month = "";
      }else {
         this.visible = true;
           axios.post(this.APP_URL+'/api/getPastattendanceMonth', this.record).then(response=>{
               //console.log(response.data.downloads);
              setTimeout(() => {
              this.the_employees = response.data.employees
              this.the_employees1 = response.data.employees
              this.downloads = response.data.downloads
            this.visible = false;
          }, 3000);
           }).catch(error=>{
             alert(error)
           })

      }
    },
    getPastattendanceDay() {

       if(this.record.month == "") {
        alert("Please select month first");
        this.day = "";
      }else {
         this.visible = true;
           axios.post(this.APP_URL+'/api/getPastattendanceDay', this.record).then(response=>{
               //console.log(response.data.downloads);
              setTimeout(() => {
              this.the_employees = response.data.employees
              this.the_employees1 = response.data.employees
              this.downloads = response.data.downloads
            this.visible = false;
          }, 3000);
           }).catch(error=>{
             alert(error)
           })

      }

    },
    searchByDate() {
       if(this.todate == null) {
           alert('please select from date');
       }else {
           this.visible = true;
           axios.get(this.APP_URL+'/api/getattendancefromdate/'+this.fromdate+'/'+this.todate).then(response=>{
               
               //console.log(response.data.downloads);
                setTimeout(() => {
                  this.the_employees = response.data.employees
               this.downloads = response.data.downloads
            this.visible = false;
          }, 3000);
           }).catch(error=>{
             alert(error)
           })
       }
    },
    checkAttendance(employee_id, status) {
      //if(status == 1)
    },
    get_company(event) {
      var search = event.target.value;
      this.search(search, 1);
    },
    get_site(event) {
      var search = event.target.value;
      this.search(search, 2);
    },
    get_department(event) {
      var search = event.target.value;
      this.search(search, 3);
    },
    search(search_param, check) {

      /*this.visible = true;
      axios
        .get(
          this.APP_URL +
            "/api/get-employee-attendance2/" +
            search_param +
            "/" +
            check
        )
        .then(response => {
          //console.log(response.data);
         
          // this.employee_counting = response.data.employee_count;
          setTimeout(() => {
             this.the_employees = response.data.employees;
            this.visible = false;
          }, 5000);
        })
        .catch(error => {
          alert(error)
        });
        */
    },
    singleAttendPresent(employee_id) {
      let employees = this.the_employees.filter(
        r => r.id == employee_id
      );
      employees.forEach(element => {
        element.present = 1;
        element.absent = 0;
        element.time_in = element.time_in2;
        element.time_out = element.time_out2;
      });
    },
    singleAttendAbsent(employee_id) {
      let employees = this.the_employees.filter(
        r => r.id == employee_id
      );
      employees.forEach(element => {
        element.present = 0;
        element.absent = 1;
        element.time_in = null;
        element.time_out = null;
      });
    },
    presentAll() {
      // alert("1");
      this.the_employees.forEach(element => {
        element.present = 1;
        element.absent = 0;
        element.time_in = element.time_in2;
        element.time_out = element.time_out2;

       // alert(element.present)
      });
    },
    absentAll() {
      this.the_employees.forEach(element => {
        element.present = 0;
        element.absent = 1;
        element.time_in = null;
        element.time_out = null;
      });
    },
    uploadFile() {
      if (this.upload_today.file == null) {
        alert("file cannot be emptied");
        return false;
      }
      if (confirm("Are you sure you want to perform this operation ? ")) {
        this.visible = true;
        let formdata = new FormData();
        formdata.append("date", this.upload_today.today_date);
        formdata.append("user_id", this.user_id);
        formdata.append("file", this.upload_today.file);
        formdata.append("employees", JSON.stringify(this.the_employees));
        axios
          .post(this.APP_URL + "/api/updateTodayAttendance", formdata, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(response => {
            //alert("Success");
            setTimeout(() => {
              if (response.data == 1) {
              alert("success");
              window.location.replace(this.APP_URL + "/attendance");
            } else {
              alert("error occured, Please try again");
            }
              this.visible = false;
            }, 3000);
            
          })
          .catch(error => {
            alert(error)
          });
      }
    },
    onChangeFileUpload() {
      this.upload_today.file = this.$refs.file.files[0];
      //console.log(this.upload_today.file);
    }
  },
  created() {
    this.the_companies = JSON.parse(this.companies);
    this.the_sites = JSON.parse(this.sites);
    this.the_departments = JSON.parse(this.departments);
    this.the_employees = JSON.parse(this.employees);
    this.downloads = JSON.parse(this.the_downloads);
    
  }
};
</script>

<style>
</style>
