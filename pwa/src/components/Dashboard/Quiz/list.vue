<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import LeftDashboard from "./../LeftDashboard/LeftDashboard.vue";
import router from "./../../../router";
import toastr from "toastr";
import { listCourses, store } from "../../../store/store";

const course = ref({});
const questions = ref([]);

const courseId = router.currentRoute.value.params.id;

const init = async () => {
  const { data: courseRaw } = await axios
    .get(import.meta.env.VITE_API_URL + "/courses/" + courseId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .catch((err) => {
      console.log("debug", err);
    });

  const sequence = JSON.parse(courseRaw.sequence);

  course.value = {
    sequence,
  };

  const promises = sequence.map((id) => {
    return axios.get(import.meta.env.VITE_API_URL + "/questions/" + id, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    });
  });

  Promise.all(promises)
    .then((responses) => {
      responses.forEach(({ data }) => {
        questions.value.push({
          id: data.id,
          settings: JSON.parse(data.settings),
        });
      });
    })
    .catch((error) => {
      console.error(error);
    });
};

onMounted(async () => {
  if (store.user.token) {
    init();
  }
});

watch(() => store.user.token, init);

const handleDelete = (questionId) => {
  axios
    .delete(import.meta.env.VITE_API_URL + "/questions/" + questionId, {
      headers: {
        Authorization: `Bearer ${store.user.token}`,
      },
    })
    .then(() => {
      axios
        .patch(
          import.meta.env.VITE_API_URL + "/courses/" + courseId,
          {
            sequence: JSON.stringify(
              course.value.sequence.filter((id) => id !== questionId)
            ),
          },
          {
            headers: {
              Authorization: `Bearer ${store.user.token}`,
            },
          }
        )
        .then(() => {
          questions.value = questions.value.filter(
            (item) => item.id !== questionId
          );
          toastr.success("Question supprimée", "", { timeOut: 3000 });
        });
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
      <h2>Liste des question pour le cours n°{{ courseId }}</h2>
      <div class="container-grid">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Content</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="question in questions">
              <td v-if="question.id > 0">{{ question.id }}</td>
              <td v-else>-</td>

              <td>{{ question.settings.content }}</td>

              <td>
                <button class="bttn bttn-wng">
                  <RouterLink :to="`/db/quiz/list/${courseId}/${question.id}`"
                    ><va-icon name="last_page"
                  /></RouterLink>
                </button>
                <!--Aller sur le cours-->
                <!--<button class="bttn bttn-dng-out"><va-icon name="close"/></button>-->
                <!--Rejeter-->
                <button
                  class="bttn bttn-dng"
                  @click="handleDelete(question.id)"
                >
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

  .wrapperAnswers {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2vw;
  }
}
</style>
