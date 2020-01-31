<template>
  <div>
    <div class="row" v-for="item in contacts" :key="item.di">
      <div class="col-md-6">
        <span class="text-muted">Emergency Contact</span>
        <br>
        <input type="text" class="form-control" v-model="item.name">
      </div>
      <div class="col-md-6">
        <span class="text-muted">Emergency contact phone number</span>
        <br>
        <input type="text" class="form-control" v-model="item.phone">
      </div>
      <div class="pull-right">
        <span
          style="cursor:pointer"
          @click="edit(item.id, item.name, item.phone)"
          class="text-muted"
          title="edit this contact"
        >
          <span class="fa fa-pencil"></span>
        </span>
        <span
          style="cursor:pointer"
          @click="deleteEmergency(item.id)"
          class="text-muted"
          title="delete this contact"
        >
          <span class="fa fa-times"></span>
        </span>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <span class="text-muted">Emergency Contact</span>
        <br>
        <input
          type="text"
          placeholder="Enter employee emergency name"
          class="form-control"
          v-model="contact.name"
        >
      </div>
      <div class="col-md-6">
        <span class="text-muted">Emergency contact phone number</span>
        <br>
        <input
          type="text"
          placeholder="Enter employee emrgency phone number"
          class="form-control"
          v-model="contact.phone"
        >
      </div>
    </div>
    <br>
    <span style="cursor:pointer" @click="add" title="add another emergency contact">
      <span class="fa fa-plus">&nbsp; Add another emergency contact</span>
    </span>
  </div>
</template>

<script>
export default {
  name: "Emergencycontact",
  props: ["employee_id"],
  data() {
    return {
      contact: {
        name: null,
        phone: null,
        employee_id: this.employee_id
      },
      contacts: [],
     // APP_URL: ""
      // APP_URL: "/smarthr2/public"
    };
  },
  methods: {
    add() {
      if (this.contact.name == null) {
        alert("enter name");
      } else if (this.contact.phone == null) {
        alert("enter phone");
      } else {
        axios
          .post(this.APP_URL + "/api/add-new-emergency-contact", this.contact)
          .then(response => {
            this.contacts = response.data.contacts;
            alert("Operation was successful");
            this.contact.name = null;
            this.contact.phone = null;
          });
      }
    },
    edit(id, name, phone) {
      axios
        .get(
          this.APP_URL +
            "/api/edit-employee-emergency/" +
            id +
            "/" +
            name +
            "/" +
            phone +
            "/" +
            this.employee_id
        )
        .then(response => {
          this.contacts = response.data.contacts;
          alert("Operation was successful");
        })
        .catch(error => console.log(error));
    },
    deleteEmergency(id) {
      if (confirm("Are you sure you want to delete this contact")) {
        axios
          .get(
            this.APP_URL +
              "/api/delete-employee-emergency/" +
              id +
              "/" +
              this.employee_id
          )
          .then(response => {
            this.contacts = response.data.contacts;
            alert("Operation was successful");
          })
          .catch(error => console.log(error));
      }
    }
  },
  created() {
    //get list of emergency
    axios
      .get(this.APP_URL + "/api/employee-emergency/" + this.employee_id)
      .then(response => {
        this.contacts = response.data.contacts;
      })
      .catch(error => console.log(error));
  }
};
</script>

<style>
</style>
