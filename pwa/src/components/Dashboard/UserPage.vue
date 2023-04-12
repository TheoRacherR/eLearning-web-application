<script setup>
import axios from "axios";
import { ref, watch, watchEffect, onMounted } from "vue";
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import router from "../../router";
import { listUsers, listComments, store } from "../../store/store";
import toastr from "toastr";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}
// else if(!store.user.isAdmin){
//   router.push("/")
//   toastr.error("Vous n'êtes pas autorisé à accéder au backoffice ", "", { timeOut: 3000 });
// }

const user = ref({});
const userId = router.currentRoute.value.params.id;

onMounted(async () => {
  if (store.user.token) {
    const { data: userRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .catch((err) => {
        console.log("debug", err);
      });

    user.value = {
      firstname: userRaw.firstname,
      lastname: userRaw.lastname,
      mail: userRaw.mail,
    };
  }
});

watch(
  () => store.user.token,
  async () => {
    if (store.user.token) {
      const { data: userRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .catch((err) => {
          console.log("debug", err);
        });

      user.value = {
        firstname: userRaw.firstname,
        lastname: userRaw.lastname,
        mail: userRaw.mail,
      };
    }
  }
);

const courses = ref({});
watchEffect(() => {
  const values = Object.values(store.courses.list);
  const possessed = values.filter((item) => !item.possessed);
  const object = {};
  possessed.map((item) => (object[item.id] = item));

  courses.value = object;
});

const comments = ref({});
watchEffect(() => {
  const values = Object.values(store.comments.list);
  const possessed = values.filter(
    (item) => parseInt(item.user_id) === parseInt(userId)
  );
  const object = {};
  possessed.map((item) => (object[item.id] = item));

  comments.value = possessed;
});

const handleSubmit = async () => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/users/" + userId,
      { ...user.value },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      listUsers.value[userId] = {
        ...listUsers.value[userId],
        ...user.value,
      };
      toastr.success("Données mises à jour", "", { timeOut: 3000 });
      router.push("/db/user-list");
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const handleBanComments = (commentId) => {
  const value = listComments.value[commentId].valid === 2 ? 1 : 2;
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/comments/" + commentId,
      { valid: value },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      listComments.value[commentId].valid = value;

      toastr.success("Commentaire banni", "", { timeOut: 3000 });
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const handleDeleteComments = (commentId) => {
  axios
    .delete(import.meta.env.VITE_API_URL + "/comments/" + commentId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      delete listComments.value[commentId];
      toastr.success("Commentaire supprimé", "", { timeOut: 3000 });
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
      <h2>Modification des informations de l'utilisateur n°{{ userId }}</h2>
      <div class="container-input">
        <div class="firstline">
          <div class="input-item">
            <label :for="user.firstname">Prénom</label>
            <input class="innput" v-model="user.firstname" />
          </div>

          <div class="input-item">
            <label :for="user.lastname">Nom</label>
            <input class="innput" v-model="user.lastname" />
          </div>
        </div>

        <div class="input-item">
          <label :for="user.mail">Adresse mail</label>

          <input class="innput" v-model="user.mail" />
        </div>

        <button class="bttn bttn-prim" @click="handleSubmit">Valider</button>
      </div>
      <div class="wrapperLists">
        <div class="container-commlist">
          <h2 v-if="courses && Object.values(courses).length > 0">
            Liste des cours:
          </h2>
          <div
            class="container-grid"
            v-if="courses && Object.values(courses).length > 0"
          >
            <table>
              <thead>
                <tr>
                  <th>Titre</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="course in courses">
                  <!--<td v-if="course.user_id.firstname.length > 0 || course.user_id.lastname.length > 0">{{ course.user_id.firstname + ' ' + course.user_id.lastname }}</td>
                            <td v-else-if="course.user_id.firstname.length === 0 && course.user_id.lastname.length === 0">-</td>-->

                  <td v-if="course.title.length > 0">{{ course.title }}</td>
                  <td v-else>-</td>

                  <td class="refused" v-else><va-icon name="close" /></td>
                  <td>
                    <button class="bttn bttn-wng">
                      <RouterLink :to="`/db/course/${course.id}`"
                        ><va-icon name="last_page"
                      /></RouterLink>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="container-commlist">
          <h2 v-if="comments && Object.values(comments).length > 0">
            Liste des commentaires:
          </h2>
          <div
            class="container-grid"
            v-if="comments && Object.values(comments).length > 0"
          >
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Commentaire</th>
                  <!-- Titre du cours-->
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
                <tr v-for="comment in comments">
                  <td v-if="comment.id > 0">{{ comment.id }}</td>
                  <td v-else>-</td>

                  <td v-if="comment.content.length > 0">
                    {{ comment.content.slice(0, 300) }}
                  </td>
                  <td v-else>-</td>

                  <td v-if="comment.star > 0">{{ comment.star }}/5</td>
                  <td v-else>-</td>

                  <td>{{ comment.created_at }}</td>

                  <td class="verifed" v-if="comment.valid == 1">
                    <va-icon name="verified" />
                  </td>
                  <td class="refused" v-else-if="comment.valid == 2">
                    <va-icon name="cancel" />
                  </td>
                  <td class="waiting" v-else>
                    <va-icon name="hourglass_empty" />
                  </td>

                  <td>
                    <!-- <button class="bttn bttn-wng">
                  <va-icon name="last_page" />
                </button> -->
                    <!-- Accéder au cours pour voir le commentaire -->
                    <button
                      class="bttn bttn-dng-out"
                      @click="handleBanComments(comment.id)"
                    >
                      <va-icon name="block" />
                    </button>
                    <!--Bannir-->
                    <button
                      class="bttn bttn-dng"
                      @click="handleDeleteComments(comment.id)"
                    >
                      <va-icon name="delete" />
                    </button>
                    <!-- Supprimer le commentaire -->
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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

    div.container-input {
      background-color: rgb(245, 245, 245);
      padding: 1rem;
      width: 50%;
      margin: 2rem auto 0 auto;
      div.firstline {
        display: flex;
        justify-content: center;

        div {
          width: 50%;
        }
      }

      div.input-item {
        margin-bottom: 2rem;
      }

      button {
        margin: 2rem 1rem 0 auto;
        // padding-left: 20rem;
        // padding-right: 20rem;
        width: 100%;
      }
    }
  }
}

div.container-commlist {
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

.wrapperLists {
  padding-top: 2vh;

  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1vw;
}
</style>
