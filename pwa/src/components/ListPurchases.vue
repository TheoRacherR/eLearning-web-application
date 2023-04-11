<script setup>
import { store } from "../store/store";
import { ref, watchEffect } from "vue";
import router from "../router";
import toastr from "toastr";

import ItemCourse from "./Courses/ItemCourse.vue";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;

  for (const item in items.value) {
    if (items.value[item].possessed) {
      validItems.value = {
        ...validItems.value,
        [item]: { ...items.value[item] },
      };
    }
  }
});
</script>

<template>
  <div class="wrapper">
    <h1>Liste des cours :</h1>
    <div class="categories"></div>
    <div v-if="validItems">
      <div class="wrapper-item" v-for="item in validItems">
        <ItemCourse
          :title="item.title"
          :description="item.description"
          :id="item.id"
        />
      </div>
    </div>
    <div v-else>no item</div>
  </div>
</template>

<style scoped>
div.wrapper {
  padding: 2rem 0;
}

@media (min-width: 768px) {
  div.wrapper {
    margin: auto;
    max-width: 768px;
    width: 100%;
  }
}

@media (min-width: 992px) {
  div.wrapper {
    margin: auto;
    max-width: 992px;
    width: 100%;
  }
}

@media (min-width: 1280px) {
  div.wrapper {
    margin: auto;
    max-width: 1280px;
    width: 100%;
  }
}
</style>
