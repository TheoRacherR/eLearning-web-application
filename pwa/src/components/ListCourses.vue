<script setup>

import { store } from "../store/store";
import { ref, watchEffect } from "vue";

import ItemCourse from "./Courses/ItemCourse.vue";

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;
  console.log(items.value);

  for (const item in items.value) {
    if (items.value[item].valid) {
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
        <div class="wrapper-item" v-for="item in validItems">
            <ItemCourse :title="item.title" :description="item.description" :id="item.id"/>
        </div>
    </div>
</template>

<style scoped>

    div.wrapper {
        padding: 2rem 0;
    }

@media (min-width: 768px){
    div.wrapper {
        margin: auto;
        max-width: 768px;
        width: 100%;
    }
}

@media (min-width: 992px){
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