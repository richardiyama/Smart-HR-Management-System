<template>
<div>
<div  >
  <button class="btn btn-success btn-lg"   data-backdrop="static"
data-keyboard="false"
data-toggle="modal" 
data-target="#confirm2">Confirm New Employee</button>

<button class="btn btn-danger btn-lg"  data-backdrop="static"
    data-keyboard="false"
    data-toggle="modal"
    data-target="#reject">Reject New Employee</button>
</div>
  

    
    <div id="confirm2" class="modal fade" role="dialog">
    <div class="modal-dialog">
         <loading
        :active.sync="visible"
        :can-cancel="false"
        :is-full-page="fullPage"
      ></loading>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div>
          <h2>Are you sure you would like to confirm <strong>{{employee_name}}</strong> as a new employee</h2>
          </div>
         </div>
   
        <div class="modal-footer">
          <button type="button" class="btn btn-success" @click="confirmEmp">Confirm New Employee</button>
        </div>
      </div>
    </div>
    </div>



    <div id="reject" class="modal fade" role="dialog">
    <div class="modal-dialog">
         <loading
        :active.sync="visible2"
        :can-cancel="false"
        :is-full-page="fullPage"
      ></loading>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div>
          <h2>Please explain why you have rejected <strong>{{employee_name}}</strong> as a new employee</h2>
          <div class="form-group">
              <textarea class="form-control" v-model="rej.remarks" rows="5" cols="10" placeholder="Enter a explanation here" >
              </textarea>
          </div>
          </div>
         </div>
   
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" @click="rejectEmp">Reject New Employee</button>
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
    name: "Confirmemployee",
    props: ["employee_name", "employee_id", "user_id", "role_id"],
    data() {
        return {
            visible:false,
            fullPage: true,
            visible2:false,
            emp: {
                employee_id:this.employee_id,
                user_id:this.user_id
            },
            rej: {
                employee_id:this.employee_id,
                user_id:this.user_id,
                remarks:null,
            }
        }
    },
    components: {
        Loading,
    },
    methods: {
        rejectEmp() {
            if(this.rej.remarks== null) {
                alert("Please state the reason why you are rejecting this employee");
                return false;
            }
           if(confirm("Proceed to employee rejection")) {
                this.visible2 = true;
                axios.post(this.APP_URL+'/api/rejectNewEmployee', this.rej)
                .then(response=>{
                    setTimeout(() => {
                    this.visible2 = false;
                    alert('Operation was successful');
                    window.location.href=this.APP_URL+'/employees/pending';
                }, 5000);
                }).catch(error => {
                    alert(error);
                })
           }
        },
        confirmEmp() {
            if(confirm("Proceed to activation of "+ this.employee_name)) {
                this.visible = true;
                axios.post(this.APP_URL+'/api/confirmNewEmployee', this.emp)
                .then(response=>{
                    setTimeout(() => {
                    this.visible = false;
                    alert('Operation was successful');
                    window.location.reload();
                }, 5000);
                }).catch(error => {
                    alert(error);
                })
            }
        }
    }
}
</script>

<style></style>