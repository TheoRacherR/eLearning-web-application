<script setup>
import Category from "./Category.vue";
import { RouterLink } from "vue-router";
import { store } from "../../../store/store";
import { ref, watchEffect } from "vue";
import router from "../../../router";

const itemsCourse = ref({});
let countInvalidItemCourse = ref(0);

const itemsComment = ref({});
let countInvalidItemComment = ref(0);

watchEffect(() => {
  itemsCourse.value = store.courses.list;

  for (const item in itemsCourse.value) {
    if (itemsCourse.value[item].valid == 0) {
      countInvalidItemCourse.value += 1;
    }
  }

  itemsComment.value = store.comments.list;

  for (const item in itemsComment.value) {
    if (itemsComment.value[item].valid == 0) {
      countInvalidItemComment.value += 1;
    }
  }
});

const handleCreateQuiz = () => {
  const courseId = 62; // Prcq il existe
  router.push(`/db/quiz/create/${courseId}`);
};
const navigateListQuiz = () => {
  const courseId = 62; // Prcq il existe
  router.push(`/db/quiz/list/${courseId}`);
};
</script>

<template>
  <div class="left-board">
    <RouterLink to="/db">
      <h3>Dashboard</h3>
    </RouterLink>
    <div class="fill-title"></div>

    <button v-if="store.user.isTeacher" class="bttn bttn-succ">
      Créer un cours
    </button>
    <button
      v-if="store.user.isTeacher"
      class="bttn bttn-succ quiz"
      @click="handleCreateQuiz"
    >
      Créer un Quiz (à mettre sur la page de création de cours quand length > 0)
    </button>
    <button
      v-if="store.user.isTeacher"
      class="bttn bttn-succ quiz"
      @click="navigateListQuiz"
    >
      Liste quiz
    </button>

    <div class="box" v-if="store.user.isAdmin">
      <h5>Utilisateurs</h5>
      <div class="fill-title"></div>
      <RouterLink to="/db/user-list">
        <div class="container-tab">
          <va-icon name="group" size="small" />Liste
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
      <RouterLink to="/db/demand-list">
        <div class="container-tab">
          <va-icon name="group" size="small" />Demandes professeurs
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
    </div>

    <div class="box" v-if="store.user.isAdmin">
      <h5>Cours</h5>
      <div class="fill-title"></div>
      <RouterLink to="/db/course-list">
        <div class="container-tab">
          <va-icon name="menu_book" size="small" />Liste
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
      <RouterLink to="/db/to-valid-course">
        <div v-if="countInvalidItemCourse > 0" class="container-tab">
          <va-icon name="menu_book" size="small" />À valider ({{
            countInvalidItemCourse
          }})
        </div>
        <div v-else class="container-tab">
          <va-icon name="menu_book" size="small" />À valider
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
    </div>

    <div class="box" v-if="store.user.isAdmin">
      <h5>Commentaires</h5>
      <div class="fill-title"></div>
      <RouterLink to="/db/comm-list">
        <div class="container-tab">
          <va-icon name="chat" size="small" />Liste
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
      <RouterLink to="/db/to-valid-comm">
        <div v-if="countInvalidItemComment > 0" class="container-tab">
          <va-icon name="chat" size="small" />À valider ({{
            countInvalidItemComment
          }})
        </div>
        <div v-else class="container-tab">
          <va-icon name="chat" size="small" />À valider
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.left-board {
  width: 20vw;
  min-height: 100vh;
  padding-top: 1rem;
  background-color: #262c31;
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
    padding: 1rem 0 0 0.5rem;
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

  .quiz {
    margin-top: 2vh;
  }
}
</style>
