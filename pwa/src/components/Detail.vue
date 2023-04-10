<script setup>
import { onMounted, watchEffect, watch, computed, ref } from "vue";
import { store, setBuyCourse } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";
import axios from "axios";
import { initData } from "../utils/initData";

const { user } = store;

const courseId = router.currentRoute.value.params.id;
const courses = ref({});
const rating = ref(2.5);
const comment = ref("");
const commenting = ref(false);

const items = ref({});
const validComments = ref({});


watchEffect(() => {
  items.value = store.comments.list;

  for (const item in items.value) {
    if (items.value[item].valid === 1 && items.value[item].id === parseInt(courseId)) {
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
  initData();
  closeCommenting();
  })
  .catch((err) => {
    console.log("dbeug", err);
  });
};

watch(rating, () => {
  console.log("debug", rating.value);
});
</script>

<template>
  <div class="wrapper">
    <h1>{{ course?.title }}</h1>

    <div class="description">{{ course?.description }}</div>

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
      <div class="container-comments">
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

          <div class="main-com">{{ com.content.slice(0, 100) }} {{ com.content.length > 103 ? '...' : '' }}</div>
        </div>
      </div>
    </div>
    <div v-if="store.user.isConnected && !course?.possessed">
      <button v-if="!Object.keys(store.cart.list).includes(courseId.toString())" class="bttn bttn-succ" @click="handleCart">
        <va-icon name="add_shopping_cart" /> Ajouter au panier
      </button>
      <div class="alreadyInCartDetailP" v-else-if="Object.keys(store.cart.list).includes(courseId.toString())"><p><va-icon name="done" />Ce cours se trouve dans le panier</p></div>
    </div>

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

div.alreadyInCartDetailP > p{ 
  background-color: rgb(94, 138, 29);
  color: var(--color-text-dark);
  margin: 0 auto;
  padding: 1rem;
  max-width: 20rem;
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
