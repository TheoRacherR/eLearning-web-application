<script setup>
import axios from "axios";
import { ref, watch, onMounted, watchEffect } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";
import { QuillEditor } from "@vueup/vue-quill";
import "quill/dist/quill.snow.css";
import { RouterLink } from "vue-router";

const courseId = ref();

const items = ref({});
const validItems = ref({});

const chapterTitle = ref("");
const chapter = ref("");
const chapters = ref({});
const nbChapter = ref(0);

const number = ref("0123456789");
const numberV = ref("0123456789,");

const floated = ref(false);

const editChapter = ref(false);
const chapterEditorOn = ref(false);
const idToEdit = ref();

const submitting = ref(false);

const course = ref({
  title: "",
  description: "",
  price: "",
});

const questions = ref({});
const questionsGenerated = ref(false);

const formerId = ref();

watchEffect(() => {
  items.value = store.courses.list;
  for (const item in items.value) {
    if (items.value[item].id === parseInt(courseId.value)) {
      validItems.value = {
        ...validItems.value,
        [item]: { ...items.value[item] },
      };
      course.value.title = items.value[item].title;
      course.value.description = items.value[item].description;
      course.value.price = items.value[item].price.toString();
      break;
    }
  }
  if (Object.values(validItems.value).length > 0) {
    chapters.value = JSON.parse(validItems.value[courseId.value].sequence)[
      "chapters"
    ];
    nbChapter.value = Object.values(
      JSON.parse(validItems.value[courseId.value].sequence)["chapters"]
    ).length;
  }
});

