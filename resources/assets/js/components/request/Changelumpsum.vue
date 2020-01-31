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
            data-target="#addNewLumpSum"
            class="btn btn-danger"
          >New Change in Lumpsum Request</button>
          <br>
          <br>
          <span class="count_top">
            <i class="fa fa-user"></i> Change in lumpsum count
          </span>
          <div class="count">{{change_lumpsum_count}}</div>
        </div>

       
      </div>



  <div class="row">
            <div class="text-center">
                        <button @click="getApprovedLumpsum" type="button" :class="approved_lumpsum == true ? 'btn btn-success' : 'btn btn-default' ">Approved Lumpsum</button>
                        <button @click= "getNewLumpsum" type="button" :class="new_lumpsum == true ? 'btn btn-success' : 'btn btn-default' ">New Lumpsum</button>
                      </div>
           
        </div>

      <!-- /top tiles -->

      <div class="row">
        <div class="col-md-12">Search (by request date):</div>
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
                      <select class="form-control"  @change="getcompany($event)">
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
                      <select class="form-control" @change="getsite($event)" >
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
                      <select class="form-control"  @change="getdepartment($event)">
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
              <table id="ddatatable-responsive" class="table table-striped">
                <thead>
                  <tr>
                    <th>created</th>
                    <th>Employee</th>
                    <th>From:</th>
                    <th>To:</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                    <tr v-for="item in the_lumpsum" :key="item.id">
                        <td>{{item.created}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.from}}</td>
                        <td>{{item.toamount}}</td>
                        <td >
                            <button v-if="item.status==0" class="btn btn-danger pull-right"  data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#approveLump" @click="approveLumsump(item.created, item.name, item.from, item.toamount, item.remarks, item.id, item.employee_id)">Approve</button>
                            <div v-if="item.status==1" class="label label-success"> 
                              Approved by {{item.approvedby}}:  {{item.approveddate}}
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

    






    <div id="addNewLumpSum" class="modal fade" role="dialog">
     <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New Lumpsum Request</h4>
      </div>
      <div class="modal-body">
         <loading
        :active.sync="visible3"
        :can-cancel="false"
        :on-cancel="onCancel"
        :is-full-page="fullPage"
      ></loading>
    
        
        <div class="row">

            <div class="form-group">
                <label for="">Site</label>
                <select class="form-control" v-model="lumpsum2.site_id" @change="getChangeSite($event)">
                        <option value="0">Select site</option>
                        <option
                          :value="item.id"
                          v-for="item in the_sites"
                          :key="item.id"
                        >{{item.name}}</option>
                      </select>
                
               
            </div>

            <div class="form-group">
                 <label for="">Employee</label>
                <select name="" id="" class="form-control" v-model="lumpsum2.employee_id">
                    <option value="0">Select employees</option>
                     <option
                          :value="item.id"
                          v-for="item in employees"
                          :key="item.id"
                        >{{item.empname}} / Lumpsum: {{item.salary}}</option>
                </select>
            </div>

            <div class="form-group">
                  <label for="">New Lumpsum Amount ( digit only pls eg 23000)</label>
                <input v-model="lumpsum2.amount" type="text" class="form-control" placeholder="Enter the new lumpsum amount... please enter digit only eg. 23000">
            </div>

            <div>
                <label for="">Reason</label>
                <textarea v-model="lumpsum2.reason" name="" id="" cols="30" rows="10" placeholder="Please state the reason you are requesting for change in lumpsum of the employee above" class="form-control"></textarea>
            </div>

        </div>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" @click="submitChangeLumpsum">Change in lumpsum</button>
      </div>
    </div>



    

  </div>
