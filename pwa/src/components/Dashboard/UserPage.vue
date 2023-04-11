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

const user = ref({});
const userId = router.currentRoute.value.params.id;

onMounted(async () => {
  if (store.user.token) {
    const { data: userRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .catch((err) => {
        console.log("debug", err);
      });

    user.value = {
      firstname: userRaw.firstname,
      lastname: userRaw.lastname,
      mail: userRaw.mail,
    };
  }
});

watch(
  () => store.user.token,
  async () => {
    if (store.user.token) {
      const { data: userRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .catch((err) => {
          console.log("debug", err);
        });

      user.value = {
        firstname: userRaw.firstname,
        lastname: userRaw.lastname,
        mail: userRaw.mail,
      };
    }
  }
);

const handleSubmit = async () => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/users/" + userId,
      { ...user.value },
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
      <h2>Modification des informations de l'utilisateur n°{{ userId }}</h2>
      <div class="container-input">
        <div class="firstline">
          <div class="input-item">
            <label :for="user.firstname">Prénom</label>
            <input
              class="innput"
              :key="user.firstname"
              :label="user.firstname"
              v-model="user.firstname"
            />
          </div>

          <div class="input-item">
            <label :for="user.lastname">Nom</label>
            <input
              class="innput"
              :key="user.lastname"
              :label="user.lastname"
              v-model="user.lastname"
            />
          </div>
        </div>

        <div class="input-item">
          <label :for="user.mail">Adresse mail</label>

          <input
            class="innput"
            :key="user.mail"
            :label="user.mail"
            v-model="user.mail"
          />
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
