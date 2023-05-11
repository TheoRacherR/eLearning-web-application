<script setup>
import router from "../router";
import { store } from "../store/store";
import { ref, isReactive, watchEffect } from "vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

import Swiper from "./Home/Swiper.vue";
import BoxLeft from "./Home/BoxLeft.vue";
import BoxRight from "./Home/BoxRight.vue";
import IconBox from "./Home/IconBox.vue";
import Comments from "./Home/Comments.vue";

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;

  let count = 0

  console.log(Object.keys(items.value).length)
  for (let j = Object.keys(items.value).length-1; j > 0; j--) {
    if (items.value[Object.keys(items.value)[j]].valid === 1) {
      validItems.value = {
        ...validItems.value,
        [count]: { ...items.value[Object.keys(items.value)[j]] },
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

let cnt1 = "Ce site de vente de cours est une véritable trouvaille ! J'ai enfin trouvé une solution simple et efficace pour apprendre de nouvelles compétences. Les cours proposés sont variés et de haute qualité. J'ai pu choisir celui qui correspondait le mieux à mes besoins et commencer immédiatement mon apprentissage. Les explications sont claires et les ressources fournies sont très utiles. J'ai déjà pu mettre en pratique les connaissances acquises dans ma vie quotidienne. De plus, le service client est excellent. Je recommande vivement ce site à tous ceux qui souhaitent développer leurs compétences rapidement et facilement.";
let cnt2 = "Ce site de vente de cours est tout simplement incroyable ! Les cours sont de haute qualité et m'ont vraiment aidé à acquérir de nouvelles compétences. Les instructeurs sont compétents et les leçons sont bien structurées. J'ai pu apprendre à mon propre rythme et selon mes disponibilités. Je recommande vivement ce site à tous ceux qui veulent améliorer leurs compétences de manière pratique et accessible.";
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
        :text="cnt1"
        :img="'../../../public/working-people-1.jpg'"
      />
      <BoxRight
        :title="''"
        :text="cnt2"
        :img="'../../../public/working-people-2.jpg'"
      />
    </div>

  <div class="third-home-item">
    <Comments :size="6"/>
  </div>
  
  <div class="fourth-home-item">
      <IconBox :content="content" />
    </div>
  </div>

</template>

<style lang="scss" scoped>
.wrapper {
  text-align: center;
  width: 100%;

  div:nth-child(1){
    padding-top: 4rem;
    padding-bottom: 3.5rem;
    position: sticky;
    z-index: auto;
  }

  div:nth-child(2){
    // background-color: rgb(245, 245, 245);
    padding: 3.5rem 0;
    // margin-bottom: 7rem;
  }

  div:nth-child(3) {}

  div:nth-child(4) {
    padding: 3.5rem 0;
  }
}
// div.wrapper .second-home-item, div.wrapper .fourth-home-item {
//     background-color: rgb(245, 245, 245);
// }

div.wrapper > div:nth-of-type(2n) {
    background-color: rgb(245, 245, 245);
}


@media (min-width: 768px) {
  div.first-home-item > *,
  div.second-home-item > *,
  div.third-home-item > *,
  div.fourth-home-item > * {
    max-width: 768px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}

@media (min-width: 992px) {
  div.first-home-item > *,
  div.second-home-item > *,
  div.third-home-item > *,
  div.fourth-home-item > * {
    max-width: 992px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}

@media (min-width: 1280px) {
  div.first-home-item > *,
  div.second-home-item > *,
  div.third-home-item > *,
  div.fourth-home-item > * {
    max-width: 1280px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
</style>
