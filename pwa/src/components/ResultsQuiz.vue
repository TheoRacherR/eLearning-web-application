<script setup>
import axios from "axios";
import router from "../router";
import { computed, onMounted, ref, watchEffect } from "vue";
import { store } from "../store/store";
import { checkConnection } from "../utils/checkConnection";

const { id: courseId } = router.currentRoute.value.params;

const courses = ref({});
const questions = ref({});

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});

watchEffect(() => {
  courses.value = store.courses.list;
});

watchEffect(() => {
  if (store.user.token && courses.value && course.value) {
    questions.value =
      Object.values(JSON.parse(course.value.sequence).questions) || [];

    axios
      .get(import.meta.env.VITE_API_URL + "/answers", {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then(({ data: { ["hydra:member"]: answerRaw } }) => {
        const userAnswers = answerRaw.filter((item) => {
          console.log("debug", item.account, "/users/" + store.user.id);

          const arrayQuestions = Object.values(questions.value).map((item) =>
            parseInt(item.questionId)
          );
          return (
            item.account === "/users/" + store.user.id &&
            arrayQuestions.includes(parseInt(item.question.split("/")[2]))
          );
        });

        const body = {};

        userAnswers.map((item) => {
          body[item.question.split("/")[2]] = item.value;
        });

        console.log("debug", body);

        axios.post(import.meta.env.VITE_API_URL + "/quiz/submit/", body, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }).then(({data}) => {
            console.log(data)
        });
      });
  }
});

onMounted(() => {
  checkConnection(false, true, false, false, "Quiz results");
  store.selectCourse(courseId);
  if (
    typeof course.value?.possessed === "boolean" &&
    !course.value?.possessed
  ) {
    router.push(`/detail/${courseId}`);
  }
});
</script>

<template>
  <div class="wrapper">Vos résultats arrivent</div>
</template>

<style scoped>
.wrapper {
  padding: 3vh 3vw;
  text-align: center;
}
</style>
