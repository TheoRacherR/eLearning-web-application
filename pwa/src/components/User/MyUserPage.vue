<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import RightContainer from "./RightContainer.vue";
import toastr from "toastr";
import router from "../../router";
import { store } from "../../store/store";

const user = ref({});

const loadingButton = ref(false);

const {
  user: {
    id: userId,
    isValid,
    isAdmin,
    isConnected,
    isTeacher,
    isTeacherValid,
    token,
    ...infos
  },
} = store;

if (!isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}

onMounted(async () => {
  if (token) {
    const { data: userRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
        headers: {
          Authorization: `Bearer ${token}`,
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
  () => token,
  async () => {
    if (token) {
      const { data: userRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
          headers: {
            Authorization: `Bearer ${token}`,
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

const handleSubmit = () => {
  loadingButton.value = true;
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/users/" + userId,
      { ...user.value },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )
    .then(() => {
      toastr.success("Données mises à jour", "", { timeOut: 3000 });
      loadingButton.value = false;
    })
    .catch((err) => {
      console.log("debug", err);
    });
};
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Données personnelles</h3>
      <div class="firstline">
        <div class="input-item">
          <label :for="user.firstname">Prénom</label>
          <input class="innput" v-model="user.firstname" />
        </div>

        <div class="input-item">
          <label :for="user.lastname">Nom</label>
          <input class="innput" v-model="user.lastname" />
        </div>
      </div>

      <div class="input-item">
        <label :for="user.mail">Adresse mail</label>

        <input class="innput" v-model="user.mail" />
      </div>

      <button class="bttn bttn-prim" @click="handleSubmit">
        <div
          class="spinner-border spinner-border-sm"
          role="status"
          v-if="loadingButton"
        >
          <span class="visually-hidden">Loading...</span>
        </div>
        Valider
      </button>
    </div>

    <RightContainer page="personal-data" />
  </div>
</template>

<style lang="scss" scoped>
div.total-container {
  display: flex;
  height: 100vh;
  div.main-container {
    // background-color: chocolate;
    width: 75vw;
    padding: 5rem 2rem 2rem 2rem;

    h3 {
      font-weight: bold;
      margin-bottom: 2.5rem;
    }

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

    button.bttn-prim {
      margin: 2rem 1rem 0 auto;
      width: 100%;
    }
  }
}
</style>
