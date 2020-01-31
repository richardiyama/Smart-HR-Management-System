<template>
  <div>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New Employee BVN</h4>
      </div>
      <div class="modal-body">
         <loading
        :active.sync="visible2"
        :can-cancel="false"
      ></loading>
        <form v-if="check == 0">
          <input
            type="text"
            class="form-control"
            maxlength="11"
            v-model="employee.bvn"
            placeholder="Enter New Employee BVN"
          >
          <span v-if="bvn_length < 11 || bvn_length > 11" style="color:red">BVN must be 11 digit</span>
          <br>
          <button :disabled="!employee.bvn || employee.bvn.length < 11" type="submit" class="btn btn-success" @click="save">Add New Employee</button>
        </form>
        <div v-if="check == 1">
          <a href="#" @click="backme">
            <span class="fa fa-arrow-left" style="color:green">&nbsp; Back</span>
          </a>
          <br>
          <span style="color:red">
            Existing employee 
            <strong>{{employee.name}}</strong>
            found with BVN {{employee.bvn}} 
            <br>
            
          </span>

          <button class="btn btn-success btn-block" @click="empDetail">Preload Existing Employee with details</button>
          <br>
          <button class="btn btn-success btn-block" @click="save1">Add as New Employee</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
  props: ["user_id"],
  name: "Bvnnewemployee",
  data() {
    return {
      employee: {
        bvn: null,
        user_id: this.user_id,
        name:null,
        blacklist:null,
        
      },
      bvn_length: 0,
      name: null,
      blacklist:null,
      employee_id: null,
      check: 0,
      
      visible2:false,
     // APP_URL: ""
       //APP_URL: "/smarthr2/public"
    };
  },
  components: {
        Loading,
    },

  methods: {
    empDetail() {
      //alert(this.employee_id);
      window.location.href=this.APP_URL+'/employee-detail/'+this.employee_id;
    },
    backme(e) {
      e.preventDefault();
      this.check = 0;
    },
    save1() {
      alert('Click back button and change BVN first, One employee per hbvn, and if the same person, use preload existing employee operations !.');
      /*window.location.replace(
        this.APP_URL + "/employee-detail/" + this.employee_id
      );
      */
    },
    save(e) {
      e.preventDefault();
      this.visible2 = true;
      if (this.employee.bvn.length < 11 || this.employee.bvn.length > 11) {
        this.bvn_length = this.employee.bvn.length;
        return false;
      }
     // e.target.disabled = true;
      axios
        .post(this.APP_URL + "/api/empoyeebvn", this.employee)
        .then(response => {
           setTimeout(() => {
            this.visible2 = false;
          //  e.target.disabled = false;
            this.check = response.data.check;
            this.employee.name = response.data.name;
            //this.blacklist = response.data.blacklist;
            this.employee_id = response.data.employee_id;
            //this.user_id = response.data.user_id;
            if(response.data.check == 0) {
               alert('Proceed to Employee Personal Details');
                window.location.replace(
                 this.APP_URL + "/employee-detail/" + this.employee_id
                ); 
            }
           }, 5000);
        
         /* window.location.replace(
              this.APP_URL + "/employee-detail/" + response.data.employee_id
            );
            */
        })
        .catch(error =>{
          alert(error)
        });
    }
  }
};
</script>

<style>
</style>
