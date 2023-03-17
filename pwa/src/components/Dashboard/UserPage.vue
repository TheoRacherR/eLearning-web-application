<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import router from "../../router";
import { store } from "../../store/store";
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";

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
      mail: userRaw.mail,
      firstname: userRaw.firstname,
      lastname: userRaw.lastname,
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
        mail: userRaw.mail,
        firstname: userRaw.firstname,
        lastname: userRaw.lastname,
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
      router.push("/db");
    });
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>User Page</h2>

      <va-input
        v-for="key in Object.keys(user)"
        :key="key"
        class="my-3"
        :label="key"
        v-model="user[key]"
      />
      <va-button @click="handleSubmit">Valider</va-button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.container-dashboard {
  width: 100%;
  height: 100%;
  display: flex;
  /* background-color: aqua; */

  div.container-commlist {
    padding: 2rem 5rem;
    width: 90%;

    div.container-grid {
      display: grid;
      grid-auto-columns: auto;
      grid-auto-rows: auto;

      thead {
        background-color: rgb(133, 85, 63);
        color: #fff;
      }
      th,
      td {
        padding: 1rem;
      }

      td.verifed {
        color: #52b425;
      }

      td.waiting {
        color: rgb(168, 43, 43);
      }
      tbody tr:nth-child(even) {
        background-color: #f5f5f5;
      }
    }
  }
}
</style>
