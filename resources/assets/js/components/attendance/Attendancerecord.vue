<template>
  <div>
    <div class="row">
       <div class="row tile_count">
          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Total Hours</span>
              <div class="count"> {{the_hour_worked}}</div>
          </div>
          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-building-o"></i> Number of Companies</span>
              <div class="count"> {{companies_count}}</div>
          </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count" style="color:green; font-weight:bold;">
              <span class="count_top"><i class="fa fa-clock-o"></i> Total Hours/Site ( {{site_name}} )</span>
              <div class="count">{{work_hour}}</div>
          </div>
        </div>

 <div class="col-md-12">View hours from </div> <br>
                <div class="row calendar-exibit">  
                    <div class="col-md-3">
                      <br>
                        <div class="col-md-1"></div>
                        <span>From date</span>
                        <fieldset>
                            <div class="control-group">
                            <div class="controls">
                               <input type="date" v-model="fromdate" class="form-control">
                            </div>
                            </div>
                        </fieldset>
                    </div>
                   <!-- <div class="col-md-1">

                      <span>To</span>
                    </div> -->

                    <div class="col-md-3">
                      <br>
                        <div class="col-md-1"></div>
                        <span>To date</span>
                        <fieldset>
                        <div class="control-group">
                            <div class="controls">
                            <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                              <input type="date" v-model="todate" class="form-control" v-on:change="searchByDate">
                            </div>
                            </div>
                        </div>
                        </fieldset>
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
           
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-12">Filter by:</div>
            <div class="col-md-12" style="margin-bottom:20px !important">
              <form class="form-vertical form-label-top">
                <div class="form-group">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <label>Company</label>
                    <select class="form-control" v-model="company_id" @change="getCompany">
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
                    <select class="form-control" v-model="site_id" @change="getSite">
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
                    <select class="form-control" v-model="department_id" @change="getDepartment">
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
           
            <table id="data" class="table table-condensed jambo_table bulk_action">
              <thead>
                <tr>
                  <th>EmpNo.</th>
                  <th>Name</th>
                  <th>Site</th>
                  <th>Company</th>
                  <th>Attendance</th>
                  <th>Present/Absent</th>
                  <th>Time in</th>
                  <th>Time out</th>
                  <th>Total Man Hrs</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="item in the_employees" :key="item.id">
                  <td>{{item.employee_no}}</td>
                  <td>{{item.name}}</td>
                  <td>{{item.site}}</td>
                  <td>{{item.company}}</td>
                  <td>{{item.attendance}}</td>
                  <td>
                    <span v-if="item.present == 1"><i class="fa fa-check text-success"></i></span>
                    <span v-if="item.absent == 1"><i class="fa fa-times text-danger"></i></span>
                 
                  </td>
                 
                  <td>
                   {{item.time_in}}
                  </td>
                  <td>
                   {{item.time_out}}
                  </td>
                  <td>{{item.total_emp_work}} hrs</td>
                </tr>
              </tbody>
            </table>
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
  name: "Attendancerecord",
  props: [
    "today",
    "companies",
    "sites",
    "departments",
    "this_today",
    "user_id",
    "employees",
    "the_downloads",
    "companies_count",
    "hour_worked"
  ],
  data() {
    return {
      the_companies: [],
      the_sites: [],
      the_departments: [],
      the_employees: [],
      the_employees1: [],
      downloads: [],
      the_hour_worked: 0,
      site_hours: 0,
      the_company_count: 0,
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
      site_name_default: "Select Site",
      site_name:"Select Site",
      work_hour: 0,
      site_name_derived: null,
      company_id:"",
      site_id: "",
      department_id: "",
     // APP_URL: ""
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
        let total_hour_site = 0;
        let emp = this.the_employees1.filter(r=>r.site_id==this.site_id);
        emp.forEach(element => {
          total_hour_site +=  element.total_emp_work;
        })

      //get the site name
       let empSite = this.the_sites.filter(r=>r.id==this.site_id);

       empSite.forEach(element => {
         this.site_name = element.name;
       })

        this.work_hour = total_hour_site;
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
    searchByDate() {
       if(this.todate == null) {
           alert('please select from date');
       }else {
           this.visible = true;
           axios.get(this.APP_URL+'/api/getattendancefromdate/'+this.fromdate+'/'+this.todate).then(response=>{
               console.log(response);
              
               //console.log(response.data.downloads);
                setTimeout(() => {
               this.the_employees = response.data.employees
               this.the_employees1 = response.data.employees
               this.the_hour_worked = response.data.total_worked;
               //this.downloads = response.data.downloads
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

      //this.work_hour = this.the_hour_worked
      this.site_name = this.site_name_default;
      
    },
    get_site(event) {

      var search = event.target.value;
     // alert(search);
      this.search(search, 2);
      //this.work_hour = this.site_hours
      //this.site_name = this.site_name_derived;
      //alert(this.site_name_derived);
      
    },
    get_department(event) {
      //this.site_hours = 0;
    
      var search = event.target.value;
      this.search(search, 3);
      //this.work_hour = this.the_hour_worked
      this.site_name = this.site_name_default;
     
    
    },
    search(search_param, check) {
      this.visible = true;
      axios
        .get(
          this.APP_URL +
            "/api/get-employee-attendance2/" +
            search_param +
            "/" +
            check
        )
        .then(response => {
          console.log(response.data);
       
          // this.employee_counting = response.data.employee_count;
          setTimeout(() => {
               this.the_employees = response.data.employees;
               //this.site_hour_worked: "Total Hour Worked ()"
               if(check == 2) {
                  this.work_hour = response.data.site_hours;
                  this.site_name = response.data.site_name;
               }
              
               this.visible = false;
          }, 3000);
        })
        .catch(error => {
          alert(error)
        });
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
    this.work_hour = this.hour_worked;
    this.the_company_count = this.companies_count;
  }
};
</script>

<style>
</style>
