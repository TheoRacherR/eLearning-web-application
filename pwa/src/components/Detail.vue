<script setup>
import { onMounted, watchEffect, watch, computed, ref } from "vue";
import { store, setBuyCourse } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import axios from "axios";
import { initData } from "../utils/initData";
import toastr from "toastr";

import Comment from "./Courses/Comment.vue";

const { user } = store;

const courseId = router.currentRoute.value.params.id;
const courses = ref({});
const rating = ref(2.5);
const comment = ref("");
const commenting = ref(false);

const loadingSubmitComment = ref(false);

const items = ref({});
const validComments = ref({});

watchEffect(() => {
  items.value = store.comments.list;

  for (const item in items.value) {
    if (
      items.value[item].valid === 1 &&
      parseInt(items.value[item].course_id) === parseInt(courseId)
    ) {
      validComments.value = {
        ...validComments.value,
        [item]: { ...items.value[item] },
      };
    }
  }
});

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});

watchEffect(() => {
  courses.value = store.courses.list;
});

onMounted(() => {
  checkConnection(false, false, false, false, "Detail");
  store.selectCourse(courseId);
});

const handleCart = () => {
  let arr = [];
  if (localStorage.getItem("CART") !== null) {
    if (!localStorage.getItem("CART").includes(courseId)) {
      arr = JSON.parse(localStorage.getItem("CART"));
      arr.push(courseId);
      localStorage.setItem("CART", JSON.stringify(arr)); //ajout dans le localStorage
      store.setAddCart(courseId); //ajout dans le store
    } else {
      console.log("Item déjà dans le panier");
    }
  } else {
    arr.push(courseId);
    localStorage.setItem("CART", JSON.stringify(arr));
  }
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
  loadingSubmitComment.value = true;
  if (comment.value.length > 300) {
    toastr.error("Trop long ", "", { timeOut: 3000 });
  } else {
    const body = {
      course: "courses/" + courseId,
      userId: "users/" + user.id,
      star: Math.round(rating.value),
      content: comment.value,
    };

    axios
      .post(import.meta.env.VITE_API_URL + "/comments", body, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then(() => {
        toastr.info("Votre commentaire est en cours de validation", "", {
          timeout: 3000,
        });
        initData();
        closeCommenting();
        loadingSubmitComment.value = false;
      })
      .catch((err) => {
        console.log("dbeug", err);
      });
  }
};

watch(rating, () => {
  console.log("debug", rating.value);
});
</script>

<template>
  <div class="wrapper">
    <div class="presentation">
      <div class="left">
        <h1>{{ course?.title }}</h1>
        <h5>{{ course?.description }}</h5>
        <router-link
          v-if="course?.possessed && store.user.isConnected"
          :to="`/course/${courseId}`"
          class="bttn bttn-drk"
          data-test="continueCourse"
          >Reprendre ce cours</router-link
        >
        <!-- Ajouter au panier -->
        <div v-if="store.user.isConnected && !course?.possessed">
          <button
            v-if="!Object.keys(store.cart.list).includes(courseId.toString())"
            class="bttn bttn-succ"
            @click="handleCart"
          >
            <va-icon name="add_shopping_cart" /> Ajouter au panier
          </button>
          <div
            class="alreadyInCartDetailP"
            v-else-if="
              Object.keys(store.cart.list).includes(courseId.toString())
            "
          >
            <p><va-icon name="done" />Ce cours se trouve dans le panier</p>
          </div>
        </div>

        <button
          class="bttn bttn-succ"
          type="submit"
          v-if="!store.user.isConnected"
          data-bs-toggle="modal"
          data-bs-target="#ModalConnection"
        >
          Connexion
        </button>
      </div>

      <div class="middle"></div>

      <div class="right">
        <img
          v-if="course?.image"
          class="img-fluid"
          :src="course?.image"
          alt="Image du cours"
        />
      </div>
    </div>

    <div
      class="wrapperComment"
      v-if="store.user.isConnected && commenting && course?.possessed"
    >
      <vue3-star-ratings v-model="rating" />
      <textarea
        rows="5"
        v-model="comment"
        placeholder="Rentrez votre commentaire ici"
      ></textarea>

      <div class="cont-form-comment">
        <button
          v-if="comment.length > 0"
          class="bttn bttn-prim"
          @click="submitComment"
        >
          <div
            class="spinner-border spinner-border-sm"
            role="status"
            v-if="loadingSubmitComment"
          >
            <span class="visually-hidden">Loading...</span>
          </div>
          Valider le commentaire
        </button>
        <button v-else class="bttn bttn-prim">Valider le commentaire</button>
        <button class="bttn bttn-prim-out" @click="closeCommenting">
          Annuler
        </button>
      </div>
    </div>

    <div
      class="leave-comment"
      v-else-if="store.user.isConnected && !commenting && course?.possessed"
    >
      <button class="bttn bttn-prim" @click="handleComment">
        Laisser un commentaire
      </button>
    </div>

    <!-- Commentaire -->
    <div class="wrapperCommentsList">
      <h4>Les commentaires:</h4>
      <Comment :items="validComments" />
    </div>

    <div
      class="report-container"
      v-if="store.user.isConnected && course?.possessed"
    >
      <RouterLink :to="`/report/${courseId}`">
        <button class="bttn bttn-dng">
          <va-icon name="warning"></va-icon>
          Signaler ce cours
        </button>
      </RouterLink>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.wrapper {
  padding: 0 0 3vh 0;
  text-align: center;

  div.presentation {
    display: flex;
    justify-content: space-between;
    padding: 6vh 30vw 5rem 30vw;
    margin-bottom: 5rem;
    background-color: #e2e2e2;

    div.left {
      flex: 3;
      text-align: left;

      h1 {
        font-weight: bold;
      }
      a,
      button {
        margin-top: 2rem;
      }
    }

    div.middle {
      flex: 1;
    }

    div.right {
      flex: 3;
      img {
        width: 100%;
      }
    }
  }

  div.description {
    margin-bottom: 5vh;
  }

  div.alreadyInCartDetailP > p {
    // background-color: rgb(94, 138, 29);
    color: #2ecc71;
    margin: 0 auto;
    padding: 1rem 1rem 1rem 0;
    max-width: 20rem;
  }

  div.report-container {
    margin: 15vh auto 0 auto;
    text-align: right;
    width: 50%;
  }

  div.wrapperCommentsList {
    margin: 10vh auto 3vh auto;
    width: 40vw;
    text-align: left;

    div.container-comments {
      display: flex;
      flex-wrap: wrap;
      /* text-align: left; */
      margin-top: 3rem;

      div.item-comment {
        margin: 0 1.6rem 1.6rem 0;
        width: calc(50% - 1.6rem);
        padding: 0 0 1.8rem 0;
        border-top: 1px solid grey;

        div.top-com {
          margin-bottom: 1rem;
          font-weight: bold;
          display: flex;
          justify-content: left;
          div.top-com > img {
            border-radius: 50%;
            div.text-topc {
              margin-top: auto;
              margin-bottom: auto;
              /* margin-left: 0.5rem; */
              margin-right: 0;
              div.stars-com {
                display: flex;
              }
            }
          }
        }
      }
    }
  }
  div.wrapperComment {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 40vw;
    margin: 0 auto;

    textarea {
      width: 100%;
      padding: 1rem;
    }

    div.cont-form-comment {
      margin-left: auto;
      button {
        margin-top: 1rem;
      }
      button:nth-child(1) {
        width: 17rem;
        margin-right: 1rem;
      }
      button:nth-child(2) {
        width: 8rem;
      }
    }
  }

  div.leave-comment {
    width: 40vw;
    margin: 0 auto;

    text-align: left;
  }
}
</style>
