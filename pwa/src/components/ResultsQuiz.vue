<script setup>
import axios from "axios";
import router from "../router";
import { computed, onMounted, ref, watchEffect } from "vue";
import { store } from "../store/store";
import toastr from "toastr";
import { checkConnection } from "../utils/checkConnection";

const { id: courseId } = router.currentRoute.value.params;

const courses = ref({});
const questions = ref({});
const score = ref(null);
const correction = ref(null);
const questionsAnswered = ref(null);

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

        axios
          .post(import.meta.env.VITE_API_URL + "/quiz/submit/", body, {
            headers: {
              Authorization: `Bearer ${store.user.token}`,
            },
          })
          .then(({ data: { score: scoreRes, correction: correctionRaw } }) => {
            score.value = scoreRes;

            correction.value = correctionRaw.filter(
              (item) => !item.yourAnswer.isGoodAnswer
            );
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

const restart = () => {
  questionsAnswered.value =
    Object.values(JSON.parse(course.value.sequence).questions) || [];

  axios
    .get(import.meta.env.VITE_API_URL + "/answers", {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(async ({ data: { ["hydra:member"]: answerRaw } }) => {
      const userAnswersId = answerRaw
        .filter((item) => {
          console.log("debug", item.account, "/users/" + store.user.id);

          const arrayQuestions = Object.values(questionsAnswered.value).map(
            (item) => parseInt(item.questionId)
          );
          return (
            item.account === "/users/" + store.user.id &&
            arrayQuestions.includes(parseInt(item.question.split("/")[2]))
          );
        })
        .map((item) => item.id);

      console.log("debug", userAnswersId);
      const promises = userAnswersId.map((answerId) =>
        axios.delete(import.meta.env.VITE_API_URL + "/answers/" + answerId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
      );

      await Promise.all(promises).catch(() => {
        toastr.warning("Un problème est survenu", "", { timeOut: 3000 });
      });
      router.push(`/course/${courseId}/quiz`);
    });
};
</script>

<template>
  <div class="wrapper" v-if="score === null">Vos résultats arrivent</div>
  <div class="wrapper" v-else>
    <div class="success" v-if="score > 70">
      Félicitation vous avez réussi ce cours avec un score de {{ score }}/100
      <div class="correction">
        <div class="question" v-for="question in correction">
          <div class="label">Question : {{ question.question }}</div>
          <div class="answer">
            Votre réponse : {{ question.yourAnswer.label }}
          </div>
          <div class="Correction">Correction : {{ question.goodAnswer }}</div>
        </div>
      </div>
    </div>
    <div class="failure" v-else>
      Vous avez échoué ce cours avec un score de {{ score }}/100
    </div>

    <button class="bttn bttn-wng quiz-btn" @click="restart">
      Recommencer le Quiz
    </button>
  </div>
</template>

<style scoped>
.wrapper {
  padding: 3vh 3vw;
  text-align: center;
}
</style>
