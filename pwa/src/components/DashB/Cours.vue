<script setup>
import axios from "axios";
import { ref, watchEffect, onMounted } from "vue";
import { store } from "../../store/store";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import toastr from "toastr";

const editedCourseId = ref(null);
const editedCourse = ref(null);

const creatingCourse = ref(false);
const createCourse = ref({
  title: "",
  description: "",
});

const myEditor = ref(null);
const createEditor = ref(null);

const {
  data: { ["hydra:member"]: coursesRaw },
} = await axios.get(import.meta.env.VITE_API_URL + "/courses", {
  headers: {
    Authorization: `Bearer ${store.user.token}`,
  },
});

const courses = ref([]);

watchEffect(() => {
  if (store.user.isAdmin) {
    coursesRaw.map((course) => {
      const userId =
        course.userId.split("/")[course.userId.split("/").length - 1];

      axios
        .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .then(({ data: user }) => {
          courses.value.push({
            Titre: course.title,
            Author: `${user.firstname} ${user.lastname}`,
            isValid: course.valid,
            id: course.id,
            content: course.content,
          });
        });
    });
  } else {
    console.log("debug", store.user.firstname);

    coursesRaw
      .filter(
        (courseItem) =>
          parseInt(
            courseItem.userId.split("/")[
              courseItem.userId.split("/").length - 1
            ]
          ) === store.user.id
      )
      .map((course) => {
        courses.value.push({
          Titre: course.title,
          Author: `${store.user.firstname} ${store.user.lastname}`,
          isValid: course.valid,
          id: course.id,
          content: course.content,
        });
      });
  }
});

const columns = [
  {
    key: "Titre",
    sortable: true,
  },
  {
    key: "Author",
    sortable: true,
  },
  {
    key: "actions",
    width: 80,
  },
];

const openModalToEditItemById = (index) => {
  const {
    isValid,
    id,
    Author,
    content: contentCourse,
    ...editableField
  } = courses.value[index];

  editedCourse.value = { ...editableField };
  editedCourseId.value = index;

  setTimeout(() => {
    myEditor.value.setHTML(contentCourse);
  }, 250);
};

const editCourse = () => {
  const body = { ...editedCourse.value, content: myEditor.value.getHTML() };

  courses.value[editedCourseId.value] = {
    ...courses.value[editedCourseId.value],
    ...body,
  };

  axios
    .put(
      import.meta.env.VITE_API_URL +
        "/courses/" +
        courses.value[editedCourseId.value].id,
      { title: body.Titre, content: myEditor.value.getHTML() },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      editedCourse.value = null;
      editedCourseId.value = null;
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const resetEditedCourse = () => {
  editedCourse.value = null;
  editedCourseId.value = null;
};

const reviewCourse = (isValid, id, rowIndex) => {
  axios
    .put(
      import.meta.env.VITE_API_URL + "/courses/" + id,
      {
        valid: isValid,
      },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      courses.value[rowIndex] = {
        ...courses.value[rowIndex],
        isValid: isValid,
      };
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const handleCreate = () => {
  const body = {
    ...createCourse.value,
    content: createEditor.value.getHTML(),
    valid: false,
    userId: "users/" + store.user.id,
    createdAt: "NOW",
    updatedAt: "NOW",
  };

  axios
    .post(import.meta.env.VITE_API_URL + "/courses", body, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      courses.value.push({
        Titre: createCourse.value.title,
        Author: `${store.user.firstname} ${store.user.lastname}`,
        isValid: false,
        id: undefined,
        content: createEditor.value.getHTML(),
      });

      resetCreate();
    })
    .catch((err) => {
      console.log(err);
    });
};
const resetCreate = () => {
  createCourse.value = {
    title: "",
    description: "",
  };
  creatingCourse.value = false;
};

getElementByClass("va-modal__default-cancel-button").classList.add(
  "bttn bttn-prim-out"
);
</script>

<template>
  <div class="wrapperPage">
    <h1
      v-if="
        (store.user.teacherStatus === 'VALIDATED' && store.user.isTeacher) ||
        store.user.isAdmin
      "
    >
      Gérer les cours
    </h1>
    <h1
      v-else-if="
        store.user.teacherStatus === 'VALIDATED' && store.user.isTeacher
      "
    >
      Gérer vos cours
    </h1>
    <h1 v-else>Un administrateur va valider votre demande.</h1>

    <button
      class="bttn bttn-drk"
      @click="
        () => {
          creatingCourse = !creatingCourse;
        }
      "
    >
      Ajouter un cours
    </button>
    <va-modal
      class="modalCreateCourse"
      :model-value="creatingCourse"
      title="Create Course"
      size="small"
      @ok="handleCreate"
      @cancel="resetCreate"
    >
      <va-input
        v-for="key in Object.keys(createCourse)"
        :key="key"
        class="my-3"
        :label="key"
        v-model="createCourse[key]"
      />
      <QuillEditor ref="createEditor" theme="snow" />
    </va-modal>

    <va-data-table
      :items="courses"
      :columns="columns"
      :wrapper-size="500"
      :item-size="46"
      virtual-scroller
    >
      <template #cell(actions)="{ rowIndex }">
        <va-button
          v-if="!courses[rowIndex].isValid && store.user.isAdmin"
          preset="plain"
          icon="check"
          @click="reviewCourse(true, courses[rowIndex].id, rowIndex)"
        />
        <va-button
          v-if="!courses[rowIndex].isValid && store.user.isAdmin"
          preset="plain"
          icon="close"
          @click="reviewCourse(false, courses[rowIndex].id, rowIndex)"
        />
        <va-button
          v-else
          preset="plain"
          icon="edit"
          @click="openModalToEditItemById(rowIndex)"
        />
      </template>
    </va-data-table>

    <va-modal
      class="modalEditCourse"
      :model-value="!!editedCourse"
      title="Edit item"
      size="small"
      @ok="editCourse"
      @cancel="resetEditedCourse"
    >
      <va-input
        v-for="key in Object.keys(editedCourse)"
        :key="key"
        class="my-3"
        :label="key"
        v-model="editedCourse[key]"
      />
      <QuillEditor ref="myEditor" theme="snow" />
    </va-modal>
  </div>
</template>

<style lang="scss" scoped>
.wrapperPage {
  display: flex;
  flex-direction: column;
  padding: 3rem 5rem;
}

.modalEditCourse {
  .va-input {
    display: block;
  }
}
.modalCreateCourse {
  .va-input {
    display: block;
  }
}

button.va-modal__default-cancel-button > span {
  color: wheat;
}
</style>
