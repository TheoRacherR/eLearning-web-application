<script setup>
import { listCourses, store } from "../store/store";
import toastr from "toastr";
import { onMounted, ref, watchEffect } from "vue";
import axios from "axios";
import { checkConnection } from "../utils/checkConnection";

const listCoursesInCart = ref(Object.values(store.listCoursesInCart.list));
const totalPrice = ref(0);

const loadingButton = ref(false);

onMounted(() => {
  let stripeCheckoutId = localStorage.getItem("stripeCheckoutId");
  if (stripeCheckoutId) {
    console.log(stripeCheckoutId);
    axios
      .get("https://api.stripe.com/v1/checkout/sessions/" + stripeCheckoutId, {
        headers: {
          Authorization: `Bearer sk_test_51MRvy2I8On5YmTNNSW1T6vjY1Qf27P8l62eZgOEECJYbSKaRXlP6HoBHnHmHB7adcGZHNfbuCnTjROFEQOXjummg00kZ0bQcBv`,
        },
      })
      .then((res) => {
        if (res.data.status === "complete") {
          const cart = Object.values(store.listCoursesInCart.list);
          for (let i = 0; i < cart.length; i++) {
            axios
              .post(
                import.meta.env.VITE_API_URL + "/user_courses",
                {
                  account: "users/" + store.user.id,
                  course: "courses/" + cart[i].id,
                },
                {
                  headers: {
                    Authorization: `Bearer ${store.user.token}`,
                  },
                }
              )
              .then(() => {
                localStorage.setItem("CART", "[]");
                localStorage.setItem("stripeCheckoutId", "");
                store.deleteCart();
                toastr.success("Vos achats ont été validés !");

                const courseId = cart[i].id;
                listCourses.value[courseId].possessed = true;
              })
              .catch((err) => {});
          }
        }
      });
  }
});

watchEffect(() => {
  listCoursesInCart.value = Object.values(store.listCoursesInCart.list);
  totalPrice.value = Object.values(store.listCoursesInCart.list).reduce(
    (acc, { price }) => acc + price,
    0
  );
});

const deleteOnInCart = (courseId) => {
  let localStorageArray = localStorage.getItem("CART");
  localStorageArray = JSON.parse(localStorageArray).filter(
    (item) => item != courseId.toString()
  );
  localStorage.setItem("CART", JSON.stringify(localStorageArray));
  store.setCart();
  toastr.success("Cours supprimé du panier", "", { timeOut: 3000 });
};

const onSubmitCart = () => {
  loadingButton.value = true;
  let cart = Object.values(store.listCoursesInCart.list);
  let tempArray = [];
  for (let i = 0; i < cart.length; i++) {
    tempArray.push({ priceId: cart[i].stripePriceId });
  }
  axios
    .post(
      import.meta.env.VITE_API_URL + "/course/buy/",
      {
        products: tempArray,
      },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then((res) => {
      localStorage.setItem("stripeCheckoutId", res.data.id);
      window.location.href = res.data.url;
      loadingButton.value = false;
    });
};

onMounted(() => {
  checkConnection(false, true, false, false, "Summary");
});
</script>

<template>
  <div class="container-summary">
    <h1 class="title-summary">Panier</h1>
    <div class="main-summary">
      <div class="list-items">
        <div v-if="listCoursesInCart.length === 0">
          <h5>Vous n'avez rien dans votre panier...</h5>
        </div>
        <div v-else>
          <div v-for="c in listCoursesInCart" class="item-sum">
            <div class="container-item">
              <img :src="c.image" alt="image of the course" />
              <div class="right-box">
                <div class="text-rb">
                  <RouterLink :to="'/detail/' + c.id">
                    <div class="title-item">{{ c.title }}</div>
                    <div class="description-item">
                      {{ c.description.slice(0, 100)
                      }}{{ c.description.length > 103 ? "..." : "" }}
                    </div>
                  </RouterLink>
                </div>
                <button
                  class="bttn bttn-dng delete-item-cart"
                  @click="deleteOnInCart(c.id)"
                >
                  <va-icon name="delete" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="straps">
        <div class="list-purchase">
          <div v-if="listCoursesInCart.length === 0">
            <div class="item-purchase">
              <div class="left-name">...</div>
              <div class="right-price">0 €</div>
            </div>
          </div>
          <div v-else>
            <div v-for="c in listCoursesInCart" class="item-purchase">
              <div class="left-name">{{ c.title }}</div>
              <div class="right-price">{{ c.price }} €</div>
            </div>
          </div>

          <div class="fill-purchase"></div>

          <div class="total-price">
            <div class="left-name">Total</div>
            <!--<div class="right-price">{{ totalPrice }} €</div>-->
            <div class="right-price">{{ totalPrice }} €</div>
          </div>
        </div>

        <button
          class="bttn bttn-succ"
          @click="onSubmitCart"
          data-test="submitCartButton"
        >
          <div
            class="spinner-border spinner-border-sm"
            role="status"
            v-if="loadingButton"
          >
            <span class="visually-hidden">Loading...</span>
          </div>
          Terminer la commande
        </button>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.container-summary {
  text-align: center;
  padding-top: 4rem;
  width: 100%;

  div.main-summary {
    margin-top: 3rem;
    display: flex;

    div.list-items {
      // background-color: red;
      width: 60%;
      margin: 0 2rem 0 0;

      div.item-sum {
        // background-color: green;
        margin: 0 0 2rem 0;
        // padding: 1rem 0;
        div.container-item {
          background-color: rgb(233, 233, 233);
          display: flex;
          // margin-top: 3vh;

          > img {
            width: 20%;
            height: auto;
            margin: 0;
            display: block;
            padding: 0;
          }

          div.right-box {
            padding: 1rem 1.5rem;
            color: #000;
            width: 100%;
            text-align: left;
            display: flex;
            justify-content: space-between;

            a {
              text-decoration: none;
              color: #000;

              .title-item {
                font-size: 1rem;
                letter-spacing: 0px;
                line-height: 24px;
                font-weight: 700;
              }
              .description-item {
                font-size: 1rem;
                letter-spacing: 0px;
                line-height: 26px;
                font-weight: 400;
                display: block;
              }
            }
            .check {
              display: flex;
              justify-content: end;
              color: #fff;

              > div {
                padding: 0.2rem 1rem;
                // background-color: #49961d;
              }
            }

            button.delete-item-cart {
              // position: absolute;
              // position: right;
              margin: auto 0;
            }
          }
        }
      }
    }

    div.straps {
      // background-color: aqua;
      text-align: left;
      width: 40%;

      div.list-purchase {
        padding: 2rem;
        div.item-purchase {
          display: flex;
          justify-content: space-between;
        }

        div.fill-purchase {
          margin: 1rem 0;
          height: 1px;
          background-color: #000;
          width: 100%;
        }

        div.total-price {
          // margin-top: 2rem;
          font-weight: bold;
          display: flex;
          justify-content: space-between;
        }
      }
      > button {
        width: 100%;
      }
    }
  }
}

@media (min-width: 768px) {
  div.container-summary {
    max-width: 708px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
@media (min-width: 992px) {
  div.container-summary {
    max-width: 912px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
@media (min-width: 1280px) {
  div.container-summary {
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
</style>
