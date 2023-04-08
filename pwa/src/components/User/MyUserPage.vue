<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import router from "../../router";
import { store } from "../../store/store";
import RightContainer from "./RightContainer.vue";

const user = ref({});
const userId = store.user.id;

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
      router.push("/db/user-list");
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

    <RightContainer page="user" />
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

    button {
      margin: 2rem 1rem 0 auto;
      width: 100%;
    }
  }
}
</style>
