<script setup>
import { onMounted, ref, computed, watchEffect, watch } from "vue";
import { store } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import axios from "axios";

const { id: courseId, questionIndex } = router.currentRoute.value.params;

const courses = ref({});
const questions = ref({});
const question = ref({});

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});

watchEffect(() => {
  courses.value = store.courses.list;
});

watchEffect(() => {
  if (course.value && course.value.sequence) {
    questions.value =
      Object.values(JSON.parse(course.value.sequence).questions) || [];

    question.value = questions.value[parseInt(questionIndex)];

    axios
      .get(
        import.meta.env.VITE_API_URL +
          "/questions/" +
          question.value.questionId,
        {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }
      )
      .then(({ data: { settings } }) => {
        console.log("debug", JSON.parse(settings));
      });
  }
});

onMounted(() => {
  // checkConnection(false, true, "Quiz");
  // store.selectCourse(courseId);
  // if (
  //   typeof course.value?.possessed === "boolean" &&
  //   !course.value?.possessed
  // ) {
  //   router.push(`/detail/${courseId}`);
  // }
});
</script>

<template>
  <div class="wrapper">
    <h1>{{ course?.title }}</h1>
    <h2>{{ chapter?.title }}</h2>
    <h2>
      Question n°{{ parseInt(questionIndex) + 1 }}: {{ question.question }}
    </h2>
  </div>
</template>

<style scoped>
.wrapper {
  padding: 3vh 3vw;
  text-align: center;
}
</style>
