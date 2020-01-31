<template>
  <div>
    <div class="row">
      <loading
        :active.sync="visible"
        :can-cancel="false"
        :on-cancel="onCancel"
        :is-full-page="fullPage"
      ></loading>

      <!-- top tiles -->
      <div class="row tile_count">
        <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count" style="background-color:white">
          <button
            data-backdrop="static"
            data-keyboard="false"
            data-toggle="modal"
            data-target="#myModal2"
            class="btn btn-success"
            v-if="role_id != 2"
          >Add new employee</button>
          <br>
          <br>
          <span class="count_top">
            <i class="fa fa-user"></i> Number of employees
          </span>
          <div class="count">{{employee_counting}}</div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count" style="background-color:white">
          <button :class="total_check > 0 ? 'btn btn-success' : 'btn btn-secondary'" :disabled="total_check == 0" @click="getTransferEmp" data-toggle="modal" data-target="#myModal"  v-if="role_id != 2">Bulk transfer</button>
          <br>
          <span class="count_top">
            <i class="fa fa-user"></i> Number of employeers in this year
          </span>
          <div class="count">{{employee_year_count}}</div>
          <a :href="APP_URL+'/employees/pending'" title="Click for pending employees">
            <small> <span class="fa fa-cirle text-danger"></span> <strong>{{pending}} Pending Employees</strong> </small>
            <span class="pull-right">
              <i class="fa fa-arrow-right" style="color:green"></i>
            </span>
          </a>
        </div>
      </div>
      <!-- /top tiles -->

      <div class="row">
        <div class="col-md-12">Search (by start date):</div>
        <div class="row calendar-exibit">
          <div class="col-md-3">
            <div class="col-md-1"></div>
            <span>From date</span>
            <fieldset>
              <div class="control-group">
                <div class="controls">
                  <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                    <input type="date" v-model="fromdate" class="form-control">
                  </div>
                </div>
              </div>
            </fieldset>
          </div>

          <div class="col-md-3">
            <div class="col-md-1"></div>
            <span>To date</span>
            <fieldset>
              <div class="control-group">
                <div class="controls">
                  <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                    <input
                      type="date"
                      @change="searchByDate()"
                      v-model="todate"
                      class="form-control"
                    >
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_content">
              <div class="col-md-12">Filter</div>
              <div class="col-md-12" style="margin-bottom:20px !important">
                <form class="form-vertical form-label-top">
                  <div class="form-group">
                    <div class="col-md-2 col-sm-2 col-xs-12">
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
                    <div class="col-md-2 col-sm-2 col-xs-12">
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
                    <div class="col-md-2 col-sm-2 col-xs-12">
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
                </form>
              </div>
              <span style="color:red">Employees in red are under the process of termination, they will be removed from here once the termination has been approved and  effective terminated date has elapsed.</span>
               <table id="datatable-responsive" class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr>
                    <th>
                      <input type="checkbox" v-model="check_all" title="Check all" @click="checkAll()">
                    </th>
                    <th>Employee No.</th>
                    <th>Name</th>
                    <th>Company</th>   
                    <th>Site</th>
                    <th>Department</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in the_employees" :key="item.id" :class="item.is_under_termination==1 ? 'red': '' ">
                    <td>
                      <input type="checkbox" v-model="item.check_employee" @change="checkme(item.id)">
                    </td>
                    
                    <td
                      @click="edit_employee(item.employee_id, item.is_under_termination)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.employee_no}}</td>

                    <td
                      @click="edit_employee(item.employee_id, item.is_under_termination)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.name}}</td>
                    <td
                      @click="edit_employee(item.employee_id, item.is_under_termination)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.company}}</td>
                    
                    <td
                      @click="edit_employee(item.employee_id, item.is_under_termination)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.site}}</td>
                    <td
                      @click="edit_employee(item.employee_id, item.is_under_termination)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.department}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <br>
    </div>

    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Transfer : {{this.transfer.employees.length}} employee(s)</h4>
      </div>
      <div class="modal-body">
         <loading
        :active.sync="visible3"
        :can-cancel="false"
        :on-cancel="onCancel"
        :is-full-page="fullPage"
      ></loading>
        <a class="alert alert-info" style="color:#fff" href="#" @click="viewTransfer($event)">{{view_text}} {{this.transfer.employees.length}} employee(s)</a><br>
        <hr>

        <div v-if="view_emp">
          <br>
          <div class="col-md-12">
            
            <table class="table table-condense">
              <thead>
                <th>Employee No.</th>
                <th>Name</th>
                <th>Company</th>
                <th>Site</th>
                <th>Department</th>
                <th></th>
              </thead>
              <tbody>
                <tr v-for="(item, index) in transfer.employees" :key="item.employee_no" >
                  <td>{{item.employee_no}}</td>
                  <td>{{item.name}}</td>
                  <td>{{item.company}}</td>
                  <td>{{item.site}}</td>
                  <td>{{item.department}}</td>
                  <td><span style="cursor:pointer" @click="removeEmpFromList(index)" class="fa fa-times text-danger" :title="'Remove '+ item.name + ' from the list !'"></span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Transfer Company</label>
              <select v-model="transfer.company" class="form-control">
                <option value="">Select Company</option>
                <option :value="item.id" v-for="item in the_companies" :key="item.id">{{item.name}}</option>
              </select>
            </div>
          </div>
           <div class="col-md-6">
            <div class="form-group">
              <label for="">Transfer Site</label>
              <select v-model="transfer.site" class="form-control">
                <option value="">Select Site</option>
                <option :value="item.id" v-for="item in the_sites" :key="item.id">{{item.name}}</option>
              </select>
            </div>
          </div>

        </div>

         <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Transfer Department</label>
              <select v-model="transfer.department" class="form-control">
                <option value="">Select department</option>
                <option :value="item.id" v-for="item in the_departments" :key="item.id">{{item.name}}</option>
              </select>
            </div>
          </div>
          <!-- <div class="col-md-6">
            <div class="form-group">
              <label for="">Transfer Job Position</label>
             <select v-model="transfer.job" class="form-control">
                <option value="">Select job position</option>
                <option :value="item.id" v-for="item in the_jobs" :key="item.id">{{item.name}}</option>
              </select>
            </div>
          </div> -->

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" :disabled="this.transfer.employees.length==0" class="btn btn-success" @click="submitTransfer" >Transfer Employee</button>
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
  name: "Employeemanager",
  props: [
    "employee_count",
    "employee_year_count",
    "employees",
    "sites",
    "departments",
    "companies",
    "jobs",
    "user_id",
    "role_id",
    "pending"
  ],
  data() {
    return {
      visible: false,
      fullPage: true,
      the_employees: [],
      the_employees1:[],
      the_sites: [],
      the_departments: [],
      the_companies: [],
      the_jobs:[],
      emps: [],
      fromdate: null,
      todate: null,
      employee_counting: 0,
      total_check :0,
      check_all:false,
      transfer: {
        company:"",
        site:"",
        department:"",
        job:"",
        employees:[],
        user_id:this.user_id
      },
      view_emp:false,
      view_text: 'View',
      visible3:false,
      //APP_URL: ""
      //APP_URL: "/smarthr2/public"
    };
  },
  components: {
    Loading
  },
  methods: {
    removeEmpFromList(index) {
      //alert(employee_no);
      this.transfer.employees.splice(index, 1);
    },
    submitTransfer() 
    {
      if(this.transfer.company == "") {
        alert("Please select company");
        return false;
      }
      if(this.transfer.site == "") {
        alert("Please select site");
        return false;
      }
      if(this.transfer.department == "") {
        alert("Please select department");
        return false;
      }
      /*if(this.transfer.job == "") {
        alert("Please select job");
        return false;
      }
      */
      

      //transfer...
      this.visible3 = true;
      axios.post(this.APP_URL+'/api/submitTransferEmployees', this.transfer)
      .then(response => {
        setTimeout(() => {
            this.visible3 = false;
            alert('Operation was successful');
            window.location.reload();
          }, 5000);

      }).catch(error => {
        alert(error);
      })

    },
    viewTransfer(event) {
      event.preventDefault();
      this.view_emp = !this.view_emp;

      if(!this.view_emp) {
        this.view_text ="View";
      }else {
        this.view_text = "Close";
      }
    },
    getTransferEmp() {
      //alert(this.total_check);
      //console.log(this.transfer);
    },
    checkAll() {
      if(!this.check_all) {
         let total = 0;
         this.transfer.employees.length = 0;
          for(let i =0; i < this.the_employees.length; i++) {
             this.the_employees[i]['check_employee']=true;
             total++;
             this.transfer.employees.push(this.the_employees[i]);
          }
         this.total_check = total;
      }else {
        for(let i =0; i < this.the_employees.length; i++) {
             this.the_employees[i]['check_employee']=false;
            // total++;
          }
          this.total_check = 0;
      } 
    },
    checkme(id) {
      this.getTotalChecked();
      console.log(this.the_employees);
    },
    getTotalChecked() {
      let total = 0;
      this.transfer.employees.length = 0;
      for(let i =0; i < this.the_employees.length; i++) {
        if(this.the_employees[i]['check_employee']) {
          total++;
          this.transfer.employees.push(this.the_employees[i]);
        }
      }
      this.total_check = total;
    },
    onCancel() {
      console.log("User cancelled the operation.");
    },
    searchByDate() {
      // alert(this.fromdate);
      // alert(this.todate);
      this.visible = true;
      axios
        .get(
          this.APP_URL +
            "/api/search-by-start-date/" +
            this.fromdate +
            "/" +
            this.todate
        )
        .then(response => {
          //console.log(response);
          this.the_employees = response.data.employees;
          this.employee_counting = response.data.employee_count;
          setTimeout(() => {
            this.visible = false;
          }, 5000);
        });
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
            "/api/get-employee-company/" +
            search_param +
            "/" +
            check
        )
        .then(response => {
          //console.log(response.data);
        
          setTimeout(() => {
              this.the_employees = response.data.employees;
          this.employee_counting = response.data.employee_count;
            this.visible = false;
          }, 3000);
        })
        .catch(error =>{
          alert(error)
        });
    },
    edit_employee(employee_id, is_under_termination) {
       window.location.replace(
        this.APP_URL + "/employee/" + employee_id + "/edit"
      );
    
    }
  },

  created() {
    this.the_employees = JSON.parse(this.employees);
    this.the_employees1 = JSON.parse(this.employees);   
    this.the_sites = JSON.parse(this.sites);
    this.the_companies = JSON.parse(this.companies);
    this.the_departments = JSON.parse(this.departments);
    this.employee_counting = this.employee_count;
    this.the_jobs = JSON.parse(this.jobs);
  }
};
</script>

<style scoped>
.red {
  color:red;
  font-weight:bold;
}
</style>
