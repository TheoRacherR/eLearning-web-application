<script setup>
import axios from "axios";
import { login } from "../utils/login";
import { onMounted, ref, watchEffect, watch } from "vue";
import { store } from "./../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import toastr from "toastr";

onMounted(() => {
  checkConnection(true, false, "register");
});

const loadingButton = ref(false);
const errorPassword = ref(false);

const initialValue = {
  password: "",
  confirmPassword: "",
  mail: "",
  firstname: "",
  lastname: "",
};

const confirmPasswords = () => {
  if (initialValue.password === initialValue.confirmPassword) { 
    errorPassword.value = false;
  }
}

const handleSubmit = () => {
  if (initialValue.firstname.length > 0 && initialValue.lastname.length > 0 && initialValue.mail.length > 0 && initialValue.password.length > 0 && initialValue.confirmPassword.length > 0 && initialValue.mail.length > 0 && initialValue.password === initialValue.confirmPassword) {
    loadingButton.value = true;
    axios
      .post(import.meta.env.VITE_API_URL + "/users", {
        password: initialValue.password,
        mail: initialValue.mail,
        firstname: initialValue.firstname,
        lastname: initialValue.lastname,
      })
      .then(() => {
        loadingButton.value = false;
        router.push("/");
        toastr.warning("Veuillez vérifier votre compte", "", { timeOut: 3000 });
        toastr.success("Compte créé", "", { timeOut: 3000 });
      })
      .catch((error) => {
        // Gestion des erreurs
      });
  }
  else {
    errorPassword.value = true
  }

};
</script>

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
          @change="confirmPasswords"
          required
        />
      </div>
      <div class="form-group mb-3">
        <label for="password">Confirmer votre mot de passe</label>
        <input
          type="password"
          class="form-control"
          id="password"
          v-model="initialValue.confirmPassword"
          @change="confirmPasswords"
          required
        />
        <div v-if="errorPassword" class="form-group mb-3 erroPwd">
          Confirmer avec le même mot de passe !
        </div>
      </div>
      <button type="submit" class="bttn bttn-prim bttn-submit">
        <div
          class="spinner-border spinner-border-sm"
          role="status"
          v-if="loadingButton"
        >
          <span class="visually-hidden">Loading...</span>
        </div>
        Créer le compte
      </button>
    </form>
  </div>
</template>

<style lang='scss' scoped>
  div.erroPwd{
    color: red;
  }
</style>
