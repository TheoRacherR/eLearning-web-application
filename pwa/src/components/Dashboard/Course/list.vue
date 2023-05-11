<script setup>
import axios from "axios";
import { ref, watch, onMounted, watchEffect } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;

  for (const item in items.value) {
    if (items.value[item].userId === "/users/" + store.user.id) {
      validItems.value = {
        ...validItems.value,
        [item]: { ...items.value[item] },
      };
    }
  }
});

const deleteCourse = (courseId) => {
  axios
    .delete(import.meta.env.VITE_API_URL + "/courses/" + courseId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      delete store.courses.list[courseId];
      delete validItems.value[courseId];
      toastr.success("Cours supprimé", "", { timeout: 3000 });
    })
    .catch((err) => {
      console.log("debug", err);
    });
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="main-page">
      <h2>Liste de vos cours</h2>
      <div class="container-grid">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Description</th>
              <th>Valide</th>
              <th>Prix</th>
              <th>Nombre d'achat</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="course in validItems">
              <td>{{ course.id }}</td>
              <td>{{ course.title }}</td>
              <td>
                {{ course.description.slice(0, 100) }}
                <span v-if="course.description.length >= 97">...</span>
              </td>
              <td class="verifed" v-if="course.valid == 1">
                <va-icon name="verified" />
              </td>
              <td class="waiting" v-else-if="course.valid == 0">
                <va-icon name="hourglass_empty" />
              </td>
              <td class="refused" v-else><va-icon name="close" /></td>
              <td>{{ course.price }} €</td>
              <td>0</td>

              <td>
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/course/page/${course.id}`">
                    <va-icon name="last_page" />
                  </RouterLink>
                </button>
                <button class="bttn bttn-dng" @click="deleteCourse(course.id)">
                  <va-icon name="delete" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
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
    display: flex;
    flex-direction: column;

    div.container-grid {
      display: grid;
      grid-auto-columns: auto;
      grid-auto-rows: auto;

      thead {
        background-color: rgb(161, 61, 21);
        color: #fff;
      }
      th,
      td {
        padding: 1rem;
      }

      td button {
        margin-right: 1rem;
      }

      td.verifed {
        color: #52b425;
      }

      td.refused {
        color: rgb(168, 43, 43);
      }

      td.waiting {
        color: #eb8c1f;
      }

      td button a {
        text-decoration: none;
        color: #fff;
      }

      tbody tr:nth-child(even) {
        background-color: #f5f5f5;

        tbody tr:nth-child(even) {
          background-color: #f5f5f5;
        }
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
