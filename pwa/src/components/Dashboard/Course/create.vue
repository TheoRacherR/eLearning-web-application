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

const number = ref("0123456789.");
const numberV = ref("0123456789");

const floated = ref(false);

const editChapter = ref(false);
const chapterEditorOn = ref(false);
const idToEdit = ref();

const submitting = ref(false);
let image;

const course = ref({
  title: "",
  description: "",
  price: "",
  image: "",
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
  chapterTitle.value = "";
  chapter.value = "";
  chapters.value = {};
  nbChapter.value = 0;

  editChapter.value = false;
  chapterEditorOn.value = false;
};

const handleChangeImage = (event) => {
    const selectedfile = event.target.files;
    if (selectedfile.length > 0) {
        const [imageFile] = selectedfile;
        const fileReader = new FileReader();
        fileReader.onload = () => {
            image = fileReader.result;
        };
        fileReader.readAsDataURL(imageFile);
    }
}

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
      .post(
        import.meta.env.VITE_API_URL + "/courses",
        {
          userId: "users/" + store.user.id,
          user_id: "users/" + store.user.id,
          content: "",
          stripeProductId: "string",
          stripePriceId: "string",
          title: course.value.title,
          description: course.value.description,
          price: parseFloat(course.value.price),
          valid: 0,
          image: image,
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
      .then(({ data }) => {
        console.log("debug", data);
        store.courses.list[data.course] = {
          id: data.course,
          userId: "/users/" + store.user.id,
          user_id: "/users/" + store.user.id,
          content: "",
          stripeProductId: "string",
          stripePriceId: "string",
          title: course.value.title,
          description: course.value.description,
          price: parseInt(course.value.price),
          valid: 0,
          createdAt: "NOW",
          updatedAt: "NOW",
          sequence: JSON.stringify(chapters.value),
        };
        toastr.success("Cours ajouté", "", { timeOut: 3000 });
        submitting.value = false;
        resetData();
      })
      .catch((err) => {
        console.log(err);
      });
  }
};

const editOrAdd = async () => {
  if (editChapter.value) {
    // editAChapter();
  } else {
    addNewChapter();
  }
};

// const clickToEdit = async (id) => {
//   idToEdit.value = id;
//   editChapter.value = true;
//   chapterEditorOn.value = true;
//   chapterTitle.value = Object.values(chapters.value)[id].title;
//   chapter.value = Object.values(chapters.value)[id].content;
//   document.getElementsByClassName("ql-editor").innerHTML = "<div>" + Object.values(chapters.value)[id].content + "</div>";
// }

const addNewChapter = async () => {
  if (
    chapterTitle.value.length > 0 &&
    chapter.value.getHTML() != "<p><br></p>"
  ) {
    chapters.value["chapters"] = {
      ...chapters.value["chapters"],
      [nbChapter.value]: {
        title: chapterTitle.value,
        content: chapter.value.getHTML(),
      },
    };
    console.log(chapters.value);
    chapterTitle.value = "";
    document.getElementsByClassName("ql-editor")[0].childNodes[0].remove();
    chapter.value = "";
    nbChapter.value = nbChapter.value + 1;
    chapterEditorOn.value = false;
  }
};

const editAChapter = async () => {
  chapters.value["chapters"] = {
    ...chapters.value["chapters"],
    [idToEdit.value]: {
      title: chapterTitle.value,
      content: chapter.value.getHTML(),
    },
  };
  chapterTitle.value = "";
  document.getElementsByClassName("ql-editor")[0].childNodes[0].remove();
  chapter.value = "";
  chapterEditorOn.value = false;
};

const deleteAChapter = async (id) => {
  delete chapters.value["chapters"][id];
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
                @input="checkNumber"
              />
              <span class="input-group-text">€</span>
            </div>
          </div>
        </div>

        <div class="secondline">
            <div class="input-item">
                <label for="formFile">Image du cours à renseigner</label>
                <input class="form-control innput" type="file" id="formFile" v-on:change="handleChangeImage" />
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
            Valider le cours
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
            <button class="bttn bttn-wng" @click="editOrAdd">Valider</button>
            <button class="bttn bttn-dng" @click="cancelEditor">Annuler</button>
          </div>
        </div>
        <div class="list-course">
          <div v-for:="(item, index) in chapters['chapters']" class="itemss">
            <div>Chapitre {{ parseInt(index) + 1 }} "{{ item.title }}"</div>
            <div>
              <!--<button class="bttn bttn-wng" @click="clickToEdit(index)"><va-icon name="edit"/></button>-->
              <button class="bttn bttn-dng" @click="deleteAChapter(index)">
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
      width: 20%;
      margin-left: 80%;
      justify-content: space-around;
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
  }
}
</style>
