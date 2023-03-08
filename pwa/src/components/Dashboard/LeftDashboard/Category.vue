<script setup>

import Tab from './Tab.vue';


const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  tag: {
    type: Object,
    required: true,
  },
});

</script>



<template>
    <div class="box">
        <h5 class="title">{{ title }}</h5>
        <div class="fill-title"></div>
        <div v-for="l in {tag}">
            <Tab :text="l.titleTag" :icon="l.icon" :link="l.link"/>
        </div>
    </div>
</template>



<style lang="scss" scoped>

div.box {
    // margin: 2rem;
    text-align: left;
    margin-bottom: 1rem;

    h5.title {
        color: rgb(187, 187, 187);
        text-transform: uppercase;
    }
    div.fill-title {
      width: auto;
      height: 1px;
      background-color: rgb(153, 153, 153);
      margin: 0 2rem 0.3rem 0;
    }
}


</style>