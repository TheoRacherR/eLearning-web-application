<script setup>
import axios from "axios";
import toastr from "toastr";
import router from "../router";

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const token = urlParams.get('token')

const handleSubmitButton = () => {
    let data = {
        "newPassword": document.getElementById("newPassword").value
    }
    axios.post(import.meta.env.VITE_API_URL + "/user/getByToken?token="+token, data).then((res) => {
        toastr.success(res.data.msg)
        router.push('/')
    })
}
</script>

<template>
    <div class="container">
        <h1 class="mt-2">Réinitialiser votre mot de passe</h1>

        <div class="form-group mb-3">
            <label for="newPassword">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="newPassword">
        </div>
        <div class="form-group mb-3">
            <label for="newPassword2">Confirmation du mot de passe</label>
            <input type="password" class="form-control" id="newPassword2">
        </div>
        <div class="form-group mb-3">
            <button type="button" class="btn btn-primary" @click="handleSubmitButton">Modifier le mot de passe</button>
        </div>
    </div>
</template>
