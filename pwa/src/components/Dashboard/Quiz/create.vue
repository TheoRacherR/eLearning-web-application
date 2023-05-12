<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";

const course = ref({});
const question = ref({
  content: "",
  answer_1: { label: "", isGoodAnswer: false },
  answer_2: { label: "", isGoodAnswer: false },
  answer_3: { label: "", isGoodAnswer: false },
  answer_4: { label: "", isGoodAnswer: false },
});

const resetQuestion = () => {
  question.value = {
    content: "",
    answer_1: { label: "", isGoodAnswer: false },
    answer_2: { label: "", isGoodAnswer: false },
    answer_3: { label: "", isGoodAnswer: false },
    answer_4: { label: "", isGoodAnswer: false },
  };
};
const courseId = router.currentRoute.value.params.id;

onMounted(async () => {
  if (store.user.token) {
    const { data: courseRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/courses/" + courseId, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .catch((err) => {
        console.log("debug", err);
      });

    course.value = {
      sequence: JSON.parse(courseRaw.sequence),
    };
  }
});

watch(
  () => store.user.token,
  async () => {
    if (store.user.token) {
      const { data: courseRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/courses/" + courseId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .catch((err) => {
          console.log("debug", err);
        });

      course.value = {
        sequence: courseRaw.sequence ? JSON.parse(courseRaw.sequence) : [],
      };
    }
  }
);

const handleSubmit = async () => {
  const { content, ...answers } = question.value;

  const answersValid = Object.values(answers).some(
    (item) => item.label.length > 0
  );
  const hasGoodAnswer = Object.values(answers).some(
    (item) => item.isGoodAnswer
  );

  if (answersValid && hasGoodAnswer && content.length > 0) {
    const settings = JSON.stringify({
      content,
      answers: Object.values(answers),
    });
    const goodAnswer = JSON.stringify(
      Object.values(answers).findIndex((item) => item.isGoodAnswer)
    );

    axios
      .post(
        import.meta.env.VITE_API_URL + "/questions",
        { course: "courses/" + courseId, settings, goodAnswer },
        {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }
      )
      .then(({ data: { id: questionId } }) => {
        const oldQuestions = course.value.sequence.questions || [];

        course.value.sequence.questions = [
          ...oldQuestions,
          { questionId, question: content },
        ];

        console.log("debug", course.value.sequence);

        axios
          .patch(
            import.meta.env.VITE_API_URL + "/courses/" + courseId,
            {
              sequence: JSON.stringify(course.value.sequence),
              updatedAt: "NOW",
            },
            {
              headers: {
                Authorization: `Bearer ${store.user.token}`,
              },
            }
          )
          .then(() => {
            store.courses.list[courseId].sequence = JSON.stringify(
              course.value.sequence
            );
            toastr.success("Question ajouté au cours", "", { timeOut: 3000 });
            resetQuestion();
          });
      })
      .catch((err) => {
        console.log("debug", err);
      });
  } else {
    toastr.error("Réponses incomplètes", "", { timeOut: 3000 });
  }
};

const isDisabled = (answerId) => {
  switch (answerId) {
    case 1:
      return (
        question.value.answer_2.isGoodAnswer ||
        question.value.answer_3.isGoodAnswer ||
        question.value.answer_4.isGoodAnswer
      );
      break;
    case 2:
      return (
        question.value.answer_1.isGoodAnswer ||
        question.value.answer_3.isGoodAnswer ||
        question.value.answer_4.isGoodAnswer
      );
      break;
    case 3:
      return (
        question.value.answer_1.isGoodAnswer ||
        question.value.answer_2.isGoodAnswer ||
        question.value.answer_4.isGoodAnswer
      );
      break;
    case 4:
      return (
        question.value.answer_1.isGoodAnswer ||
        question.value.answer_2.isGoodAnswer ||
        question.value.answer_3.isGoodAnswer
      );
      break;

    default:
      break;
  }
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>
        Création du Quiz : question n°{{
          course?.sequence?.questions?.length + 1 || 1
        }}
      </h2>
      <div class="container-input">
        <div class="firstline">
          <div class="input-item">
            <label :for="question.content">Question</label>
            <input class="innput" v-model="question.content" />
          </div>
        </div>

        <div class="wrapperAnswers">
          <div class="input-item">
            <label :for="question.answer_1.label">Réponse 1</label>
            <input
              class="innput"
              maxlength="70"
              v-model="question.answer_1.label"
            />
            <input
              type="checkbox"
              v-model="question.answer_1.isGoodAnswer"
              :disabled="isDisabled(1)"
            />
          </div>
          <div class="input-item">
            <label :for="question.answer_2.label">Réponse 2</label>
            <input
              class="innput"
              maxlength="70"
              v-model="question.answer_2.label"
            />
            <input
              type="checkbox"
              v-model="question.answer_2.isGoodAnswer"
              :disabled="isDisabled(2)"
            />
          </div>
          <div class="input-item">
            <label :for="question.answer_3.label">Réponse 3</label>
            <input
              class="innput"
              maxlength="70"
              v-model="question.answer_3.label"
            />
            <input
              type="checkbox"
              v-model="question.answer_3.isGoodAnswer"
              :disabled="isDisabled(3)"
            />
          </div>
          <div class="input-item">
            <label :for="question.answer_4.label">Réponse 4</label>
            <input
              class="innput"
              maxlength="70"
              v-model="question.answer_4.label"
            />
            <input
              type="checkbox"
              v-model="question.answer_4.isGoodAnswer"
              :disabled="isDisabled(4)"
            />
          </div>
        </div>

        <button class="bttn bttn-prim" @click="handleSubmit">Valider</button>
      </div>

      <button class="bttn bttn-drk back-btn">
        <RouterLink :to="`/db/course/page/${courseId}`">
          <va-icon name="chevron_left" />Revenir au cours
        </RouterLink>
      </button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.container-dashboard {
  display: flex;

  div.main-page {
    padding: 2rem;
    width: 100%;

    div.container-input {
      background-color: rgb(245, 245, 245);
      padding: 1rem;
      width: 50%;
      margin: 2rem auto 0 auto;
      text-align: center;
      div.firstline {
        display: flex;
        justify-content: center;

        div {
          width: 50%;
        }
      }

      div.input-item {
        margin-bottom: 2rem;
      }

      button {
        margin: 2rem 1rem 0 auto;
        // padding-left: 20rem;
        // padding-right: 20rem;
        width: 100%;
      }
    }

    button.back-btn {
      margin-top: 7rem;

      a {
        text-decoration: none;
        color: white;
      }
    }
  }

  .wrapperAnswers {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2vw;
  }
}
</style>
