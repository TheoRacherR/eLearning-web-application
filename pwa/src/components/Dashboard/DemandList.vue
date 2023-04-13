<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import axios from "axios";
import { ref, watchEffect } from "vue";

import router from "../../router";
import { store, listUsers } from "../../store/store";
import toastr from "toastr";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
} else if (!store.user.isAdmin) {
  router.push("/");
  toastr.error("Vous n'êtes pas autorisé à accéder au backoffice ", "", {
    timeOut: 3000,
  });
}

const users = ref({});

watchEffect(() => {
  const values = Object.values(store.users.list);
  const concerned = values.filter(
    (item) => item.isTeacher && item.teacherStatus === "WAITING"
  );
  const object = {};
  concerned.map((item) => (object[item.id] = item));

  users.value = object;
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

const updateStatus = (formerId, status, userId) => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/formers/" + formerId,
      { status },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      console.log("debug", userId);
      listUsers.value[userId].teacherStatus = status;
      toastr.success("Demande validée", "", { timeOut: 3000 });
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
      <h2>Liste des demandes pour passer en professeur:</h2>
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
                <!--Aller sur la page du user-->
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/user/${user.id}`"
                    ><va-icon name="last_page"
                  /></RouterLink>
                </button>

                <!--Refuser la demande-->
                <button
                  class="bttn bttn-succ"
                  @click="updateStatus(user.teacherId, 'VALIDATED', user.id)"
                >
                  <va-icon name="done" />
                </button>

                <!--Accepter la demande-->
                <button
                  class="bttn bttn-dng"
                  @click="updateStatus(user.teacherId, 'REFUSED', user.id)"
                >
                  <va-icon name="close" />
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
