<script setup>
import router from "../router";
import { store } from "../store/store";
import { ref, isReactive, watchEffect } from "vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

import Swiper from "./Home/Swiper.vue";
import BoxLeft from "./Home/BoxLeft.vue";
import BoxRight from "./Home/BoxRight.vue";
import IconBox from "./Home/IconBox.vue";
import Cookie from "./Home/Cookie.vue";

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;

  let count = 0

  for (const item in items.value) {
    if (items.value[item].valid) {
      validItems.value = {
        ...validItems.value,
        [item]: { ...items.value[item] },
      };
      count ++;
    }
    if(count >= 3) {
      break;
    }
  }
});

const content = [
  {
    icon: "star",
    text: "Flexibilité",
  },
  {
    icon: "redeem",
    text: "Adaptabilité",
  },
  {
    icon: "sell",
    text: "Discipline",
  },
];

let cnt =
  "Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam delectus ipsam praesentium fugit inventore. Distinctio eius totam quasi laboriosam porro nulla. Amet earum sequi soluta nulla, recusandae ea consequuntur eligendi?";
</script>

<template>
  <div class="wrapper">
    <div class="first-home-item">
      <div>
        <h1 style="text-align: left;">Les dernières formations : </h1>
        <Swiper :content="validItems"/>
      </div>
    </div>

    <div class="second-home-item">
      <BoxLeft
        :title="'Ils témoignent '"
        :text="cnt"
        :img="'../../../public/working-people-1.jpg'"
      />
      <BoxRight
        :title="''"
        :text="cnt"
        :img="'../../../public/working-people-2.jpg'"
      />
    </div>

    <div class="thrid-home-item">
      <IconBox :content="content" />
    </div>
  </div>

</template>

<style lang="scss" scoped>
.wrapper {
  text-align: center;
  padding-top: 4rem;
  width: 100%;

  div.first-home-item {
    margin-bottom: 5rem;
    position: sticky;
    z-index: auto;
  }

  div.second-home-item {
    background-color: rgb(245, 245, 245);
    padding: 2rem 0;
    margin-bottom: 7rem;
  }
}

@media (min-width: 768px) {
  div.first-home-item > *,
  div.second-home-item > *,
  div.third-home-item > * {
    max-width: 768px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}

@media (min-width: 992px) {
  div.first-home-item > *,
  div.second-home-item > *,
  div.third-home-item > * {
    max-width: 992px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}

@media (min-width: 1280px) {
  div.first-home-item > *,
  div.second-home-item > *,
  div.third-home-item > * {
    max-width: 1280px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
</style>
