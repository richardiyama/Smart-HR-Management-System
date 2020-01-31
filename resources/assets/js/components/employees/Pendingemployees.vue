<template>

    <div>

        <div class="row">
             <a :href="APP_URL+'/employees'" style="" title="Back to active employee"><span class="fa fa-arrow-left" style="color:green; font-size:15px;"> Back to Active Employee</span></a> <br><br>
            <div class="col-md-12">
               
                <h2>New Employee | <span class="fa fa-circle text-danger" style="font-size:9px;"></span> <span style="font-size:12px;" class="text-muted"> {{total_pending}} Pending Employees</span></h2>  
            </div>
        <hr>
        </div>

        <div class="row">
            <div class="text-center">
                        <button @click = "getNewEmployees" type="button" :class="all_new_employees == true ? 'btn btn-success' : 'btn btn-default' ">All New Employees</button>
                        <button @click= "getPendingEmployees" type="button" :class="pending_new_employees == true ? 'btn btn-success' : 'btn btn-default' ">Pending New Employees</button>
                      </div>
           
        </div>


         <div class="row">
      <loading
        :active.sync="visible"
        :can-cancel="false"
        :on-cancel="onCancel"
        :is-full-page="fullPage"
      ></loading>

      <!-- top tiles -->
      
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
               <table id="datatable-responsive" class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr>
                    <th>Employee No.</th>
                    <th>Name</th>
                    <th>Company</th>
                    
                    <th>Site</th>
                    <th>Department</th>
                    <th>Status</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in the_employees" :key="item.id"  @click="edit_employee(item.employee_id)"
                      style="cursor:pointer"
                      title="Click to approve">
                    <td> <span class="fa fa-circle text-danger" style="font-size:9px;"></span> &nbsp; {{item.employee_no}}</td>
                    <td>  {{item.name}}</td>
                    <td
                    >{{item.company}}</td>
                    
                    <td
                    >{{item.site}}</td>
                    <td
                    >{{item.department}}</td>
                    <td>
                      <div v-if="item.active == 1">
                         <label for="" class="label label-success">Active</label>
                      </div>
                      <div v-else>
                        <label for="" class="label label-warning" v-if="item.rejection==0">Pending</label>
                         <label for="" class="label label-danger" v-if="item.rejection==1">Rejected</label>
                      </div>
                        
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <br>
    </div>

    </div>

</template>



<script>
// Import component
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";
 export default {
     name: "Pendingemployees",
     props: [
    "employee_count",
    "employee_year_count",
    "employees",
    "sites",
    "departments",
    "companies",
    "jobs",
    "total_pending",
    "new_employees",
  ],
  data() {
      return  {
        visible: false,
        fullPage: true,
        the_employees: [],
        the_employees1: [],
        the_new_employees:[],
        the_sites: [],
        the_departments: [],
        the_companies: [],
        the_jobs:[],
        emps: [],
        fromdate: null,
        todate: null,
        employee_counting: 0,
        all_new_employees: false,
        pending_new_employees: true,
      }
  },
  components: {
      Loading,
  },
  methods: {

    getNewEmployees() {
      this.all_new_employees = true;
      this.pending_new_employees =  false;
      this.visible = true;
      axios
        .get(this.APP_URL +"/api/getMonthPendningNew")
        .then(response => {
          //console.log(response);
         
          setTimeout(() => {
            this.visible = false;
             this.the_new_employees = response.data.employees;
             this.employee_counting = response.data.employee_count;
            
             this.the_employees = this.the_new_employees;
          }, 5000);
        });
       
    },
    getPendingEmployees() {
        this.all_new_employees = false;
        this.pending_new_employees =  true;
        this.the_employees = this.the_employees1;
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
         
          setTimeout(() => {
             this.the_employees = response.data.employees;
             this.employee_counting = response.data.employee_count;
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
    edit_employee(employee_id) {
      window.location.replace(
        this.APP_URL + "/employee/" + employee_id + "/approve"
      );
    }
  },
  created() {
    this.the_employees = JSON.parse(this.employees);
    this.the_employees1 = JSON.parse(this.employees); 
    this.the_new_employees = JSON.parse(this.new_employees); 
    this.the_sites = JSON.parse(this.sites);
    this.the_companies = JSON.parse(this.companies);
    this.the_departments = JSON.parse(this.departments);
    this.employee_counting = this.employee_count;
    this.the_jobs = JSON.parse(this.jobs);
  }

  
  

 }

</script>

<style></style>
