<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import axios from "axios";
import { ref, watchEffect } from "vue";

import { RouterLink } from "vue-router";
import router from "../../router";
import { store } from "../../store/store";
import toastr from "toastr";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}
// else if (!store.user.isAdmin) {
//   router.push("/");
//   toastr.error("Vous n'êtes pas autorisé à accéder au backoffice ", "", {
//     timeOut: 3000,
// });
// }

const users = ref({});

watchEffect(() => {
  users.value = store.users.list;
});

const getRole = (roles) => {
  let role = "";

  roles.map((item) => {
    switch (item) {
      case "ROLE_USER":
        role += "Utilisateur ";
        break;
      case "ROLE_ADMIN":
        role += "Administrateur ";
        break;

      default:
        break;
    }
  });

  return role;
};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />
    <div class="container-commlist">
      <h2>Liste des utilisateurs:</h2>
      <div class="container-grid">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Prénom</th>
              <th>Nom</th>
              <th>Mail</th>
              <th>Role</th>
              <th>Compte Validé ?</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users">
              <td>{{ user.id }}</td>

              <td v-if="user.firstname?.length > 0">{{ user.firstname }}</td>
              <td v-else>-</td>

              <td v-if="user.lastname?.length > 0">{{ user.lastname }}</td>
              <td v-else>-</td>

              <td v-if="user.mail?.length > 0">{{ user.mail }}</td>
              <td v-else>-</td>

              <td v-if="user.roles?.length > 0">{{ getRole(user.roles) }}</td>
              <td v-else>-</td>

              <td class="verifed" v-if="user.valid === true">
                <va-icon name="verified" />
              </td>
              <td class="waiting" v-else><va-icon name="hourglass_empty" /></td>

              <td>
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/user/${user.id}`"
                    ><va-icon name="last_page"
                  /></RouterLink>
                </button>
                <!--Aller sur la page-->
                <button class="bttn bttn-dng-out">
                  <va-icon name="block" />
                </button>
                <!--Bannir-->
                <button class="bttn bttn-dng"><va-icon name="delete" /></button>
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
        background-color: rgb(133, 85, 63);
        color: #fff;
      }
      th,
      td {
        padding: 1rem;
      }

      td.verifed {
        color: #52b425;
      }

      td.waiting {
        color: rgb(168, 43, 43);
      }

      td button a {
        text-decoration: none;
        color: #fff;
      }
      tbody tr:nth-child(even) {
        background-color: #f5f5f5;
      }
    }
  }
}
</style>
