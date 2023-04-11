<template>
  <div class="container mt-4">
    <form @submit.prevent="handleSubmit">
      <div class="form-group mb-3">
        <label for="mail">Adresse Mail</label>
        <input
          type="mail"
          class="form-control"
          id="mail"
          v-model="initialValue.mail"
          required
        />
      </div>
      <div class="form-group mb-3">
        <label for="firstname">Prénom</label>
        <input
          type="firstname"
          class="form-control"
          id="firstname"
          v-model="initialValue.firstname"
          required
        />
      </div>
      <div class="form-group mb-3">
        <label for="lastname">Nom</label>
        <input
          type="lastname"
          class="form-control"
          id="lastname"
          v-model="initialValue.lastname"
          required
        />
      </div>
      <div class="form-group mb-3">
        <label for="password">Mot de passe</label>
        <input
          type="password"
          class="form-control"
          id="password"
          v-model="initialValue.password"
          required
        />
      </div>
      <button type="submit" class="bttn bttn-prim bttn-submit">Créer le compte</button>
    </form>
  </div>

  
</template>

<style scoped>


</style>


<script setup>
import axios from "axios";
import { login } from "../utils/login";
import { onMounted } from "vue";
import { store } from "./../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";

onMounted(() => {
  checkConnection(true, false, "register");
});

const initialValue = {
  password: "",
  mail: "",
  firstname: "",
  lastname: "",
};

const handleSubmit = () => {
  axios
    .post(import.meta.env.VITE_API_URL + "/users", {
      password: initialValue.password,
      mail: initialValue.mail,
      firstname: initialValue.firstname,
      lastname: initialValue.lastname,
    })
    .then(() => {
      router.push("/");
    })
    .catch((error) => {
      // Gestion des erreurs
      console.log(error);
    });
};
</script>
