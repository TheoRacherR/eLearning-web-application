<script setup>
import { login } from "../utils/login";
import { onMounted, ref } from "vue";
import { store } from "./../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import toastr from "toastr";

const loadingButtonLogin = ref(false);
const loadingButtonSignup = ref(false);

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
  checkConnection(false, false, false, false, "login");
});

const handleSubmit = () => {
  loadingButtonLogin.value = true;
  login(initialValue.mail, initialValue.password)
    .then(() => {
      loadingButtonLogin.value = false;
      props.closeModal();

      initialValue.mail = initObject.mail;
      initialValue.password = initObject.password;
      errorMessage.value = "";
      errorShown.value = false;
      toastr.success("Connecté", "", { timeOut: 3000 });
    })
    .catch((err) => {
      loadingButtonLogin.value = false;
      console.log("debug", err, err.response);

      errorShown.value = true;
      errorMessage.value =
        err.response?.data?.message || err.response?.data?.detail;
    });
};
const navigateRegister = () => {
  loadingButtonSignup.value = true;
  props.closeModal();
  loadingButtonSignup.value = false;
  router.push("/register");
};
</script>

<template>
  <div class="modal-body">
    <div v-show="errorShown" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>

    <div class="form-group">
      <label for="mail">Adresse Mail</label>
      <input
        type="mail"
        class="form-control"
        id="mail"
        required
        v-model="initialValue.mail"
      />
    </div>

    <div class="form-group mb-3">
      <label for="password">Mot de passe</label>
      <input
        type="password"
        class="form-control"
        id="password"
        required
        v-model="initialValue.password"
      />
    </div>
  </div>

  <div class="modal-footer">
    <button
      type="button"
      class="bttn bttn-prim-out bttn-signin"
      @click="navigateRegister"
    >
      <div
        class="spinner-border spinner-border-sm"
        role="status"
        v-if="loadingButtonSignup"
      >
        <span class="visually-hidden">Loading...</span>
      </div>
      Se créer un compte
    </button>
    <button
      type="button"
      class="bttn bttn-prim bttn-submit"
      @click="handleSubmit"
    >
      <div
        class="spinner-border spinner-border-sm"
        role="status"
        v-if="loadingButtonLogin"
      >
        <span class="visually-hidden">Loading...</span>
      </div>
      Se connecter
    </button>
  </div>
</template>

<style scoped>
button.bttn-signin {
  margin-right: 0.7rem;
}
</style>
