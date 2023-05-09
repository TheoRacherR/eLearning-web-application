<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import toastr from "toastr";

import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import { listCourses, store } from "../../../store/store";

const question = ref({});
const { courseId, questionId } = router.currentRoute.value.params;

onMounted(async () => {
  if (store.user.token) {
    const { data: questionRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/questions/" + questionId, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .catch((err) => {
        console.log("debug", err);
      });

    question.value = {
      settings: JSON.parse(questionRaw.settings),
    };
  }
});

watch(
  () => store.user.token,
  async () => {
    if (store.user.token) {
      const { data: questionRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/questions/" + questionId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .catch((err) => {
          console.log("debug", err);
        });

      question.value = {
        settings: JSON.parse(questionRaw.settings),
      };
    }
  }
);

const handleSubmit = async () => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/courses/" + courseId,
      { ...question.value },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      listCourses.value[courseId] = {
        ...listCourses.value[courseId],
        ...question.value,
      };
      toastr.success("Données mises à jour", "", { timeOut: 3000 });
      router.push("/db/question-list");
    })
    .catch((err) => {
      console.log("debug", err);
    });
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>Modification des informations du cours n°{{ courseId }}</h2>
      <div class="container-input">
        <div class="firstline">
          <div class="input-item">
            <label :for="question.settings.content">Content</label>
            <input class="innput" v-model="question.settings.content" />
          </div>
        </div>

        <button class="bttn bttn-prim" @click="handleSubmit">Valider</button>
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

    div.container-input {
      background-color: rgb(245, 245, 245);
      padding: 1rem;
      width: 50%;
      margin: 2rem auto 0 auto;
      div.firstline {
        display: flex;
        justify-content: center;

        div {
          width: 50%;
        }
      }

      div.input-item {
        margin-bottom: 2rem;
      }

      button {
        margin: 2rem 1rem 0 auto;
        // padding-left: 20rem;
        // padding-right: 20rem;
        width: 100%;
      }
    }
  }
}
</style>
