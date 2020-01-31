<template>
  <div>
    <div class="row">
       <div class="col-m-12">
                    <form class="form-vertical form-label-top">  
                    <div class="col-md-3">
                        <label>Select payroll filter options</label>
                      <select v-model="payroll.option" class="form-control" @change="getSelected">
                        <option value="">Select Option</option>
                        <option value="1">Gross salary</option>
                        <option value="2">Net Salary</option>
                        <option value="3">Hours</option>
                        <option value="4">Overtime</option>
                        <option value="5">Deductions</option>
                        <option value="6">Bonuses</option>
                        <option value="7">Pensions</option>
                        <option value="8">Tax</option>
                      </select>
                    </div>
                    <div class="col-md-3" style="text-align:left">
                        <div class="col-md-1"></div>
                        <label>Year</label>
                        <fieldset>
                            <div class="control-group">
                            <div class="controls">
                             <select @change="getSelected" v-model="payroll.year" class="form-control">
                               <option value="">Select Year</option>
                               <option value="2019">2019</option>
                               <option value="2020">2020</option>
                               <option value="2021">2021</option>
                               <option value="2022">2022</option>
                               <option value="2023">2023</option>
                             </select>
                            </div>
                            </div>
                           
                        </fieldset>
                    </div>
                    </form>
                </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
         <loading
        :active.sync="visible"
        :can-cancel="false"
        :is-full-page="fullPage"
      ></loading>
        <div class="x_panel">
          <div class="x_title">
           
            <ul class="nav navbar-right panel_toolbox">
              <li>
               
              </li>
              <li>
                <a class="collapse-link">
                  <i class="fa fa-chevron-up"></i>
                </a>
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
                    <select class="form-control" @change="get_company($event)">
                      <option value="0">All</option>
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
                    <select class="form-control" @change="get_site($event)">
                      <option value="0">All</option>
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
                    <select class="form-control" @change="get_department($event)">
                      <option value="0">All</option>
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
                    <a :href="APP_URL+'/payroll/report'" class="btn btn-default">
                      <span class="fa fa-refresh"></span> Refresh
                    </a>
                  </div>
                </div>
              </form>
            </div>
           
            <table id="data" class="table table-condensed jambo_table bulk_action">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Jan</th>
                  <th>Feb</th>
                  <th>Mar</th>
                  <th>Apr</th>
                  <th>May</th>
                  <th>Jun</th>
                  <th>Jul</th>
                  <th>Aug</th>
                  <th>Sep</th>
                  <th>Oct</th>
                  <th>Nov</th>
                  <th>Dec</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="item in the_employees" :key="item.id">
                 <td>{{item.name}}</td>
                 <td>{{item.jan }}</td>
                 <td>{{item.feb }}</td>
                 <td>{{item.mar }}</td>
                 <td>{{item.apr }}</td>
                 <td>{{item.may }}</td>
                 <td>{{item.jun }}</td>
                 <td>{{item.jul }}</td>
                 <td>{{item.aug }}</td>
                 <td>{{item.sep }}</td>
                 <td>{{item.oct }}</td>
                 <td>{{item.nov }}</td>
                 <td>{{item.dec }}</td>
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
  name: "Payrollhistory",
  props: [
    "companies",
    "sites",
    "departments",
    "user_id",
  ],
  data() {
    return {
      the_companies: [],
      the_sites: [],
      the_departments: [],
      the_employees: [],
      the_employees1: [],
      upload_today: {
        today_date: this.this_today,
        user_id: this.user_id,
        file: null,
        employees: this.the_employees
      },
      visible: false,
      fullPage: true,
      payroll: {
        year:"",
        option:"",
        user_id:this.user_id
      },
      payrolls:[],

    };
  },
  components: {
    Loading
  },
  methods: {
    getSelected(event) {
     // let option = event.target.value;
      if(this.payroll.option == "" || this.payroll.year == "") {
        return false;
      }
      this.visible = true;
      axios.post(this.APP_URL+'/api/get_payroll_option', this.payroll)
      .then(response=>{
      // console.log(response.data);
        setTimeout(() => {
             this.visible = false;
              //alert("Success");
                this.the_employees = response.data.employees;
                this.the_employees1 = response.data.employees;
          }, 3000);
      }).catch(error => {
        alert(error);
      });
      
    },
    generalPayroll() {
       this.visible = true;
      axios.get(this.APP_URL+'/api/search_payroll/'+this.payroll.month)
      .then(response=>{
       // console.log(response.data);
        setTimeout(() => {
             this.visible = false;
              //alert("Success");
                this.the_employees = response.data.employees;
                this.the_employees1 = response.data.employees;
          }, 3000);
      }).catch(error => {
        alert(error);
      });
      
     
    },
    checkAttendance(employee_id, status) {
      //if(status == 1)
    },
    get_company(event) {
      var search = event.target.value;
      if(search == "0") {
        this.the_employees = this.the_employees1;
      }else {
       let employees = this.the_employees1.filter(r=>r.company_id==search);
      this.the_employees = employees
      }
     
    },
    get_site(event) {
      var search = event.target.value;
    if(search == "0") {
        this.the_employees = this.the_employees1;
      }else {
       let employees = this.the_employees1.filter(r=>r.site_id==search);
      this.the_employees = employees
      }
    },
    get_department(event) {
    var search = event.target.value;
   // alert(search);
    if(search == "0") {
        this.the_employees = this.the_employees1;
      }else {
       let employees = this.the_employees1.filter(r=>r.department_id==search);
      this.the_employees = employees
      }
    },
    search(search_param, check) {
      this.visible = true;
      axios
        .get(
          this.APP_URL +
            "/api/get-employee-attendance/" +
            search_param +
            "/" +
            check
        )
        .then(response => {
          setTimeout(() => {
            this.visible = false;
            this.the_employees = response.data.employees;
          }, 3000);
        })
        .catch(error => console.log(error));
    },
    singleAttendPresent(employee_id) {
      let employees = this.the_employees.filter(
        r => r.employee_id == employee_id
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
        r => r.employee_id == employee_id
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
        //alert(element.time_in2);
        // alert(element.time_out2);
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
            }, 5000);
           
          })
          .catch(error => console.log(error));
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
  }
};
</script>

<style>
</style>
