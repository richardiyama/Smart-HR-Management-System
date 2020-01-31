<template>
  <div>
    <button class="btn btn-success btn-lg"  data-backdrop="static"
data-keyboard="false"
data-toggle="modal"
data-target="#approve">Approve Employee Termination</button>
&nbsp; &nbsp;
<button class="btn btn-danger btn-lg"  data-backdrop="static"
        data-keyboard="false"
        data-toggle="modal"
        data-target="#reject">Reject Employee Termination</button>

        
    <div id="approve" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <loading :active.sync="visible" :can-cancel="false"></loading>
            <p>
              Are you sure you would like to approve the termination of
              <strong>{{employee_name}}</strong>'s contract ?
            </p>
            <div class="form-group">
              <label for="">Approved Date</label>
              <input type="date" class="form-control" v-model="approval.terminated">
            </div>
            <br>
            <button class="btn btn-success" @click="approve">Approve Termination</button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div id="reject" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4
              class="modal-title"
            >Please explain the reason why you are rejected employee termination</h4>
          </div>
          <div class="modal-body">
            <loading :active.sync="visible1" :can-cancel="true"></loading>
            <p>
              <textarea
                placeholder="Please explain the reason why you are rejected employee termination"
                class="form-control"
                v-model="rejection.reason"
                id
                cols="30"
                rows="10"
              ></textarea>
            </p>
            <br>
            <button class="btn btn-danger" @click="reject">Reject Employee Termination</button>
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
  name: "Approvetermination",
  props: ["employee_name", "employee_id", "user_id", "terminated_id", "termination_date", "terminated_id"],
  data() {
    return {
      rejection: {
        reason: null,
        employee_id: this.employee_id,
        user_id: this.user_id,
        terminated_id: this.terminated_id
      },
      visible: false,
      visible1: false,
      

      approval:{
        employee_id: this.employee_id,
        user_id: this.user_id,
        terminated_id: this.terminated_id,
        terminated:this.termination_date
      }
      // APP_URL: ""
       //APP_URL: "/smarthr2/public"
    };
  },
  components: {
    Loading
  },
  methods: {
    reject() {
      if (
        confirm("Are you sure you want to reject this employee termination ?")
      ) {
        this.visible1 = true;
        axios
          .post(
            this.APP_URL + "/api/reject-employee-termination",
            this.rejection
          )
          .then(response => {
            setTimeout(() => {
              alert(
              "You have rejected the termination of " +
                this.employee_name +
                "'s contract"
            );
            window.location.replace(this.APP_URL + "/employees");
              this.visible1 = false;
            }, 3000);
            
          })
          .catch(error => {
            alert(error)
          });
      }
    },
     isFutureDate(idate){
    var now = new Date();
    var before = new Date(idate);
    var result = before.getDate()-now.getDate();
    if (result < 0) {
      // selected date is in the past
      return false
    }else {
      return true;
    }
    
  
    },
    approve() {
      if(this.approval.terminated == null || this.approval.terminated == "") {
        alert('Please enter approval date');
        return false;
      }
      if(!this.isFutureDate(this.approval.terminated)) {
        alert('You can only terminate an employee now or future');
        return false;
      }
      if (
        confirm(
          "Are you sure you want to terminate " +
            this.employee_name +
            "'s contract ? "
        )
      ) {
        this.visible = true;
        axios
          .post(
            this.APP_URL +
              "/api/approve-termination", this.approval
          )
          .then(response => {
            
            setTimeout(() => {
              alert(
              "You have approved the termination of " +
                this.employee_name +
                "'s contract. His/Her employment will be terminated on " +
                this.approval.terminated
            );

             window.location.replace(
              this.APP_URL +
                "/terminated-employee/" +
                this.terminated_id +
                "/view"
            );

              this.visible = false;
            }, 3000);

           
          })
          .catch(error => {
            alert(error)
          });
      }
    }
  }
};
</script>

<style>
</style>
