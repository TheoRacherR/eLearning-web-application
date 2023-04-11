<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import { ref, watchEffect } from "vue";
import router from "../../router";
import { store, listCourses } from "../../store/store";
import toastr from "toastr";
import axios from "axios";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}
// else if (!store.user.isAdmin) {
//   router.push("/");
//   toastr.error("Vous n'êtes pas autorisé à accéder au backoffice ", "", {
//     timeOut: 3000,
//   });
// }

const items = ref({});

watchEffect(() => {
  items.value = store.courses.list;
});

const handleDelete = (courseId) => {
  axios
    .delete(import.meta.env.VITE_API_URL + "/courses/" + courseId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      delete listCourses.value[courseId];
      toastr.success("Utilisateur supprimé", "", { timeOut: 3000 });
    })
    .catch((err) => {
      console.log("debug", err);
    });
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="container-commlist">
      <h2>Liste des cours:</h2>
      <div class="container-grid">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Auteur</th>
              <th>Titre</th>
              <th>Description</th>
              <th>Date d'ajout</th>
              <th>Cours validé ?</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="course in items">
              <td v-if="course.id > 0">{{ course.id }}</td>
              <td v-else>-</td>

              <!--<td v-if="course.user_id.firstname.length > 0 || course.user_id.lastname.length > 0">{{ course.user_id.firstname + ' ' + course.user_id.lastname }}</td>
                            <td v-else-if="course.user_id.firstname.length === 0 && course.user_id.lastname.length === 0">-</td>-->

              <td>{{ course.user_id_id }}</td>

              <td v-if="course.title.length > 0">{{ course.title }}</td>
              <td v-else>-</td>

              <td v-if="course.description.length > 0">
                {{ course.description.slice(0, 100) }}
              </td>
              <td v-else>-</td>

              <td>{{ course.created_at }}</td>

              <td class="verifed" v-if="course.valid == 1">
                <va-icon name="verified" />
              </td>
              <td class="waiting" v-else-if="course.valid == 0">
                <va-icon name="hourglass_empty" />
              </td>
              <td class="refused" v-else><va-icon name="close" /></td>
              <td>
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/course/${course.id}`"
                    ><va-icon name="last_page"
                  /></RouterLink>
                </button>
                <!--Aller sur le cours-->
                <!--<button class="bttn bttn-dng-out"><va-icon name="close"/></button>-->
                <!--Rejeter-->
                <button class="bttn bttn-dng" @click="handleDelete(course.id)">
                  <va-icon name="delete" />
                </button>
                <!--Supprimer-->
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
  width: 100%;
  height: 100%;
  display: flex;
  /* background-color: aqua; */

  div.container-commlist {
    padding: 2rem 5rem;
    width: 90%;

    div.container-grid {
      display: grid;
      grid-auto-columns: auto;
      grid-auto-rows: auto;

      thead {
        background-color: rgb(103, 133, 63);
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
}
</style>
