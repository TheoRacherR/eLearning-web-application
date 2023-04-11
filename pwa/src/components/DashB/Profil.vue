<script setup>
import { ref, computed } from "vue";
import { store } from "../../store/store";

const {
  user: {
    id,
    isValid,
    isAdmin,
    isConnected,
    isTeacher,
    isTeacherValid,
    token,
    ...infos
  },
} = store;

const currentUser = ref({ ...infos });

const isDifferent = computed(() => {
  return Object.keys(currentUser.value).some(
    (key) => currentUser.value[key] !== infos[key]
  );
});
</script>

<template>
  <div class="wrapperForm">
    <h1>Modifier mon profil</h1>
    <va-form style="width: 300px">
      <va-input v-for="key in Object.keys(currentUser)" :key="key" class="my-3" :label="key" v-model="currentUser[key]"/>
      <button class="bttn bttn-prim" :disabled="!isDifferent">Valider</button>
    </va-form>
    <button class="bttn bttn-prim-out requestProf" v-if="!isTeacher">
      Demande pour passer Professeur
    </button>
  </div>
</template>

<style lang="scss" scoped>
.wrapperForm {
  display: block;
  padding: 3rem 5rem;
}

.requestProf {
  margin-top: 2vh;
}

// button.button-validate {
//   color: #fff;
//   background-color: #e28743;
//   border: 1px solid #e28743;
//   height: 3rem;
// }
</style>