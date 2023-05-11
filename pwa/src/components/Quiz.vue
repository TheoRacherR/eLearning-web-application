<script setup>
import { onMounted, ref, computed, watchEffect, watch } from "vue";
import { store } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import axios from "axios";
import toastr from "toastr";

const { id: courseId } = router.currentRoute.value.params;

const courses = ref({});
const questions = ref({});
const question = ref({});
const answersLabel = ref({});
const answersUser = ref({
  answer_1: false,
  answer_2: false,
  answer_3: false,
  answer_4: false,
});
const questionIndexValue = ref(-1);
const isLastQuestion = ref(false);

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});

watchEffect(() => {
  courses.value = store.courses.list;
});

const init = () => {
  questions.value =
    Object.values(JSON.parse(course.value.sequence).questions) || [];
  answersUser.value = {
    answer_1: false,
    answer_2: false,
    answer_3: false,
    answer_4: false,
  };

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

      const lastQuestionAnswered = parseInt(
        userAnswers[userAnswers.length - 1]?.question.split("/")[2]
      );
      const indexLastQuestion = questions.value.findIndex((item) => {
        return parseInt(item.questionId) === parseInt(lastQuestionAnswered);
      });
      const questionIndex =
        indexLastQuestion + 1 === questions.value.length
          ? "ended"
          : indexLastQuestion + 1;

      if (questionIndex !== "ended") {
        isLastQuestion.value = questionIndex + 1 === questions.value.length;
        questionIndexValue.value = questionIndex;
        question.value = questions.value[parseInt(questionIndex)];
        console.log(
          "debug new index",
          questionIndex,
          questions.value[parseInt(questionIndex)]
        );

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
            answersLabel.value = JSON.parse(settings).answers.map(
              (item) => item.label
            );
          });
      } else {
        router.push("/course/" + courseId + "/quiz/finals");
      }
    });
};

watchEffect(() => {
  if (course.value && course.value.sequence) {
    init();
  }
});

onMounted(() => {
  checkConnection(false, true, false, false, "Quiz");
  store.selectCourse(courseId);
  if (
    typeof course.value?.possessed === "boolean" &&
    !course.value?.possessed
  ) {
    router.push(`/detail/${courseId}`);
  }
});

const isDisabled = (answerId) => {
  switch (answerId) {
    case 1:
      return (
        answersUser.value.answer_2 ||
        answersUser.value.answer_3 ||
        answersUser.value.answer_4
      );
      break;
    case 2:
      return (
        answersUser.value.answer_1 ||
        answersUser.value.answer_3 ||
        answersUser.value.answer_4
      );
      break;
    case 3:
      return (
        answersUser.value.answer_1 ||
        answersUser.value.answer_2 ||
        answersUser.value.answer_4
      );
      break;
    case 4:
      return (
        answersUser.value.answer_1 ||
        answersUser.value.answer_2 ||
        answersUser.value.answer_3
      );
      break;

    default:
      break;
  }
};

const handleSubmit = () => {
  const newAnswer =
    Object.values(answersUser.value).findIndex((item) => item) + 1;

  console.log("debug", {
    question: "/questions/" + question.value.questionId,
    account: "/users/" + store.user.id,
    value: newAnswer,
  });
  console.log("debug", newAnswer);

  if (newAnswer > 0) {
    axios
      .post(
        import.meta.env.VITE_API_URL + "/answers",
        {
          question: "/questions/" + question.value.questionId,
          account: "/users/" + store.user.id,
          value: JSON.stringify(newAnswer),
        },
        {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }
      )
      .then(() => {
        toastr.info("Réponses enregistrée", "", {
          timeout: 1000,
        });

        init();
      });
  } else {
    toastr.error("Merci de répondre", "", {
      timeout: 3000,
    });
  }
};
</script>

<template>
  <div class="wrapper">
    <div class="header">

      <h1>{{ course?.title }}</h1>
      <h2>{{ chapter?.title }}</h2>
      <h2>
        Question {{ parseInt(questionIndexValue) + 1 }}
        {{ question.question }}
      </h2>
    </div>

    <div class="wrapperAnswers">
      <div class="wrapperQuestions">

        <div class="input-item">
          <label>A : {{ answersLabel[0] }}</label>
          <input
            type="checkbox"
            v-model="answersUser.answer_1"
            :disabled="isDisabled(1)"
          />
        </div>
        <div class="input-item">
          <label>B : {{ answersLabel[1] }}</label>
          <input
            type="checkbox"
            v-model="answersUser.answer_2"
            :disabled="isDisabled(2)"
          />
        </div>
        <div class="input-item">
          <label>C : {{ answersLabel[2] }}</label>
          <input
            type="checkbox"
            v-model="answersUser.answer_3"
            :disabled="isDisabled(3)"
          />
        </div>
        <div class="input-item">
          <label>D : {{ answersLabel[3] }}</label>
          <input
            type="checkbox"
            v-model="answersUser.answer_4"
            :disabled="isDisabled(4)"
          />
        </div>
      </div>
      <button
        class="bttn bttn-prim"
        @click="handleSubmit"
        v-if="!isLastQuestion"
      >
        Valider la réponse
      </button>
      <button class="bttn bttn-prim" @click="handleSubmit" v-else>
        Terminer le quiz
      </button>
    </div>
  </div>
</template>

<style lang="scss" scoped>

.wrapper {
  // padding: 6vh 0;
  text-align: center;
  // width:50%;
  margin: 0 auto;

  div.header{
    padding: 3vh 30vw 3vh 30vw;
    background-color: rgb(142, 139, 168);
    text-align: left;

  }

  div.wrapperAnswers{

    div.wrapperQuestions{
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin: 5rem auto;
      width: 60vw;

      div.input-item {
        width: 48%;
        padding: 3rem 0;
        background-color: rgb(231, 231, 231);

        input {
          margin-left: 2rem;
        }
      }

    }
  }
}
</style>
