<script setup>
import { onMounted, watchEffect, watch, computed, ref } from "vue";
import { store, setBuyCourse } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import axios from "axios";

const { user } = store;

const courseId = router.currentRoute.value.params.id;
const courses = ref({});
const rating = ref(2.5);
const comment = ref("");
const commenting = ref(false);

const commentsList = ref([
  { Note: 4.5, Commentaire: "Formation complète avec tout le nécessaire pour bien commencer le Trading.. explication simple et facile a comprendre...merci beaucoup", Prénom: "Elvis", Nom: "K." },
  { Note: 5, Commentaire: "Grand pédagogie, simplicité dans les explications, petits tips qui permettent de comprendre tellement en peu de mots. Comme je le dis souvent 'Less is more' , et cette formation est exactement ce que je recherchais. Merci", Prénom: "Marie", Nom: "C." },
  { Note: 1, Commentaire: "Peu d'exemple et une qualité de vidéo très basse, je ne recommande pas vraiment ce cours", Prénom: "Jean", Nom: "D." },
]);

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});


watchEffect(() => {
  courses.value = store.courses.list;
});

onMounted(() => {
  checkConnection(false, false, "Detail");
  store.selectCourse(courseId);
});

const handleBuy = () => {
  axios
    .post(
      import.meta.env.VITE_API_URL + "/user_courses",
      {
        account: "users/" + store.user.id,
        course: "courses/" + courseId,
      },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      setBuyCourse(courseId);
    })
    .catch((err) => {
      console.log(err);
    });
};

const handleComment = () => {
  commenting.value = !commenting.value;
};

const closeCommenting = () => {
  rating.value = 2.5;
  comment.value = "";
  commenting.value = false;
};

const submitComment = () => {
  const body = {
    course: "courses/" + courseId,
    userId: "users/" + user.id,
    star: Math.round(rating.value),
    content: comment.value,
  };

  // axios
  //   .post(import.meta.env.VITE_API_URL + "/comments", body, {
  //     headers: {
  //       Authorization: `Bearer ${store.user.token}`,
  //     },
  //   })
  //   .then(() => {
  commentsList.value.push({
    Note: rating.value,
    Commentaire: comment.value,
    Prénom: user.firstname,
    Nom: user.lastname,
  });
  closeCommenting();
  // })
  // .catch((err) => {
  //   console.log("dbeug", err);
  // });
};



watch(rating, () => {
  console.log("debug", rating.value);
});
</script>

<template>
  <div class="wrapper">
    <h1>{{ course?.title }}</h1>

    <div class="description">Description: {{ course?.description }}</div>

    <button
      class="bttn bttn-succ"
      type="submit"
      v-if="!store.user.isConnected"
      data-bs-toggle="modal"
      data-bs-target="#ModalConnection"
    >
      Connexion
    </button>

    <router-link
      v-if="course?.possessed && store.user.isConnected"
      :to="`/course/${courseId}`"
      class="bttn bttn-prim-out"
      >Reprendre ce cours</router-link
    >
    <div class="wrapperCommentsList">
      <h4>Les commentaires:</h4>

      <!--<va-data-table :items="commentsList" />-->
      <div class="container-comments">
        <div v-for="com in commentsList" class="item-comment">

          <div class="top-com">
            <!--<img src="https://via.placeholder.com/40x40" alt="">-->
            <div class="text-topc">
              <div>{{ com.Prénom }} {{ com.Nom }}</div>
              <div class="stars-com">
                <div style="margin: auto 0">
                  {{ com.Note }} 
                </div>
                <va-icon name="star"/>
              </div>
            </div>
            
          </div>

          <div class="main-com">{{ com.Commentaire.slice(0, 100) }}...</div>

        </div>
      </div>
      
    </div>
    <button
      v-if="!course?.possessed && store.user.isConnected"
      class="bttn bttn-succ"
      @click="handleBuy"
    >
    <va-icon name="add_shopping_cart"/>
      Acheter ce cours
    </button>
    <div class="wrapperComment" v-if="commenting">
      <vue3-star-ratings v-model="rating" />
      <textarea
        v-model="comment"
        placeholder="Rentrez votre commentaire ici"
      ></textarea>
      <button class="bttn bttn-prim" @click="submitComment">
        Valider le commentaire
      </button>
    </div>
    <button
      class="bttn bttn-prim"
      v-else-if="course?.possessed && store.user.isConnected"
      @click="handleComment"
    >
      Laisser un commentaire
    </button>
    <button
      class="bttn bttn-prim-out"
      v-if="course?.possessed && store.user.isConnected && commenting"
      @click="closeCommenting"
    >
      Annuler
    </button>
  </div>
</template>

<style scoped>
.wrapper {
  padding: 3vh 3vw;
  text-align: center;
}

.description {
  margin-bottom: 5vh;
}

.wrapperCommentsList {
  margin: 15vh auto 3vh auto;
  width: 50%;
  text-align: left;

}

div.container-comments {
  display: flex;
  flex-wrap: wrap;
  /* text-align: left; */
  margin-top: 3rem;
}

div.item-comment {
  /* background-color: rgb(250, 250, 250); */
  /* background-color: red; */
  margin: 0 1.6rem 1.6rem 0;
  width: calc(50% - 1.6rem);
  padding: 0 0 1.8rem 0;
  border-top: 1px solid grey;
}

div.top-com {
  margin-bottom: 1rem;
  font-weight: bold;
  display: flex;
  justify-content: left;
}

div.top-com > img {
  border-radius: 50%;
}

div.text-topc {
  margin-top: auto;
  margin-bottom: auto;
  /* margin-left: 0.5rem; */
  margin-right: 0;
}

div.stars-com {
  display: flex;
}

.wrapperComment {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
