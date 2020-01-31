<template>
  <div>

    <button class="btn btn-danger btn-lg"  data-backdrop="static"
        data-keyboard="false"
        data-toggle="modal"
        data-target="#terminate">Terminate employee contract</button>

   <div id="terminate" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <loading
        :active.sync="visible"
        :can-cancel="false"
        :is-full-page="fullPage"
      ></loading>
      <div class="modal-body" v-if="give_reason_page_1">
        <div>
          <p>Please select reason for {{employee_name}}'s termination</p>
          <p>
            <input
              type="radio"
              v-on:change="giveReason"
              name="reason"
              v-model="termination.reason"
              value="1"
            > Resignation
            <br>
            <input
              type="radio"
              v-on:change="giveReason"
              name="reason"
              v-model="termination.reason"
              value="2"
            > Dismissal
            <br>
            <input
              type="radio"
              v-on:change="giveReason"
              name="reason"
              v-model="termination.reason"
              value="3"
            > Redundancy
            <br>
          </p>
        </div>
      </div>
      <div class="modal-body" v-if="give_detail_page_2">
        <div>
          <span
            @click="backToReason"
            class="fa fa-arrow-left text-success"
            style="cursor:pointer"
          >&nbsp;Back</span>
          <p style="font-size:14px;">
            Please provide further detail on {{employee_name}}
            <strong>{{terminated_reason}}</strong>
          </p>
          <p>
            <textarea
              placeholder="Please provide further detail"
              id
              cols="30"
              rows="10"
              v-model="termination.details"
              class="form-control"
            ></textarea>
          </p>
        </div>
        <br>
        <br>
        <div>
          <button class="btn btn-danger" @click="continuDetail">Continue</button>
        </div>
      </div>

      <div class="modal-body" v-if="give_date_page_3">
        <div>
          <span
            @click="backToDetails"
            class="fa fa-arrow-left text-success"
            style="cursor:pointer"
          >&nbsp;Back</span>
          <p style="font-size:14px;">Please select the date {{employee_name}} will be dismissed</p>
          <p>
            <input type="date" class="form-control" v-model="termination.date">
          </p>
        </div>
        <br>
        <br>
        <div>
          <button class="btn btn-danger" @click="continueDate">Continue</button>
        </div>
      </div>
      <div class="modal-body" v-if="give_support_document_page_4">
        <div>
          <span
            @click="backToDate"
            class="fa fa-arrow-left text-success"
            style="cursor:pointer"
          >&nbsp;Back</span>
          <p style="font-size:14px;">
            Please attached any support documents related to {{employee_name}}
            <strong>{{terminated_reason}}</strong>
          </p>
          <p>
            <input
              type="file"
              ref="file"
              id="file"
              class="form-control"
              v-on:change="onChangeFileUpload()"
            >
            <br>
            <input
              type="text"
              class="form-control"
              v-model="terminated_document_title"
              placeholder="Document title"
            >
            <br>
            <br>
            <button class="btn btn-success" @click="uploadFile()">Upload</button>
          </p>
        </div>

        <div class="row">
          <div class="col-md-6" v-for="item in terminated_support_documents" :key="item.id">
            <div class="thumbnail">
              <div class="image view view-first">
                <img
                  title="Click the pencil icon view document"
                  style="width: 100%; display: block;"
                  :src="APP_URL+'/storage/assets/'+ item.file"
                >
                <div class="mask">
                  <p>{{item.created}}</p>
                  <div class="tools tools-bottom">
                    <a
                      target="_blank"
                      :href="APP_URL+'/storage/assets/'+ item.file"
                      title="open this file"
                    >
                      <i class="fa fa-pencil"></i>
                    </a>
                    <a @click="deleteDocument(item.id)" href="#" title="delete this file">
                      <i class="fa fa-times"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="caption">
                <p>{{item.document_title}}</p>
              </div>
            </div>
          </div>
        </div>
        <br>
        <br>
        <div>
          <button class="btn btn-danger btn-lg" @click="terminatecontract">Terminate Contract</button>
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
  name: "Terminateemployee",
  props: ["employee_name", "user_id", "employee_id",],
  data() {
    return {
      termination: {
        reason: 0,
        details: null,
        file: null,
        date: null,
        employee_id: this.employee_id,
        user_id: this.user_id,
        employee_name: this.employee_name,
        terminated_reason: null
      },
      terminated_document_title: null,
      terminated_reason: null,
      give_reason_page_1: true,
      give_detail_page_2: false,
      give_date_page_3: false,
      give_support_document_page_4: false,
      terminated_support_documents: [],
      visible: false,
      fullPage: true,
     // APP_URL: ""
      //APP_URL: "/smarthr2/public"
    };
  },
  components: {
    Loading
  },
  methods: {
    terminatecontract() {
      if (
        confirm(
          "Are you sure you want to terminate this " +
            this.employee_name +
            "'s contract"
        )
      ) {
        this.visible = true;
        axios
          .post(this.APP_URL + "/api/terminatecontract", this.termination)
          .then(response => {
            setTimeout(() => {
              alert(
              this.employee_name +
                "'s contract will be terminated effective from " +
                this.termination.date +
                " once it has been approved."
            );
            window.location.replace(
              this.APP_URL +"/employee/"+this.employee_id+"/edit"
            );
              this.visible = false;
            }, 5000);
            
          })
          .catch(error => {
            alert(error)
          });
      }
    },
    deleteDocument(id) {
      if (confirm("Are you sure you want to delete this document")) {
        this.visible = true;
        axios
          .get(
            this.APP_URL +
              "/api/deleteTerminatedDocument/" +
              id +
              "/" +
              this.employee_id
          )
          .then(response => {
            this.terminated_support_documents =
              response.data.employee_documents;
            setTimeout(() => {
              this.visible = false;
            }, 5000);
          })
          .catch(error => console.log(error));
      }
    },
    uploadFile() {
      if (this.terminated_document_title == null) {
        alert("Please enter document title");
        return false;
      }
      this.visible = true;
      let formdata = new FormData();
      formdata.append("employee_id", this.employee_id);
      formdata.append("document_title", this.terminated_document_title);
      formdata.append("file", this.termination.file);
      axios
        .post(this.APP_URL + "/api/uploadTerminatedDocument", formdata, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          //console.log(response.data);
          this.terminated_support_documents = response.data.employee_documents;
          this.terminated_document_title = null;
          this.termination.file = null;
          setTimeout(() => {
            this.visible = false;
          }, 5000);
        })
        .catch(error => console.log(error));
    },
    onChangeFileUpload() {
      this.termination.file = this.$refs.file.files[0];
    },
    continueDate() {
      if (this.termination.date == null) {
        alert("Please enter terminated date");

      }
     else if(!this.isFutureDate(this.termination.date)) {
        alert("You can only request to terminate employeee now or in future !")

      }  
      else {
       // alert(this.isFutureDate(this.termination.date))
        this.give_date_page_3 = false;
        this.give_support_document_page_4 = true;
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
    backToDetails() {
      this.give_date_page_3 = false;
      this.give_detail_page_2 = true;
    },
    backToDate() {
       this.give_date_page_3 = true;
       this.give_support_document_page_4 = false;
    },
    continuDetail() {
      if (this.termination.details == null) {
        alert("Please enter further details");
      } else {
        this.give_date_page_3 = true;
        this.give_detail_page_2 = false;
      }
    },
    backToReason() {
      this.give_detail_page_2 = false;
      this.give_reason_page_1 = true;
    },
    giveReason() {
      let reason = "";
      switch (this.termination.reason) {
        case "1":
          reason = "Resignation";
          break;
        case "2":
          reason = "Dismissal";
          break;
        case "3":
          reason = "Redundancy";
          break;
      }
      //  alert(reason);
      this.termination.terminated_reason = reason;
      this.terminated_reason = reason;
      this.give_reason_page_1 = false;
      this.give_detail_page_2 = true;
    }
  }
};
</script>

<style>
</style>
