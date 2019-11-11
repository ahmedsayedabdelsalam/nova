<template>
  <b-modal
    id="mediaLibraryModal"
    size="xl"
    v-if="showMediaModal"
    title="Media"
    @hidden="clearImages"
  >
    <media-library></media-library>
  </b-modal>
</template>

<script>
import MediaLibrary from "./MediaLibrary";
import { mapMutations, mapGetters } from "vuex";

export default {
  components: {
    MediaLibrary
  },
  computed: {
    ...mapGetters({
      showMediaModal: "mediaLibrary/showMediaModal"
    })
  },
  methods: {
    ...mapMutations({
      updateImages: "mediaLibrary/updateImages"
    }),
    clearImages() {
      this.updateImages();
    }
  }
};
</script>
