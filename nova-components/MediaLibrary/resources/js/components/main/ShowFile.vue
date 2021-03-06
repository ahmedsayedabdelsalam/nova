<template>
  <div class="file" v-if="image">
    <div class="card">
      <img class="card-img-top" v-if="imageType" :src="image.url" :alt="image.data.name" />
      <video width="100%" v-if="videoType" controls>
        <source :src="image.url" type="video/mp4" />
        <source :src="image.url" type="video/ogg" />Your browser does not support the video tag.
      </video>
      <div class="card-body">
        <h5 class="card-title">{{image.data.name}}</h5>
        <p>
          <b>Size:</b>
          {{image.data.size/1000}}Kb
        </p>
        <p>
          <b>Uploaded at:</b>
          {{image.data.created_at}}
        </p>
        <p v-if="image.data.custom_properties.user">
          <b>Uploaded by:</b>
          {{image.data.custom_properties.user.name}}
        </p>
        <p>
          <b>Type:</b>
          {{image.data.mime_type}}
        </p>
        <p>
          <b>Url:</b>
          {{image.url}}
        </p>
        <b-form>
          <b-form-group id="input-group-1" label="Title" label-for="input-1">
            <b-form-input
              id="input-1"
              v-model="form.title"
              type="text"
              name="title"
              :placeholder="image.data.name"
            ></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-2" label="Description" label-for="input-2">
            <b-form-textarea
              id="textarea"
              v-model="form.description"
              :placeholder="image.data.custom_properties.information?image.data.custom_properties.information.description:null"
              rows="3"
              name="description"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-form-group id="input-group-3" label="Caption" label-for="input-3">
            <b-form-textarea
              id="textarea"
              v-model="form.caption"
              :placeholder="image.data.custom_properties.information?image.data.custom_properties.information.caption:null"
              rows="3"
              name="caption"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-form>

        <hr />
        <h2>Related By</h2>
        <template v-for="(relation,index) in relations">
          <div :key="index" v-if="relation.value.length">
            <span class="badge badge-primary mt-1 mb-2">{{relation.name}}</span>
          </div>
        </template>

        <hr />
        <b-button variant="danger" @click="deleteImg">Delete</b-button>
        <b-button variant="info" @click="downloadWithVueResource">Download</b-button>
      </div>
    </div>
  </div>
</template>
<script>
import _ from "lodash";
import axios from "axios";
import { mapGetters, mapActions } from "vuex";
export default {
  mounted() {
    if (this.image) {
      this.isLoaded = false;
      this.form.title = this.image.data.name;

      this.form.description = this.image.data.custom_properties.information
        ? this.image.data.custom_properties.information.description
        : null;
      this.form.caption = this.image.data.custom_properties.information
        ? this.image.data.custom_properties.information.caption
        : null;
    }
  },
  data() {
    return {
      isLoaded: true,
      showedRelation: null,
      form: {
        title: null,
        description: null,
        caption: null
      }
    };
  },
  methods: {
    ...mapActions({
      deleteImage: "mediaLibrary/deleteImage",
      updateShowedImage: "mediaLibrary/updateShowedImage"
    }),
    getModelName(modelName) {
      return modelName.split("\\")[2];
    },

    deleteImg() {
      if (this.image) {
        this.deleteImage({
          imageId: this.image.data.id
        });
      }
    },
    forceFileDownload(response) {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", this.image.data.file_name); //or any other extension
      document.body.appendChild(link);
      link.click();
    },

    downloadWithVueResource() {
      axios({
        method: "get",
        url: this.image.url,
        responseType: "arraybuffer"
      })
        .then(response => {
          this.forceFileDownload(response);
        })
        .catch(() => console.log("error occured"));
    }
  },
  computed: {
    ...mapGetters({
      image: "mediaLibrary/showedImage"
    }),
    relations() {
      const data = [];

      return this.image
        ? Object.keys(this.image.relations).map(key => {
            return {
              name: key,
              value: this.image.relations[key]
            };
          })
        : [];
    },
    imageType() {
      return this.image.data.mime_type.includes("image");
    },
    videoType() {
      return this.image.data.mime_type.includes("video");
    },
    checkImageCustomProperties() {
      return this.image && this.image.data && this.image.data.custom_properties;
    }
  },
  watch: {
    image(newVal) {
      if (newVal && !this.isLoaded) {
        this.form.title = newVal.data.name ? newVal.data.name : null;
        this.form.description = newVal.data.custom_properties.information
          ? newVal.data.custom_properties.information.description
          : null;
        this.form.caption = newVal.data.custom_properties.information
          ? newVal.data.custom_properties.information.caption
          : null;
      }
    },
    "form.title": _.debounce(function(newVal, oldVal) {
      if (this.image) {
        this.updateShowedImage({
          imageId: this.image.data.id,
          information: this.form
        });
      }
    }, 2000),

    "form.description": _.debounce(function(newVal, oldVal) {
      if (this.image) {
        this.updateShowedImage({
          imageId: this.image.data.id,
          information: this.form
        });
      }
    }, 2000),
    "form.caption": _.debounce(function(newVal, oldVal) {
      if (this.image) {
        this.updateShowedImage({
          imageId: this.image.data.id,
          information: this.form
        });
      }
    }, 2000)
  }
};
</script>
<style  scoped>
.card {
  border: 0;
}
</style>
