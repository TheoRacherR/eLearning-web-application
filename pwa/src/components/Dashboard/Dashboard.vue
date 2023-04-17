<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import MainBoard from "./MainBoard/MainBoard.vue";
import router from "../../router";
import { store } from "../../store/store";
import toastr from "toastr";
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <MainBoard />
  </div>
</template>

<style lang="scss" scoped>
div.container-dashboard {
  width: 100%;
  height: 100%;
  display: flex;
  // background-color: aqua;
}
</style>
