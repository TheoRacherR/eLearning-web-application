<script setup>
import { ref, watchEffect } from "vue";
import { RouterLink } from "vue-router";
import router from "../../router";
import { store } from "../../store/store";
import toastr from "toastr";

import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import ItemCourse from "../Courses/ItemCourse.vue";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
} else if (!store.user.isAdmin) {
  router.push("/");
  toastr.error("Vous n'êtes pas autorisé à accéder au backoffice ", "", {
    timeOut: 3000,
  });
}

const items = ref({});

watchEffect(() => {
  items.value = store.comments.list;
});
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="container-commlist">
      <h2>Liste des commentaires:</h2>
      <div class="container-grid">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Cours</th>
              <!-- Titre du cours-->
              <th>Auteur</th>
              <!--Prénom + Nom-->
              <th>Commentaire</th>
              <!-- Content du commentaire slice à un nombre N-->
              <th>Note</th>
              <!--Note du commentaire: Etoiles visuels si possible sinon Int-->
              <th>Date</th>
              <!-- Created at en DD/MM/AAAA-->
              <th>Validé ?</th>
              <!-- Commentaire validé ?-->
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in items">
              <td v-if="c.id > 0">{{ c.id }}</td>
              <td v-else>-</td>

              <td v-if="c.content.length > 0">
                {{ c.content.slice(0, 75) }}...
              </td>
              <td v-else>-</td>

              <td v-if="c.firstname.length > 0 || c.lastname.length > 0">
                {{ c.firstname + " " + c.lastname }}
              </td>
              <td
                v-else-if="c.firstname.length === 0 && c.lastname.length === 0"
              >
                -
              </td>

              <RouterLink :to="`/detail/${c.course_id}`">
                <td>Details</td>
              </RouterLink>

              <td v-if="c.star > 0">{{ c.star }}/5</td>
              <td v-else>-</td>

              <td>{{ c.created_at }}</td>

              <td class="verifed" v-if="c.valid == 1">
                <va-icon name="verified" />
              </td>
              <td class="refused" v-else-if="c.valid == 2">
                <va-icon name="cancel" />
              </td>
              <td class="waiting" v-else><va-icon name="hourglass_empty" /></td>

              <td>
                <button class="bttn bttn-wng">
                  <va-icon name="last_page" />
                </button>
                <!-- Accéder au cours pour voir le commentaire -->
                <button class="bttn bttn-dng-out">
                  <va-icon name="block" />
                </button>
                <!--Bannir-->
                <button class="bttn bttn-dng"><va-icon name="delete" /></button>
                <!-- Supprimer le commentaire -->
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
        background-color: rgb(63, 133, 118);
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

      tbody tr:nth-child(even) {
        background-color: #f5f5f5;
      }
    }
  }
}
</style>
