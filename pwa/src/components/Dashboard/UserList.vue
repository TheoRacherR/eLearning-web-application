<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import axios from "axios";
import { ref, watchEffect } from "vue";

import { RouterLink } from "vue-router";
import router from "../../router";
import { store, listUsers } from "../../store/store";
import toastr from "toastr";

const users = ref({});

watchEffect(() => {
  users.value = store.users.list;
});

const getRole = (roles) => {
  let role = "";
  console.log(roles)

  if (roles.includes("ROLE_ADMIN")) {
    role = "Admin"
  }
  else if (roles.includes("ROLE_FORMER")) {
    role = "Professeur"
  }
  else {
    role = "Utilisateur"
  }
   
  return role;
};

const handleBan = (userId) => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/users/" + userId,
      { ban: !listUsers.value[userId].ban },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      listUsers.value[userId].ban = !listUsers.value[userId].ban;

      toastr.success("Utilisateur banni", "", { timeOut: 3000 });
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const handleDelete = (userId) => {
  axios
    .delete(import.meta.env.VITE_API_URL + "/users/" + userId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      delete listUsers.value[userId];
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
              <th>Compte Validé</th>
              <th>Banni</th>
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

              <td v-if="user.ban === true">Oui</td>
              <td v-else>Non</td>

              <td>
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/user/${user.id}`"
                    ><va-icon name="last_page"
                  /></RouterLink>
                </button>
                <!--Aller sur la page-->
                <button
                  class="bttn bttn-succ-out banned"
                  v-if="user.ban === true"
                >
                  <va-icon name="refresh" @click="handleBan(user.id)" />
                </button>
                <button class="bttn bttn-dng-out not-banned" v-else>
                  <va-icon name="block" @click="handleBan(user.id)" />
                </button>
                <!--Bannir-->
                <button class="bttn bttn-dng">
                  <va-icon name="delete" @click="handleDelete(user.id)" />
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
        background-color: rgb(133, 85, 63);
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

      td button.banned {
        color: #52b425;
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
