<template>
  <div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
         <loading
        :active.sync="visible"
        :can-cancel="false"
        :is-full-page="fullPage"
      ></loading>
        <div class="x_panel">
          <div class="x_title">
            <h2 style="font-size: 25px">{{today}}</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li>
                <button
                  @click="submitAttendance"
                  type="button"
                  data-backdrop="static"
                  data-keyboard="false"
                  data-toggle="modal"
                  data-target="#today_attendance"
                  class="btn btn-success"
                  v-if="role_id != 2"
                >Submit & Upload Attendance</button>
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
                    <select v-model="selected_site"  class="form-control" @change="get_site($event)">
                      <option value="">No Site</option>
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
                    <a :href="APP_URL+'/attendance'" class="btn btn-default">
                      <span class="fa fa-refresh"></span> Refresh
                    </a>
                  </div>
                </div>
              </form>
            </div>
            <span style="color:red">Time in and Time out are compulsory fields if employee is marked present</span>
            <div class="col-md-12" style="margin-bottom:20px !important; text-align:right">
              <p>
                <label>Mark all as</label>
                Present
                <input type="radio" name="action" v-on:change="presentAll">
                Absent
                <input type="radio" name="action" v-on:change="absentAll">
              </p>
            </div>
            <table id="data" class="table table-condensed jambo_table bulk_action">
              <thead>
                <tr>
                  <th>EmpNo</th>
                  <th>Name</th>
                  <th>Company</th>
                  <th>Present</th>
                  <th>Absent</th>
                  <th>Time in</th>
                  <th>Time out</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="item in the_employees" :key="item.id">
                  <td>{{item.employee_no}}</td>
                  <td>{{item.name}}</td>
                  <td>{{item.company}}</td>
                  <td>
                    <input
                      type="radio"
                      value="1"
                      :name="'attend'+item.employee_id"
                      v-model="item.present"
                      v-on:change="singleAttendPresent(item.employee_id)"
                    >
                  </td>
                  <td>
                    <input
                      type="radio"
                      value="1"
                      :name="'attend'+item.employee_id"
                      v-model="item.absent"
                      v-on:change="singleAttendAbsent(item.employee_id)"
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

    <div id="today_attendance" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Upload Attendance For : {{upload_today.today_date}}</h4>
          </div>
          <loading :active.sync="visible" :can-cancel="false" :is-full-page="fullPage"></loading>
          <div class="modal-body">
            <div>
              <p style="font-size:14px; color:red">
                Are you sure you want to submit this attendance on this date: {{today}} !
                <br>Change date:
                <input type="date" v-model="upload_today.today_date">
                &nbsp;
                Site: <select disabled v-model="selected_site" name="" id="">
                  <option value="">All</option>
                      <option
                        :value="item.id"
                        v-for="item in the_sites"
                        :key="item.id"
                      >{{item.name}}</option>
                </select>
              </p>
              <hr>
              <div style="overflow-y:auto; height:300px;">
                <table class="table table-condensed">
                  <thead>
                    <th>name</th>
                    <th>Company</th>
                    <th>Site</th>
                    <th>Department</th>
                    <th>P</th>
                    <th>A</th>
                    <th>Time in</th>
                    <th>Time Out</th>
                  </thead>
                  <tbody>
                    <tr v-for="item in the_employees" :key="item.id">
                      <td>{{item.name}}</td>
                      <td>{{item.company}}</td>
                      <td>{{item.site}}</td>
                      <td>{{item.department}}</td>
                      <td>
                        <span v-if="item.present==1" class="fa fa-check text-success"></span>
                      </td>
                      <td>
                        <span v-if="item.absent==1" class="fa fa-times text-danger"></span>
                      </td>
                      <td>{{item.time_in}}</td>
                      <td>{{item.time_out}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <br>
              <p>Kindly upload softcopy of this attendance signed by your supervisor</p>

              <input
                type="file"
                ref="file"
                id="file"
                class="form-control"
                v-on:change="onChangeFileUpload()"
              >
            </div>
            <br>
            <br>
            <button class="btn btn-success" @click="uploadFile">Upload attendance</button>
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
  name: "Attendance",
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
      upload_today: {
        today_date: this.this_today,
        user_id: this.user_id,
        file: null,
        employees: this.the_employees
      },
      visible: false,
      fullPage: true,
      selected_site: "",
     // APP_URL: "",
     // APP_URL: "/smarthr2/public"
    };
  },
  components: {
    Loading
  },
  methods: {
    submitAttendance() {
      
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
          //console.log(response.data);
         
          // this.employee_counting = response.data.employee_count;
          setTimeout(() => {
             this.the_employees = response.data.employees;
            this.visible = false;
          }, 3000);
        })
        .catch(error => {
          alert(error)
        });
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
      let check = 0;
      this.the_employees.forEach(element=>{
        if(element.present == 1) {
          if(element.time_in==null || element.time_out==null) {
            check++;
          }
        }
      })
      if(check > 0) {
        alert("Some fields were not filled, please note that, time in and time out are compulsory fields");
        return false;
      }
     // return false;
      if (this.upload_today.file == null) {
        alert("file cannot be emptied");
        return false;
      }
      if(this.selected_site == "") {
        alert("Please select site");
        return false
      }
      if (confirm("Are you sure you sure you want to perform this operation ? ")) {
        this.visible = true;
        let formdata = new FormData();
        formdata.append("date", this.upload_today.today_date);
        formdata.append("user_id", this.user_id);
        formdata.append("file", this.upload_today.file);
        formdata.append("selected_site", this.selected_site);
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
  }
};
</script>

<style>
</style>