onMounted(() => {
  courseId.value = router.currentRoute.value.params.id;
  if (store.user.isConnected) {
    axios
      .get(import.meta.env.VITE_API_URL + "/formers", {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then(({ data: { ["hydra:member"]: formerRaw } }) => {
        formerRaw.map((item) => {
          const userIID = parseInt(
            item.userId.split("/")[item.userId.split("/").length - 1]
          );
          if (userIID === store.user.id) {
            formerId.value = userIID;
          }
        });
      });
  }
});

const generateQuestions = async () => {
  const obj = JSON.parse(validItems.value[courseId.value].sequence)[
    "questions"
  ];
  for (const quest in obj) {
    axios
      .get(import.meta.env.VITE_API_URL + "/questions/" + quest, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then((data) => {
        console.log(JSON.parse(data.data.settings).answers[0]);
        questions.value[data.data.id] = {
          question: JSON.parse(data.data.settings).content,
          answer1: JSON.parse(data.data.settings).answers[0],
          answer2: JSON.parse(data.data.settings).answers[1],
          answer3: JSON.parse(data.data.settings).answers[2],
          answer4: JSON.parse(data.data.settings).answers[3],
        };
        console.log(questions.value);
      });
  }
  questionsGenerated.value = true;
};

const handleSubmitCourse = async () => {
  if (
    formerId &&
    course.value.title.length > 0 &&
    course.value.description.length > 0 &&
    course.value.price.length > 0 &&
    Object.values(chapters.value).length > 0
  ) {
    submitting.value = true;
    axios
      .patch(
        import.meta.env.VITE_API_URL + "/courses/" + courseId.value,
        {
          title: course.value.title,
          description: course.value.description,
          price: parseInt(course.value.price),
          updatedAt: "NOW",
          sequence: JSON.stringify({
            ["chapters"]: chapters.value,
            ["questions"]: questions.value,
          }),
        },
        {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }
      )
      .then(() => {
        toastr.success("Cours modifié", "", { timeOut: 3000 });
        submitting.value = false;
      })
      .catch((err) => {
        console.log(err);
      });
  } else {
    toastr.error("Tous les champs ne sont pas remplis!", "", { timeOut: 3000 });
    console.log("error");
  }
};

const deleteAQuestion = async (id) => {
  delete questions.value[id];
  axios
    .delete(import.meta.env.VITE_API_URL + "/questions/" + id, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      axios
        .patch(
          import.meta.env.VITE_API_URL + "/courses/" + courseId.value,
          {
            valid: 0,
            updatedAt: "NOW",
            sequence: JSON.stringify({
              ["chapters"]: chapters.value,
              ["questions"]: questions.value,
            }),
          },
          {
            headers: {
              Authorization: `Bearer ${store.user.token}`,
            },
          }
        )
        .then(() => {
          toastr.success("Question supprimé", "", { timeOut: 3000 });
          submitting.value = false;
        })
        .catch((err) => {
          console.log(err);
        });
    });
  console.log("deleted");
};

const addNewChapter = async () => {
  if (
    chapterTitle.value.length > 0 &&
    chapter.value.getHTML() != "<p><br></p>"
  ) {
    chapters.value[nbChapter.value] = {
      title: chapterTitle.value,
      content: chapter.value.getHTML(),
    };

    axios
      .patch(
        import.meta.env.VITE_API_URL + "/courses/" + courseId.value,
        {
          valid: 0,
          updatedAt: "NOW",
          sequence: JSON.stringify({
            chapters: chapters.value,
            questions: questions.value,
          }),
        },
        {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }
      )
      .then(() => {
        toastr.success("Chapitre ajouté", "", { timeOut: 3000 });

        chapterTitle.value = "";
        document.getElementsByClassName("ql-editor")[0].childNodes[0].remove();
        chapter.value = "";
        nbChapter.value = nbChapter.value + 1;
        chapterEditorOn.value = false;
      })
      .catch((err) => {
        console.log(err);
      });
  }
};

const deleteAChapter = async (id) => {
  delete chapters.value[id];
  console.log(chapters.value);
  console.log("deleted");
};

const cancelEditor = async () => {
  chapterTitle.value = "";
  document.getElementsByClassName("ql-editor")[0].childNodes[0].remove();
  chapter.value = "";
  chapterEditorOn.value = false;
};

const checkNumber = () => {
  if (!course.value.price.includes(",")) {
    floated.value = false;
  }
  if (floated.value === false) {
    if (
      !numberV.value.includes(
        course.value.price.substring(
          course.value.price.length - 1,
          course.value.price.length
        )
      )
    ) {
      course.value.price = course.value.price.substring(
        course.value.price.length - 1,
        0
      );
    }
    if (course.value.price.includes(",")) {
      console.log("first");
      if (course.value.price.length > 0) {
        console.log("1");
        floated.value = true;
      } else {
        console.log("2");
        course.value.price = "";
      }
    }
  } else {
    if (
      !number.value.includes(
        course.value.price.substring(
          course.value.price.length - 1,
          course.value.price.length
        )
      )
    ) {
      course.value.price = course.value.price.substring(
        course.value.price.length - 1,
        0
      );
    }
  }
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>Page du cours "{{ course.title }}"</h2>
      <div>
        <div class="firstline">
          <div class="input-item">
            <label :for="course.title">Titre du cours</label>
            <input class="innput" v-model="course.title" placeholder="Titre" />
          </div>

          <div class="input-item">
            <label :for="course.price">Prix du cours</label>
            <div class="input-group mb-3" style="margin-top: 1rem">
              <input
                type="text"
                class="form-control"
                aria-label="Amount (to the nearest dollar)"
                v-model="course.price"
                placeholder="Prix"
                @input="checkNumber"
              />
              <span class="input-group-text">€</span>
            </div>
          </div>
        </div>

        <div class="secondline">
          <div class="input-item">
            <label for="formFile">Image du cours à renseigner</label>
            <input class="form-control innput" type="file" id="formFile" />
          </div>
          <div class="input-item">
            <label :for="course.description">Description du cours</label>
            <input
              class="innput"
              placeholder="Ecrire ici la description du cours..."
              v-model="course.description"
            />
          </div>
        </div>

        <div v-if="!chapterEditorOn" class="buttons">
          <button class="bttn bttn-succ" @click="chapterEditorOn = true">
            Ajouter un chapitre
          </button>
          <button class="bttn bttn-prim" @click="handleSubmitCourse">
            <div
              class="spinner-border spinner-border-sm"
              role="status"
              v-if="submitting"
            >
              <span class="visually-hidden">Loading...</span>
            </div>
            Modifier le cours
          </button>
        </div>

        <div v-else>
          <input
            class="innput"
            v-model="chapterTitle"
            placeholder="Titre du chapitre"
          />
          <QuillEditor
            :toolbar="[
              { font: [] },
              { size: ['small', false, 'large', 'huge'] },
              'bold',
              'italic',
              'underline',
              'strike',
              [],
              'blockquote',
              'code-block',
              'link',
              'image',
              [],
              { align: [] },
              { direction: 'rtl' },
              [],
              { header: 1 },
              { header: 2 },
              { list: 'ordered' },
              { list: 'bullet' },
              [],
              { script: 'sub' },
              { script: 'super' },
              { indent: '-1' },
              { indent: '+1' },
            ]"
            ref="chapter"
            contentType="delta"
            placeholder="Rédigez votre cours ici..."
          />
          <div class="group-buttons">
            <button class="bttn bttn-wng" @click="addNewChapter">
              Valider
            </button>
            <button class="bttn bttn-dng" @click="cancelEditor">Annuler</button>
          </div>
        </div>
        <div class="list-course">
          <div v-for:="(item, index) in chapters" class="itemss">
            <div>Chapitre {{ parseInt(index) + 1 }} "{{ item.title }}"</div>
            <div>
              <!--<button class="bttn bttn-wng" @click="clickToEdit(index)"><va-icon name="edit"/></button>-->
              <button class="bttn bttn-dng" @click="deleteAChapter(index)">
                <va-icon name="delete" />
              </button>
            </div>
          </div>
        </div>

        <!-- Ajouter une question -->
        <button class="bttn bttn-wng quiz-btn" v-if="questions?.length === 0">
          <RouterLink :to="`/db/quiz/create/${courseId}`">
            <va-icon name="add" />
            Créer un Quiz
          </RouterLink>
        </button>
        <button class="bttn bttn-wng quiz-btn" v-else>
          <RouterLink :to="`/db/quiz/create/${courseId}`">
            <va-icon name="add" />
            Ajouter des questions
          </RouterLink>
        </button>

        <div class="list-course">
          <button class="bttn bttn-drk quiz-btn" @click="generateQuestions">
            <va-icon name="refresh" />
            Rafraichir les questions
          </button>
          <div v-for:="(item, index) in questions" class="itemss">
            <div>
              <div>Question : "{{ item.question }}"</div>
              <div v-if="item.answer1.isGoodAnswer" style="color: green">
                Réponse 1 : {{ item.answer1.label }}
                <va-icon name="check"></va-icon>
              </div>
              <div v-else style="color: red">
                Réponse 1 : {{ item.answer1.label }}
                <va-icon name="close"></va-icon>
              </div>

              <div v-if="item.answer2.isGoodAnswer" style="color: green">
                Réponse 2 : {{ item.answer2.label }}
                <va-icon name="check"></va-icon>
              </div>
              <div v-else style="color: red">
                Réponse 2 : {{ item.answer2.label }}
                <va-icon name="close"></va-icon>
              </div>

              <div v-if="item.answer3.isGoodAnswer" style="color: green">
                Réponse 3 : {{ item.answer3.label }}
                <va-icon name="check"></va-icon>
              </div>
              <div v-else style="color: red">
                Réponse 3 : {{ item.answer3.label }}
                <va-icon name="close"></va-icon>
              </div>

              <div v-if="item.answer4.isGoodAnswer" style="color: green">
                Réponse 4 : {{ item.answer4.label }}
                <va-icon name="check"></va-icon>
              </div>
              <div v-else style="color: red">
                Réponse 4 : {{ item.answer4.label }}
                <va-icon name="close"></va-icon>
              </div>
            </div>
            <div>
              <!--<button class="bttn bttn-wng" @click="clickToEdit(index)"><va-icon name="edit"/></button>-->
              <button class="bttn bttn-dng" @click="deleteAQuestion(index)">
                <va-icon name="delete" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.container-dashboard {
  display: flex;

  div.main-page {
    padding: 2rem;
    width: 100%;

    h2 {
      margin-bottom: 2rem;
    }

    div.firstline {
      display: flex;
      justify-content: center;

      div:nth-child(1) {
        flex: 3;
      }
      div:nth-child(2) {
        flex: 1;
      }
    }

    div.secondline {
      display: flex;
      justify-content: center;

      div:nth-child(1) {
        flex: 1;
      }
      div:nth-child(2) {
        flex: 3;
      }
    }

    div.input-item {
      margin-bottom: 2rem;

      div.wrap-input-price {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
      }
    }

    div.buttons {
      display: flex;
      margin-top: 2rem;
      width: 35%;
      margin-left: 65%;
      justify-content: space-between;
    }

    div.group-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 2rem;
      margin-left: 85%;
      button {
        width: 46%;
      }
    }

    div.list-course {
      margin: 5rem 0;

      div.itemss {
        padding: 1rem;
        display: flex;
        justify-content: space-between;

        div {
          margin: auto 0;
        }
      }

      div.itemss:nth-child(2n-1) {
        background-color: rgb(245, 245, 245);
      }
    }
    button.quiz-btn a {
      text-decoration: none;
      color: white;
    }
  }
}
</style>
