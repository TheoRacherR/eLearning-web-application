<script setup>
import axios from "axios";
import { ref } from "vue";
import router from "../router";
import toastr from "toastr";

const token = router.currentRoute.value.params.token;
const error = ref("Nous validons votre token");

axios
  .get(import.meta.env.VITE_API_URL + "/user/validate?token=" + token)
  .then(() => {
    router.push("/?valid=true");
    toastr.success("Compte vérifié", "", { timeOut: 3000 });
  })
  .catch(() => {
    error.value = "Votre token n'est pas valide ou manquant";
    toastr.error(error.message, "", { timeOut: 3000 });
  });
</script>

<template>{{ error }}</template>
