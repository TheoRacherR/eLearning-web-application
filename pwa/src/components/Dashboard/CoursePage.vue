<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import router from "../../router";
import { store } from "../../store/store";
import toastr from "toastr";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}
// else if(!store.user.isAdmin){
//   router.push("/")
//   toastr.error("Vous n'êtes pas autorisé à accéder au backoffice ", "", { timeOut: 3000 });
// }

const course = ref({});
const courseId = router.currentRoute.value.params.id;

onMounted(async () => {
  if (store.user.token) {
    const { data: courseRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/courses/" + courseId, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .catch((err) => {
        console.log("debug", err);
      });

    course.value = {
      title: courseRaw.title,
      description: courseRaw.description,
    };
  }
});

watch(
  () => store.user.token,
  async () => {
    if (store.user.token) {
      const { data: courseRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/users/" + courseId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .catch((err) => {
          console.log("debug", err);
        });

      course.value = {
        title: courseRaw.title,
        description: courseRaw.description,
      };
    }
  }
);

const handleSubmit = async () => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/courses/" + courseId,
      { ...course.value },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      toastr.success("Données mises à jour", "", { timeOut: 3000 });
      router.push("/db/user-list");
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
            <label :for="course.title">Titre</label>
            <input class="innput" v-model="course.title" />
          </div>

          <div class="input-item">
            <label :for="course.description">Description</label>
            <input class="innput" v-model="course.description" />
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
