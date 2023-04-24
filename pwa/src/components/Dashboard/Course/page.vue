<script setup>
import axios from "axios";
import { ref, watch, onMounted, watchEffect } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";

const courseId = ref(router.currentRoute.value.params.id)

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;
  

  for (const item in items.value) {
  if (items.value[item].id === parseInt(courseId.value)) {
    console.log("f")
    validItems.value = {
      ...validItems.value,
      [item]: { ...items.value[item] },
    };
    break;
  }
}

console.log(validItems.value)
})

</script>


<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>Cours n°{{ courseId }}</h2>
      <div v-for="course in validItems">
        <div>{{ course.title }}</div>
        <div>{{ course.description }}</div>
        <div>{{ course.price }}</div>
        <div>{{ course.title }}</div>
      </div>
    </div>
  </div>
</template>


<style lang="scss" scoped>

div.container-dashboard {
  display: flex;

  div.main-page {
    padding: 2rem;
    width: 100%;
  }
}
</style>