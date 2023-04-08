<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import router from "../../router";
import { store } from "../../store/store";
import RightContainer from "./RightContainer.vue";

const user = ref({});
const userId = store.user.id;
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Notre newsletter</h3>
      <h2>Abonnez-vous à notre newsletter</h2>

      <div class="input-item">
        <input
          class="innput"
          :key="user.firstname"
          :label="user.firstname"
          v-model="user.firstname"
          placeholder="Email"
        />
        <button class="bttn bttn-drk">Je souscris</button>
      </div>
    </div>

    <RightContainer page="newsletter"/>
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
    text-align: center;

    h3 {
      font-weight: bold;
      margin-bottom: 2.5rem;
    }

    div.input-item {
      width: 20vw;
      margin: 0 auto;
      padding-top: 2rem;

      button {
        width: 100%;
        margin-top: 1rem;
      }
    }
  }
}
</style>
