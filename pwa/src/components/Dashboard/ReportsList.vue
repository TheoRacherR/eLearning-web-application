<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import { ref, watchEffect } from "vue";
import router from "../../router";
import { store, listCourses, listReports } from "../../store/store";
import toastr from "toastr";
import axios from "axios";

const items = ref({});
const validItems = ref({});
const course = ref({})
const coursesList = ref({})


watchEffect(() => {
  items.value = store.reports.list;
  for (const item in items.value) {

    coursesList.value = store.courses.list;
    for (const i in coursesList.value) {
      if (coursesList.value[i].id == parseInt(items.value[item].course_id)) {
        course.value = coursesList.value[i];
      }
    }
    
    validItems.value = {
      ...validItems.value,
      [parseInt(Object.keys(items.value).at(-1)) - item]: { ...items.value[item], title: course.value.title },
    };
  }


});


const handleDelete = (courseId) => {
  axios
    .delete(import.meta.env.VITE_API_URL + "/course_reports/" + courseId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      delete listCourses.value[courseId];
      toastr.success("Signalement supprimé", "", { timeOut: 3000 });
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

// const handleCloseReport = (reportId) => {
//   axios
//     .patch(import.meta.env.VITE_API_URL + "/course_reports/" + reportId,
//       {
//         done: true,
//       },
//       {
//       headers: {
//         Authorization: `Bearer ${store.user.token}`,
//       },
//     })
//     .then(() => {
//       toastr.success("Signalement mis à jour", "", { timeOut: 3000 });
//     })
//     .catch((err) => {
//       console.log("debug", err);
//     });
// };
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="container-commlist">
      <h2>Liste des signalements:</h2>
      <div class="container-grid">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Utilisateur</th>
              <th>Cours</th>
              <th>Raison</th>
              <th>Page du cours</th>
              <!--<th>Signalement classé ?</th>-->
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="report in validItems">
              <td>{{ report.id }}</td>

              <!--<td v-if="course.user_id.firstname.length > 0 || course.user_id.lastname.length > 0">{{ course.user_id.firstname + ' ' + course.user_id.lastname }}</td>
                            <td v-else-if="course.user_id.firstname.length === 0 && course.user_id.lastname.length === 0">-</td>-->

              <td><RouterLink :to="`/db/user/${report.user_id}`">{{ report.firstname }} {{ report.lastname }}</RouterLink></td>

              <td>{{ report.title }}</td>

              <td v-if="report.reason.length > 0">
                {{ report.reason.slice(0, 100) }}
              </td>
              <td v-else>-</td>

              <td>
                <button class="bttn bttn-drk">
                  <RouterLink :to="`/db/course/${report.course_id}`"
                    ><va-icon name="article"
                  /></RouterLink>
                </button>
              </td>

              <!--<td class="accepted" v-if="report.done">
                <va-icon name="verified" />
              </td>
              <td class="waiting" v-else><va-icon name="hourglass_empty"/></td>-->
              <td>
                <!--Page de tous les signalements-->
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/course-reports/${report.course_id}`">
                    <va-icon name="last_page"/>
                  </RouterLink>
                </button>

                <!--Rejeter
                <button v-if="!report.done" class="bttn bttn-succ" @click="handleCloseReport(report.id)">
                  <va-icon name="done"/>
                </button>-->

                <!--Supprimer-->
                <button class="bttn bttn-dng" @click="handleDelete(report.id)">
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
        background-color: rgb(133, 63, 121);
        color: #fff;
      }
      th,
      td {
        padding: 1rem;
      }

      td button {
        margin-right: 1rem;
      }

      td.accepted {
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
