<script setup>
import { onMounted, watchEffect, watch, computed, ref } from "vue";
import { store, setBuyCourse } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import axios from "axios";
import { initData } from "../utils/initData";
import toastr from "toastr";

const { user } = store;

const courseId = router.currentRoute.value.params.id;
const courses = ref({});
const rating = ref(2.5);
const comment = ref("");
const commenting = ref(false);

const items = ref({});
const validComments = ref({});

const loadingSubmitComment = ref(false);

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
  checkConnection(false, false, "Detail");
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
        toastr.info("Votre commentaire est en cours de validation", "", { timeout: 3000 });
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
    <h1>{{ course?.title }}</h1>

    <div class="description">{{ course?.description }}</div>

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
        v-else-if="Object.keys(store.cart.list).includes(courseId.toString())"
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

    <router-link
      v-if="course?.possessed && store.user.isConnected"
      :to="`/course/${courseId}`"
      class="bttn bttn-prim-out"
      data-test="continueCourse"
      >Reprendre ce cours</router-link
    >
    <div class="wrapperCommentsList">
      <h4>Les commentaires:</h4>
      <div class="container-comments">
        <div v-if="Object.values(validComments).length === 0">
          Il n'y a pas de commentaires
        </div>
        <div v-for="com in validComments" class="item-comment">
          <div class="top-com">
            <!--<img src="https://via.placeholder.com/40x40" alt="">-->
            <div class="text-topc">
              <div>{{ com.firstname }} {{ com.lastname }}</div>
              <div class="stars-com">
                <div style="margin: auto 0">
                  {{ com.star }}
                </div>
                <va-icon name="star" />
              </div>
            </div>
          </div>

          <div class="main-com">
            {{ com.content.slice(0, 100) }}
            {{ com.content.length > 103 ? "..." : "" }}
          </div>
        </div>
      </div>
    </div>

    <!-- Commentaire -->
    <div class="wrapperComment" v-if="
      store.user.isConnected && commenting //&& course?.possessed
    ">
      <vue3-star-ratings v-model="rating" />
      <textarea rows="5" v-model="comment" placeholder="Rentrez votre commentaire ici"></textarea>
      
      <div class="cont-form-comment">
        <button v-if="comment.length > 0" class="bttn bttn-prim" @click="submitComment">
          <div
            class="spinner-border spinner-border-sm"
            role="status"
            v-if="loadingSubmitComment"
          >
            <span class="visually-hidden">Loading...</span>
          </div>
          Valider le commentaire
        </button>
        <button v-else class="bttn bttn-prim">
          Valider le commentaire
        </button>
        <button class="bttn bttn-prim-out" @click="closeCommenting">
          Annuler
        </button>
      </div>

    </div>

    <div class="leave-comment" v-else-if="
      store.user.isConnected && !commenting //&& course?.possessed
    ">
      <button class="bttn bttn-prim" @click="handleComment">
        Laisser un commentaire
      </button>
    </div>


  </div>
</template>

<style lang="scss" scoped>
div.wrapper {
  padding: 6vh 3vw 3vh 3vw;
  text-align: center;

  div.description {
    margin-bottom: 5vh;
  }

  div.alreadyInCartDetailP > p {
    // background-color: rgb(94, 138, 29);
    color: #2ecc71;
    margin: 0 auto;
    padding: 1rem;
    max-width: 20rem;
  }

  div.wrapperCommentsList {
    margin: 15vh auto 3vh auto;
    width: 50%;
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

    textarea {
      width: 26rem;
      padding: 1rem;
    }

    div.cont-form-comment {
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

  div.leave-comment{
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
</style>
