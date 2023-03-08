<script setup>

import Category from './Category.vue';
import { RouterLink } from 'vue-router';
import { store } from "../../../store/store";
import { ref, watchEffect } from "vue";

const items = ref({});
let countInvalidItem = ref(0);


watchEffect(() => {
  items.value = store.courses.list;
  console.log(items.value);

  for (const item in items.value) {
    if (!items.value[item].valid) {
      countInvalidItem.value+=1;
    }
  }
  console.log(countInvalidItem);
});

</script>



<template>
    <div class="left-board">
      <RouterLink to="/db">
        <h3>Dashboard</h3>
      </RouterLink>
      <div class="fill-title"></div>

      <div class="box">
        <h5>Utilisateurs</h5>
        <div class="fill-title"></div>
        <RouterLink to="/db/user-list">
          <div class="container-tab">
            <va-icon name="group" size="small"/>Liste
          </div>
          <va-icon name="chevron_right" />
        </RouterLink>
      </div>

      <div class="box">
        <h5>Cours</h5>
        <div class="fill-title"></div>
        <RouterLink to="/db/course-list">
          <div class="container-tab">
            <va-icon name="menu_book" size="small"/>Liste
          </div>
          <va-icon name="chevron_right" />
        </RouterLink>
        <RouterLink to="/db/to-valid-course">
          <div v-if="countInvalidItem > 0" class="container-tab">
            <va-icon name="menu_book" size="small"/>À valider ({{ countInvalidItem }})
          </div>
          <div v-else class="container-tab">
            <va-icon name="menu_book" size="small"/>À valider
          </div>
          <va-icon name="chevron_right" />
        </RouterLink>
        
      </div>

      <div class="box">
        <h5>Commentaires</h5>
        <div class="fill-title"></div>
        <RouterLink to="/db/comm-list">
          <div class="container-tab">
            <va-icon name="chat" size="small"/>Liste
          </div>
          <va-icon name="chevron_right" />
        </RouterLink>
        <RouterLink to="/db/to-valid-comm">
          <!--<div v-if="countInvalidItem > 0" class="container-tab">
            <va-icon name="menu_book" size="small"/>À valider ({{ countInvalidItem }})
          </div>
          <div v-else class="container-tab">
            <va-icon name="menu_book" size="small"/>À valider
          </div>-->
          <div class="container-tab">
            <va-icon name="chat" size="small"/>À valider
          </div>
          <va-icon name="chevron_right" />
        </RouterLink>
      </div>

    </div>
</template>



<style lang="scss" scoped>

div.left-board {
  width: 15vw;
  min-height: 100vh;
  padding-top: 1rem;
  background-color: #262C31;
  color: var(--color-text-dark);
  text-align: center;

  > a {
    text-decoration: none;
    color: white;
  }

  div.fill-title {
    width: auto;
    height: 1px;
    background-color: rgb(153, 153, 153);
    margin: 1rem 2rem;
  }

  div.content {
      padding: 1rem 0 0 .5rem;
  }

  div.box {
    text-align: left;
    margin-bottom: 0.5rem;

    h5 {
        color: rgb(187, 187, 187);
        text-transform: uppercase;
        margin: 2rem 1rem 1rem 1rem;
    }

    div.fill-title {
      width: auto;
      height: 1px;
      background-color: rgb(153, 153, 153);
      margin: 0 2rem 0.5rem 0;
    }

    a {
      display: flex;
      justify-content: space-between;
      width: 100%;
      text-decoration: none;
      color: white;
      margin-bottom: 0.5rem;
      
      div.container-tab {
        font-size: 15px;
        display: flex;

        > i {
          margin-right: 1rem;
        }
      }
      > i {
        margin-right: 1rem;
      }
    }
  }
}


</style>