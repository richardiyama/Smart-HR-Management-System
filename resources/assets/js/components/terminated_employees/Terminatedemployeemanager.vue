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
          <span class="count_top">
            <i class="fa fa-user"></i> Number of terminated employees
          </span>
          <div class="count">{{employee_counting}}</div>
          <a :href="APP_URL+'/employees/pending-termination'">
            <small>
              <span class="fa fa-circle text-danger"></span>
              <span style="font-size:12px;">{{employee_count_awaiting}}</span> awaiting approval
            </small>
            <span class="pull-right">
              <i class="" style="color:green"></i>
            </span>
          </a>
        </div>
      </div>
      <!-- /top tiles -->

      <div class="row">
        <div class="col-md-12">Search (by terminated date):</div>
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
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in the_employees" :key="item.id">
                  
                      <td
                      @click="edit_employee(item.id)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.employee_no}}</td>
                    <td
                      @click="edit_employee(item.id)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.name}}</td>
                    <td
                      @click="edit_employee(item.id)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.company}}</td>
                  
                    <td
                      @click="edit_employee(item.id)"
                      style="cursor:pointer"
                      title="Click to edit"
                    >{{item.site}}</td>
                    <td
                      @click="edit_employee(item.id)"
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
    "employee_count_awaiting",
    "employees",
    "sites",
    "departments",
    "companies"
  ],
  data() {
    return {
      visible: false,
      fullPage: true,
      the_employees: [],
      the_employees1: [],
      the_sites: [],
      the_departments: [],
      the_companies: [],
      emps: [],
      fromdate: null,
      todate: null,
      employee_counting: 0,
     // APP_URL: ""
      //APP_URL: "/smarthr2/public"
    };
  },
  components: {
    Loading
  },
  methods: {
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
            "/api/search-by-start-date1/" +
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
          }, 3000);
        }).catch(error => {
          alert(error)
        })
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
            "/api/get-employee-company1/" +
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
        .catch(error => {
          alert(error)
        });
    },
    edit_employee(id) {
      window.location.replace(
        this.APP_URL + "/terminated-employee/" + id + "/view"
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
  }
};
</script>

<style>
</style>
