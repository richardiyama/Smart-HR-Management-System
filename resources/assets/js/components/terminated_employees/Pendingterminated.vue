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
            <button @click="getTerminated" :class="total_check > 0 ? 'btn btn-success' : 'btn btn-secondary'" :disabled="total_check == 0" data-toggle="modal" data-target="#myModal">Bulk approval of termination</button>
         
          <br>
          
          <span class="count_top">
            <i class="fa fa-user"></i> Number of Pending employees
          </span>
          <div class="count">{{employee_counting}}</div>
        </div>

    
      </div>
      <!-- /top tiles -->

      <div class="row">
        
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

                <tbody style="color:red">
                  <tr v-for="item in the_employees" :key="item.id">
                    <td>
                      <input type="checkbox" v-model="item.check_employee" @change="checkme(item.id)">
                    </td>
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



    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="width:1200px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Termination Approval : {{this.terminated.employees.length}} employee(s)</h4>
      </div>
      <div class="modal-body">
         <loading
        :active.sync="visible3"
        :can-cancel="false"
        :on-cancel="onCancel"
        :is-full-page="fullPage"
      ></loading>
        <a class="alert alert-info" style="color:#fff" href="#" @click="viewTransfer($event)">{{view_text}} {{this.terminated.employees.length}} employee(s)</a><br><br>
        <span style="color:red">Terminated date and reason are compulsory fields.</span>
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
                <th>Terminated Date</th>
                <th>Reason</th>
                <th></th>
              </thead>
              <tbody>
                <tr v-for="(item, index) in terminated.employees" :key="item.employee_no" >
                  <td>{{item.employee_no}}</td>
                  <td>{{item.name}}</td>
                  <td>{{item.company}}</td>
                  <td>{{item.site}}</td>
                  <td>{{item.department}}</td>
                  <td><input class="form-control" type="date"v-model="item.terminated_date"></td>
                  <td><input type="text" class="form-control" v-model="item.reason" placeholder="Reason" /></td>
                  <td><span style="cursor:pointer" @click="removeEmpFromList(index)" class="fa fa-times text-danger" :title="'Remove '+ item.name + ' from the list !'"></span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" :disabled="this.terminated.employees.length==0" class="btn btn-success" @click="approveBulkTermination"  >Approve Employee(s) Termination</button>
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
    "employee_count_awaiting",
    "employees",
    "sites",
    "departments",
    "companies",
    "user_id"
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
      check_all:false,
      total_check :0,
      visible3: false,
      employee_counting: 0,
      view_emp:true,
      view_text: 'View',
     // APP_URL: ""
      //APP_URL: "/smarthr2/public"
      terminated: {
        employees:[],
        user_id:this.user_id,

      },
    };
  },
  components: {
    Loading
  },
  methods: {
    approveBulkTermination() {
      //check if the email field is filled
      //alert('did you get here');
     // console.log(this.terminated.employees);
     let check=0;
      this.terminated.employees.forEach(element=>{
        if(element.terminated_date == "" || element.reason == "") {
          check++;
        }
      })
      if(check > 0 ) {
        alert("Terminated date and reason are compulsory fields");
        return false;
      }
      if(confirm("Are you sure you want to perform this operation")) {
         this.visible3 = true;
          axios
            .post(
              this.APP_URL +
                "/api/approve-bulk-termination/", this.terminated)
            .then(response => {
              setTimeout(() => {
                alert("Operation was successful");
                this.visible3 = false;
                window.location.reload();
              }, 3000);
            }).catch(error => {
              alert(error)
            })
      }
     
    },
     removeEmpFromList(index) {
      //alert(employee_no);
      this.terminated.employees.splice(index, 1);
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
    getTerminated() {
      console.log(this.terminated);
    },
      checkAll() {
      if(!this.check_all) {
         let total = 0;
         this.terminated.employees.length = 0;
          for(let i =0; i < this.the_employees.length; i++) {
             this.the_employees[i]['check_employee']=true;
             total++;
             this.terminated.employees.push(this.the_employees[i]);
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
      //console.log(this.the_employees);
    },
    getTotalChecked() {
      let total = 0;
      this.terminated.employees.length = 0;
      for(let i =0; i < this.the_employees.length; i++) {
        if(this.the_employees[i]['check_employee']) {
          total++;
          this.terminated.employees.push(this.the_employees[i]);
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
        this.APP_URL + "/employee/" + id + "/edit"
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

<style scoped>

</style>
