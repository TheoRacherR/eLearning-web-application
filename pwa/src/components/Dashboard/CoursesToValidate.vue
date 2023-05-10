<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import { ref, watchEffect } from "vue";
import router from "../../router";
import { store } from "../../store/store";
import toastr from "toastr";
import axios from "axios";

const items = ref({});
const invalidItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;
  for (const item in items.value) {
    if (items.value[item].valid == 0) {
      invalidItems.value = {
        ...invalidItems.value,
        [item]: { ...items.value[item] },
      };
    }
  }
});

const handleSubmit = (courseId, value) => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + `/courses/${courseId}`,
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
      store.courses.list[courseId].valid = value;
      if (value == 1) {
        toastr.success("Cours accepté", "", { timeOut: 3000 });
      } else if (value == 2) {
        toastr.warning("Cours refusé", "", { timeOut: 3000 });
      }
      delete invalidItems.value[courseId];
      // delete store.courses.list[courseId];
    });
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="container-commlist">
      <h2>Liste des cours en attente de validation:</h2>
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
            <tr v-for="c in invalidItems">
              <td v-if="c.id > 0">{{ c.id }}</td>
              <td v-else>-</td>

              <!--<td v-if="c.user_id.firstname.length > 0 || c.user_id.lastname.length > 0">{{ c.user_id.firstname + ' ' + c.user_id.lastname }}</td>
                            <td v-else-if="c.user_id.firstname.length === 0 && c.user_id.lastname.length === 0">-</td>-->

              <td>{{ c.user_id_id }}</td>

              <td v-if="c.title.length > 0">{{ c.title }}</td>
              <td v-else>-</td>

              <td v-if="c.description.length > 0">
                {{ c.description.slice(0, 100) }}
              </td>
              <td v-else>-</td>

              <td>{{ c.created_at }}</td>

              <td class="verifed" v-if="c.valid === true">
                <va-icon name="verified" />
              </td>
              <td class="waiting" v-else><va-icon name="hourglass_empty" /></td>

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