</div>




  <div id="approveLump" class="modal fade" role="dialog">
     <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Approve Lumsump: {{lumpsum3.name}}</h4>
      </div>
      <div class="modal-body">
         <loading
        :active.sync="visible3"
        :can-cancel="false"
        :on-cancel="onCancel"
        :is-full-page="fullPage"
      ></loading>
    
        
        <div class="row">

        <div class="form-group">
          <label for="">Present lumpsum : <strong> {{lumpsum3.from}} </strong></label>
        </div>
          <div class="form-group">
          <label for="">New lumpsum : <strong> {{lumpsum3.toamount}} </strong></label>
        </div>
          <div class="form-group">
          <label style="color:red" for="">Reason : <strong> {{lumpsum3.remarks}} </strong></label>
        </div>
          

        
          <div>
                <label for="">Remarks</label>
                <textarea v-model="updateLum.remarks" name="" id="" cols="30" rows="10" placeholder="Remarks" class="form-control"></textarea>
            </div>

        </div>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" @click="updateLumpsum">Approve lumpsum</button>
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
export default  {
    name: "Changelumpsum",
    props: ["sites", "companies", "departments", "user_id", "lumpsum", "lumpsum_count"],
    data() {
        return  {
            change_lumpsum_count: 0,
            the_sites: [],
            the_companies: [],
            the_departments: [],
            the_lumpsum: [],
            the_lumpsum1: [],
            visible: false,
            fullPage: true,
            visible3: false,
            fromdate: null,
            todate: null,
            employees: [],
            lumpsum2: {
                site_id: 0,
                employee_id: 0,
                amount: null,
                reason: null,
                user_id: this.user_id
            },

            lumpsum3: {
              id:null,
              created:null,
              name:null,
              from:null,
              toamount:null,
              remarks:null,
            },
            updateLum: {
              id:null,
              remarks:null,
              user_id:this.user_id,
              to: null,
              from:null,
              name:null,
              employee_id:null,
            },
             approved_lumpsum: false,
             new_lumpsum: true,
             check_lumpsum: 0,
        }
    },
    created() {
        this.the_sites = JSON.parse(this.sites);
        this.the_companies = JSON.parse(this.companies);
        this.the_departments = JSON.parse(this.departments);
        this.the_lumpsum = JSON.parse(this.lumpsum);
        this.the_lumpsum1 = JSON.parse(this.lumpsum);
        this.change_lumpsum_count = this.lumpsum_count;

        console.log(this.the_lumpsum);
    },
    components: {
        Loading,
    },
    methods: {
      getApprovedLumpsum() {
        this.approved_lumpsum = true;
        this.new_lumpsum = false;
        this.check_lumpsum = 1;
        this.getTheLumpsum(this.check_lumpsum);
      },
      getNewLumpsum() {
        this.approved_lumpsum = false;
        this.new_lumpsum = true;
        this.check_lumpsum = 0;
        this.getTheLumpsum(this.check_lumpsum);
      },
      getTheLumpsum(check) {
        this.visible = true;
         axios.get(this.APP_URL+'/api/getthelumpsum/'+check)
        .then(response => {
            setTimeout(() => {
                this.visible = false;
                this.the_lumpsum = response.data.lumpsums;
                this.the_lumpsum1 = response.data.lumpsums;
                this.change_lumpsum_count = this.lumpsum_count;

            }, 3000);

        }).catch(error => {
            alert(error);
        })
      },
      updateLumpsum() {
        if(confirm("Are you sure you want to approve this lumpsum ? ")) {
        this.visible3 = true;
        axios.post(this.APP_URL+'/api/updateLumpsum', this.updateLum)
        .then(response => {
            setTimeout(() => {
                this.visible3 = false;
                alert('Operation was successful');
                window.location.reload();
               // this.employees = response.data.employees;
            }, 3000);

        }).catch(error => {
            alert(error);
        })
        }
      },
        approveLumsump(created, name, from, toamount, remarks, id, employee_id) {
          this.lumpsum3.id = id;
          this.lumpsum3.name = name;
          this.lumpsum3.from = from;
          this.lumpsum3.toamount = toamount;
          this.lumpsum3.remarks = remarks;
          this.updateLum.id = id;
          this.updateLum.from = from;
          this.updateLum.to = toamount;
          this.updateLum.name = name;
          this.updateLum.employee_id = employee_id
        },
        getsite(event) {
            let search = event.target.value;
            if(search == "0") {
               this.the_lumpsum = this.the_lumpsum1;
            }else {
             let new_lumpsum = this.the_lumpsum1.filter(r=>r.site==search);
             this.the_lumpsum = new_lumpsum
            }
        },
        getcompany(event) {
            let search = event.target.value;
            if(search == "0") {
               this.the_lumpsum = this.the_lumpsum1;
            }else {
             let new_lumpsum = this.the_lumpsum1.filter(r=>r.company==search);
             this.the_lumpsum = new_lumpsum
            }
        },
         getdepartment(event) {
            let search = event.target.value;
            if(search == "0") {
               this.the_lumpsum = this.the_lumpsum1;
            }else {
             let new_lumpsum = this.the_lumpsum1.filter(r=>r.department==search);
             this.the_lumpsum = new_lumpsum
            }
        },
        getChangeSite(event) {
        let site_id = event.target.value;
        this.visible3 = true;
        axios.get(this.APP_URL+'/api/getEmployeeAsPerSite/'+site_id)
        .then(response => {
            setTimeout(() => {
                this.visible3 = false;
                this.employees = response.data.employees;
            }, 3000);

        }).catch(error => {
            alert(error);
        })
        },
        searchByDate() {
           this.visible = true;
                axios.get(this.APP_URL+'/api/getChangeLumpsumByDates/'+this.fromdate+'/'+this.todate+'/'+this.check_lumpsum)
                .then(response => {
                    setTimeout(() => {
                        this.visible = false;
                       // alert('Operation was successful');
                        this.the_lumpsum = response.data.lumpsum;
                        this.the_lumpsum1 = response.data.lumpsum;
                        //console.log(this.the_lumpsum);
                    }, 3000);
                }).catch(error => {
                     this.visible3 = false;
                    alert(error);
            })
        },
        onCancel() {
           
        },
        submitChangeLumpsum() {
            if(this.lumpsum2.site_id == 0) {
                alert("Please select site");
            }else if(this.lumpsum2.employee_id == 0) {
                alert('Please select employee... you will need to select site first...');
            }else if(this.lumpsum2.amount == null || this.lumpsum2.amount <= 0) {
                alert('new lumpsum can not be empty and must be greater than zero');
            }else if(this.lumpsum2.reason == null) {
                alert('Please enter the reason why you are requesting for change in lumpsum of this employee');
            }else {
                this.visible3 = true;
                axios.post(this.APP_URL+'/api/changeLumpsum', this.lumpsum2)
                .then(response => {
                    setTimeout(() => {
                        this.visible3 = false;
                        alert('Operation was successful');
                        this.lumpsum2.site_id = 0;
                        this.lumpsum2.employee_id = 0;
                        this.lumpsum2.amount = null;
                        this.lumpsum2.reason = null;
                        this.the_lumpsum = response.data.lumpsums;
                        this.the_lumpsum1 = response.data.lumpsums;
                        this.change_lumpsum_count = this.lumpsum_count;
                    }, 3000);

                }).catch(error => {
                     this.visible3 = false;
                    alert(error);
                })
            }

        }
        
    }
}

</script>


<style>
</style>