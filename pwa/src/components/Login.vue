<template>
  <div class="modal-body">
    <div v-show="errorShown" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>

    <div class="form-group">
      <label for="mail">Adresse Mail</label>
      <input type="mail" class="form-control" id="mail" required v-model="initialValue.mail"/>
    </div>

    <div class="form-group mb-3">
      <label for="password">Mot de passe</label>
      <input type="password" class="form-control" id="password" required v-model="initialValue.password"/>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="bttn bttn-prim-out bttn-signin" @click="navigateRegister">Se créer un compte</button>
    <button type="button" class="bttn bttn-prim bttn-submit" @click="handleSubmit">Se connecter</button>
  </div>


</template>

<style scoped>

button.bttn-signin {
  margin-right: .7rem;
}


</style>




<script setup>
import { login } from "../utils/login";
import { onMounted, ref } from "vue";
import { store } from "./../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";

const props = defineProps({
  closeModal: {
    type: Function,
    required: true,
  },
});

const initObject = {
  mail: "",
  password: "",
};
const initialValue = {
  ...initObject,
};

let errorShown = ref(false);
let errorMessage = ref("");

onMounted(() => {
  checkConnection(false, false, "login");
});

const handleSubmit = () => {
  login(initialValue.mail, initialValue.password)
    .then(() => {
      props.closeModal();

      initialValue.mail = initObject.mail;
      initialValue.password = initObject.password;
      errorMessage.value = "";
      errorShown.value = false;
    })
    .catch((err) => {
      console.log("debug", err, err.response);

      errorShown.value = true;
      errorMessage.value =
        err.response?.data?.message || err.response?.data?.detail;
    });
};
const navigateRegister = () => {
  props.closeModal();
  router.push("/register");
};
</script>
