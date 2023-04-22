<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";
import { QuillEditor } from "@vueup/vue-quill";
import "quill/dist/quill.snow.css";

const chapterTitle = ref("");
const chapter = ref("");
const chapters = ref({});
const nbChapter = ref(0);

const chapterEditorOn = ref(false);

const course = ref({
  title: "",
  description: "",
  price: "",
});

const formerId = ref();

onMounted(() => {
  resetData();

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

const resetData = async () => {
  course.value = {
    title: "",
    description: "",
    price: "",
    chapter: {},
  };
};

const handleSubmitCourse = async () => {
  if (formerId) {
    axios
      .post(
        import.meta.env.VITE_API_URL + "/courses",
        {
          userId: store.user.id,
          content: "",
          title: course.title,
          description: course.description,
          price: course.price,
          valid: 0,
          createdAt: "NOW",
          updatedAt: "NOW",
          sequence: JSON.stringify(chapters.value),
        },
        {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        }
      )
      .catch((err) => {
        console.log(err);
      });
  } else {
    console.log("error");
  }
};

const addNewChapter = async () => {
  console.log(chapter.value.getHTML());
  // chapters.value = ({ chapt });
  chapters.value[nbChapter.value] = {
    title: chapterTitle.value,
    content: chapter.value.getHTML(),
  };
  chapterTitle.value = "";
  document.getElementsByClassName("ql-editor")[0].childNodes[0].remove();
  chapter.value = "";
  nbChapter.value = nbChapter.value + 1;

  chapterEditorOn.value = false;
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>Création du cours</h2>
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
              />
              <span class="input-group-text">€</span>
            </div>
          </div>
        </div>

        <div class="input-item">
          <label :for="course.description">Description du cours</label>
          <input
            class="innput"
            placeholder="Ecrire ici la description du cours..."
            v-model="course.description"
          />
        </div>

        <div v-if="!chapterEditorOn" class="buttons">
          <button class="bttn bttn-succ" @click="chapterEditorOn = true">
            Ajouter un chaptire
          </button>
          <button class="bttn bttn-prim" @click="handleSubmitCourse">
            Valider le cours
          </button>
        </div>

        <div v-else>
          <input
            class="innput"
            v-model="chapterTitle"
            placeholder="Titre du chaptire"
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
              { color: [] },
              { background: [] },
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
          <button class="bttn bttn-wng validate" @click="addNewChapter">Valider</button>
        </div>
        <div>
          <div v-for:="c in chapters">Chapitre "{{ c.title }}"</div>
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
      width: 30%;
      margin-left: 70%;
      justify-content: space-around;

    }

    button.validate {
      margin-top: 2rem;
      width: 15%;
      margin-left: 85%;
    }

  }
}
</style>
