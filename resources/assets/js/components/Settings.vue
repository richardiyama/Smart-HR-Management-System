<template>
    <div>
        <div class="row">
            <loading
        :active.sync="visible"
        :can-cancel="false"
      ></loading>
    <div class="col-md-2"></div>
    
    <div class="col-md-8 col-sm-8 col-xs-12">
 
 
      <div class="x_panel"> <br>
       
        <div class="x_title">
       
       
          <h2>User Settings</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
               <div class="col-md-6">
                   <span class="text-muted">Email Address</span> <br>
               <strong>{{email}}</strong>
               </div>
               <div class="col-md-6">
                    <span class="text-muted"></span> <br>
                   
                </div>
           </div>
           <br>
           <div class="row">
            <div class="col-md-6">
                <span class="text-muted">Password</span> <br>
            <strong>*****************</strong>
            </div>
            <div class="col-md-6">
                 <span class="text-muted"></span> <br>
                 <a href="#" data-toggle="modal" data-target="#myModal" title="Click to change password">Change Password</a>
                
             </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">

                <input type="checkbox" @change="update_setting" v-model="the_setting.confirmation_email"> &nbsp; Receive emails for all confirmation and approval notification. <br>
                <input type="checkbox" @change="update_setting" v-model="the_setting.activity_email"> &nbsp; Receive emails notification for all activity on Smart HR account.
            </div>
            
        </div>
        <br>
           
            </div>
      </div>

      <div class="x_panel"> <br>
       
        <div class="x_title">
       
       
          <h2>Payroll Settings</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content" v-if="role_id==1">
            <div class="row">
               <div class="col-md-6">
                   <span class="text-muted">Pension %</span> <br>

                    <strong> <input type="number" v-model="the_setting.pension" v-if="pension"> <span v-if="!pension">{{the_setting.pension}}%</span> of all employee's salaries</strong> <br>
                    <a href="#" @click="editpension" v-if="!pension">Edit</a>
                    <a href="#" @click="update_setting" v-if="pension">Save</a>   
               </div>
               <div class="col-md-6">
                    <span class="text-muted">Tax %</span> <br>
                   <strong><input type="number" v-model="the_setting.tax" v-if="tax"> <span v-if="!tax">{{the_setting.tax}}%</span> of all employee's salaries</strong> <br>
               <a href="#" @click="edittax" v-if="!tax">Edit</a>
                <a href="#" @click="update_setting" v-if="tax">Save</a>
                </div>
           </div>     
            </div>
      </div>


      



      


     
    </div>
    <div class="col-md-2"></div>
  </div>

  <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
           <loading
        :active.sync="visible2"
        :can-cancel="false"
      ></loading>
       <div class="form-group">
           <label for="">Old Pasword</label>
           <input type="password" placeholder="Old password" class="form-control" v-model="pass.old_password">
       </div>
        <div class="form-group">
           <label for="">New Pasword</label>
           <input type="password" placeholder="New Password" class="form-control" v-model="pass.new_password">
       </div>
        <div class="form-group">
           <button class="btn btn-success" @click="change_password">Save</button>
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
    name:"Settings",
    props:["user_id", "email", "setting", "role_id"],
    data() {
        return {

            the_setting: {},
            pension:false,
            tax:false,
            tax_text:'Edit',
            pension_text: 'Edit',
            visible:false,
            visible2:false,

            pass: {
                old_password: null,
                new_password: null,
                email:this.email
            }
        }
    },
     components: {
    Loading
  },
    created() {
        this.the_setting = JSON.parse(this.setting);
    },
    methods:{
        change_password() {
            if(this.pass.old_password == null) {
                alert("Please enter old password");
                return false;
            }
            if(this.pass.new_password == null) {
                alert("Please enter old password");
                return false;
            }
            this.visible2 = true;
            axios.post(this.APP_URL+'/api/changePassword', this.pass)
            .then(response => {
             console.log(response.data);
             setTimeout(() => {
                 if(!response.data.check) {
                     alert("The old password is wrong, please try again !");
                 }else {
                     alert("Operation was successful, The changes will take effect in your next login");
                 }
                this.visible2 = false;
             }, 3000);
            }).catch(error => {
                alert(error);
            })

        },
        update_setting() {
            console.log(this.the_setting);
            this.visible=true;
            this.the_setting.user_id = this.user_id;
            axios.post(this.APP_URL+'/api/UpdateSettings', this.the_setting)
            .then(response => {
             setTimeout(() => {
             alert('Setting updated successfully');
             this.the_setting = response.data.setting;
             this.visible = false;
             this.pension = false;
             this.tax = false;
          }, 3000);

            }).catch(error => {
                alert(error)
            })
        },
        edittax() {
            this.tax = !this.tax;
            
        },
        editpension() {
            this.pension = !this.pension;
        }
    }

}

</script>

<style>

</style>
