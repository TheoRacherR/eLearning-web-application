<script setup>
import { ref, watchEffect } from "vue";
import { RouterLink } from "vue-router";
import router from "../../router";
import { store } from "../../store/store";
import toastr from "toastr";
import axios from "axios";

import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";

const items = ref({});
const invalidItems = ref({});

watchEffect(() => {
  items.value = store.comments.list;

  for (const item in items.value) {
    if (items.value[item].valid == 0) {
      invalidItems.value = {
        ...invalidItems.value,
        [item]: { ...items.value[item] },
      };
    }
  }
});

const handleSubmit = async (courseId, value) => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + `/comments/${courseId}`,
      {
        valid: value,
      },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      if (value == 1) {
        toastr.success("Commentaire accepté", "", { timeOut: 3000 });
      } else if (value == 2) {
        toastr.warning("Commentaire refusé", "", { timeOut: 3000 });
      }
      delete invalidItems.value[courseId];
    });
};
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
            <tr v-for="c in invalidItems">
              <td v-if="c.id > 0">{{ c.id }}</td>
              <td v-else>-</td>

              <td v-if="c.content.length > 0">
                {{ c.content.slice(0, 300) }}
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

              <td class="waiting"><va-icon name="hourglass_empty" /></td>

              <td>
                <button class="bttn bttn-succ" @click="handleSubmit(c.id, 1)">
                  <va-icon name="done" />
                </button>
                <!--Valider-->
                <button class="bttn bttn-dng" @click="handleSubmit(c.id, 2)">
                  <va-icon name="close" />
                </button>
                <!--Rejeter-->
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

      td.waiting {
        color: rgb(168, 43, 43);
      }

      tbody tr:nth-child(even) {
        background-color: #f5f5f5;
      }
    }
  }
}
</style>
