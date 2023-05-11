<script setup>
import axios from "axios";
import toastr from "toastr";

const handleSubmitButton = () => {
    let emailValue = document.getElementById('email').value;
    axios.get(import.meta.env.VITE_API_URL + "/user/request_password?mail="+emailValue).then((res) => {
        toastr.success(res.data)
    })
}
</script>

<template>
    <div class="container">
        <h1 class="mt-5">Mot de passe oublié ?</h1>
        <div class="form-group mb-3">
            <label for="email">Votre adresse mail</label>
            <input type="text" class="form-control" id="email">
        </div>
        <div class="form-group mb-3">
            <button type="button" class="btn btn-primary" @click="handleSubmitButton">Réinitialiser le mot de passe</button>
        </div>
    </div>
</template>
