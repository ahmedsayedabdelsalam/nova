<template>
  <div class="mt-2 mb-2">
    <form action>
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="name">Name</label>
          <input v-model="form.name" type="text" class="form-control" id="name" />
        </div>
        <div class="form-group col-md-2">
          <label for="inputType">Type</label>
          <select v-model="form.type" id="inputType" class="form-control">
            <option v-for="(type,index) in types" :key="index" :value="type">{{type}}</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="inputUploadedBy">Uploaded by</label>
          <select v-model="form.uploaded_by" id="inputUploadedBy" class="form-control">
            <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="inputDate">Date</label>
          <input v-model="form.date" type="date" class="form-control" id="inputDate" />
        </div>
        <div class="form-group col-md-2">
          <label for="inputStatus">Status</label>
          <select v-model="form.status" id="inputStatus" class="form-control">
            <option v-for="(state,index) in status" :key="index" :value="state">{{state}}</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <b-button variant="warning" class="btn btnClear btn-warning" @click="removeParams()">Clear</b-button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import { mapMutations } from "vuex";
import api from '../api/api.js'
export default {
  async mounted() {
    const response = await api.users.all();
    this.users = response.data.data;
  },
  data() {
    return {
      form: {
        name: null,
        type: null,
        uploaded_by: null,
        date: null,
        status: null
      },
      types: ["video", "image"],
      users: [],
      status: ["attached", "not attached"]
    };
  },
  methods: {
    ...mapMutations({
      updateQueryParams: "mediaLibrary/updateQueryParams"
    }),
    removeParams(){
      this.form.name=null,
      this.form.type= null,
      this.form.uploaded_by= null,
      this.form.date= null,
      this.form.status= null
    }
  },
  watch: {
    "form.name": _.debounce(function(newVal, oldVal) {
      this.updateQueryParams({ query: this.form });
    }, 1000),
    "form.type"(newVal, oldVal) {
      this.updateQueryParams({ query: this.form });
    },
    "form.uploaded_by"(newVal, oldVal) {
      this.updateQueryParams({ query: this.form });
    },
    "form.date"(newVal, oldVal) {
      const dateArray = newVal.split("-");
      const newDate = dateArray[0] + "-" + dateArray[1] + "-" + dateArray[2];
      this.updateQueryParams({
        query: {
          ...this.form,
          date: newDate
        }
      });
    },
    "form.status"(newVal, oldVal) {
      this.updateQueryParams({ query: this.form });
    }
  }
};
</script>
<style>
.btnClear{
  position: relative;
    bottom: -31px;
    width: 100%;
}
</style>