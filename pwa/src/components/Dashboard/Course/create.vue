<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";
import { QuillEditor } from "@vueup/vue-quill";
import "quill/dist/quill.snow.css";

const chapter = ref('');
const content = ref()

const handleSubmit = async () => {
  console.log(content)
//   const { content, ...answers } = question.value;

//   const settings = JSON.stringify({ content, answers: Object.values(answers) });
//   const goodAnswer = JSON.stringify(
//     Object.values(answers).findIndex((item) => item.isGoodAnswer)
//   );
//   console.log("debug", { course: "courses/" + courseId, settings, goodAnswer });

//   axios
//     .post(
//       import.meta.env.VITE_API_URL + "/courses",
//       { sequence: JSON.stringify(course.value.sequence) },
//       {
//         headers: {
//           Authorization: `Bearer ${store.user.token}`,
//         },
//       }
//     )
//     .catch((err) => {
//       console.log("debug", err);
//     });
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>Création du cours</h2>
      <div>
        <QuillEditor
          :toolbar="[
            { font: [] },
            { size: ['small', false, 'large', 'huge'] },
            'bold',
            'italic',
            'underline',
            'strike',
            [],
            'blockquote',
            'code-block',
            'link',
            'image',
            [],
            { color: [] },
            { background: [] },
            { align: [] },
            { direction: 'rtl' },
            [],
            { header: 1 },
            { header: 2 },
            { list: 'ordered' },
            { list: 'bullet' },
            [],
            { script: 'sub' },
            { script: 'super' },
            { indent: '-1' },
            { indent: '+1' },
          ]"
          placeholder="Rédigez votre cours ici..."
          v-model="content"
        />

        <button class="bttn bttn-wng" @click="handleSubmit">Valider</button>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.container-dashboard {
  display: flex;

  div.main-page {
    padding: 2rem;
    width: 100%;

    h2 {
      margin-bottom: 2rem;
    }

    button {
      margin-top: 2rem;
      width: 7%;
      margin-left: 93%;
    }
  }
}
</style>
