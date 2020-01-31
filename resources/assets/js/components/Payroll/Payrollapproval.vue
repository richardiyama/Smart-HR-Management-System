<template>
  <div>
    <div class="row tile_count">
          <div class="col-md-6 col-sm-3 col-xs-3">
            <fieldset>
              <div class="control-group">
                <label>Select Payroll Date</label>
              <div class="controls">
                  <div class="col-md-6 xdisplay_inputx form-group has-feedback">
                      <input v-model="payroll.month" type="month" class="form-control" @change="getThePayroll">
                  </div>
              </div>
              </div>
          </fieldset>
          </div>
          <a @click="getPublicHolidays" href="#"  data-backdrop="static"
                  data-keyboard="false"
                  data-toggle="modal"
                  data-target="#public_holiday" class="btn btn-danger pull-right"> <span class="fa fa-check"></span> Approve This Payroll</a>
        </div>
    <div>

    </div>
    <div class="row">
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
                    <a :href="APP_URL+'/payroll-approval'" class="btn btn-default">
                      <span class="fa fa-refresh"></span> Refresh
                    </a>
                  </div>
                </div>
              </form>
            </div>
           
            <table id="datatable-responsive" class="table table-striped jambo_table bulk_action">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Company</th>
                  <th>Bank Name</th>
                  <th>Account Number</th>
                  <th>Gross Monthly</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="item in the_employees" :key="item.id">
                 
                </tr>
              </tbody>
            </table>
          </div>
         
        </div>
      </div>
    </div>


     <div id="public_holiday" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Manage Public Holidays</h4>
          </div>
          <loading :active.sync="visible4" :can-cancel="false"></loading>
          <div class="modal-body">
            <p>
              <span style="font-weight:bold; color:red">You may have to do this once in year. For every year add public days of that year to this table. this is very very important for you to get correct and exact <strong>NETPAY</strong>. This has to be done manually because there are some public holidays that are not constant. </span>
            </p>
            <p>
              <div class="form-group">
                <input type="date" class="form-control" v-model="holiday.date">
              </div>
               <div class="form-group">
                <input type="text" class="form-control" placeholder="Remarks" v-model="holiday.remarks">
              </div>
              <button class="btn btn-success" @click="addPub">Add</button>
            </p>
            <div style="overflow-y:auto; height:300px">
              <table class="table table-striped">
              <thead>
               <th>Public Holiday</th>
               <th>Remarks</th>
               <th></th>
              </thead>
              <tbody>
                <tr v-for="item in holidays" :key="item.id">
                  <td>{{item.date}}</td>
                  <td>{{item.remarks}}</td>
                  <td>
                    <span class="fa fa-times" style="cursor:pointer" title="remove date" @click="removePubDate(item.id)"></span>
                  </td>
                </tr>
              </tbody>
            </table>
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
  name: "Payrollapproval",
  props: [
    "today",
    "companies",
    "sites",
    "departments",
    "this_today",
    "user_id",
    "employees",
    "role_id"
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
      visible4: false,
      fullPage: true,
      payroll: {
        month:null,
        user_id:this.user_id
      },
      payrolls:[],
      holiday: {
        date:null,
        remarks:null,
      },
      holidays: [],
      payrollTotal: 0,


    };
  },
  components: {
    Loading
  },
  methods: {
    getThePayroll() {
      alert(this.payroll.month);
    },
    getPublicHolidays() {
      this.visible4 = true;
      axios.get(this.APP_URL+'/api/getPublicHolidays/')
      .then(response=>{
         setTimeout(() => {
            this.holidays = response.data.holidays;
            this.visible4 = false;
          }, 3000);

      }).catch(error => {
        alert(error)
      })
    },
    removePubDate(id) {
      if(confirm("Are you sure you want to remove this date ? ")) {
        this.visible4 = true;
        axios.get(this.APP_URL+'/api/removePubDate/'+ id)
        .then(response=>{
          setTimeout(() => {
              alert('Operation was successful');
              this.holidays = response.data.holidays;
              this.visible4 = false;
            }, 3000);

        }).catch(error => {
          alert(error)
        })
      }
      
    },
    addPub() {
      if(this.holiday.date == null) {
        alert('Please select date');
        return false;
      }
      this.visible4 = true;
      axios.post(this.APP_URL+'/api/addPublicHoliday', this.holiday)
      .then(response=>{
         setTimeout(() => {
            alert('Operation was successful');
            this.holidays = response.data.holidays;
            this.visible4 = false;
            this.holiday.date = null;
            this.holiday.remarks = null;
          }, 3000);

      }).catch(error => {
        alert(error)
      })
    },
    generalPayroll() {
      confirm("Are you sure you want to generate payroll for the selected month and year ! ")
      {
       this.visible = true;
      axios.post(this.APP_URL+'/api/generatePayroll', this.payroll)
      .then(response=>{
        //console.log(response.data);
        setTimeout(() => {
             this.visible = false;
             if(response.data.data == 2) {
               alert('No attendance for the selected month, therefore, payroll can not be generated');
               this.the_employees = [];
               this.the_employees1 = [];
             }else {
               alert("Success");
                this.the_employees = response.data.employees;
                this.the_employees1 = response.data.employees;
             }
             
          }, 3000);
      }).catch(error => {
        alert(error);
      });
      }
     
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
     // alert(search);
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
    //alert(this.role_id);
    this.the_companies = JSON.parse(this.companies);
    this.the_sites = JSON.parse(this.sites);
    this.the_departments = JSON.parse(this.departments);
   // this.the_employees = JSON.parse(this.employees);
   //  this.the_employees1 = JSON.parse(this.employees);
  }
};
</script>

<style>
</style>
