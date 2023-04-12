<script setup>

import { store } from "../../store/store.js";
import toastr from "toastr";


const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  image: {
    type: String,
    required: false,
  },
  id: {
    type: Number,
    required: true,
  },
  addToCart: {
    type: Boolean,
    required: true,
  }
});


const handleCart = () => {
  let arr = [];
  if (localStorage.getItem("CART") !== null) {
    if (!localStorage.getItem("CART").includes(props.id.toString())) {
      arr = JSON.parse(localStorage.getItem("CART"));
      arr.push(props.id.toString());
      localStorage.setItem("CART", JSON.stringify(arr)); //ajout dans le localStorage
      store.setAddCart(props.id.toString()); //ajout dans le store
    } else {
      console.log("Item déjà dans le panier");
    }
  } else {
    arr.push(props.id.toString());
    localStorage.setItem("CART", JSON.stringify(arr));
  }
  toastr.success("Cours ajouté dans le panier", "", { timeOut: 3000 });
};

</script>

<template>
    <div class="container-item">
        <RouterLink :to="`/detail/${props.id}`">
            <img v-if="props.image !== null" src="../../../public/not-found.png" alt="not found image">
            <img v-else :src="props.image" alt="image of the product" />
        </RouterLink>
        <div class="right-box">
            <RouterLink :to="`/detail/${props.id}`">
                <div class="title-item">{{ title }}</div>
                <div class="description-item">{{ description }}</div>
            </RouterLink>
            <div v-if="props.addToCart === true">
                <div v-if="course?.possessed && store.user.isConnected" class="check">
                    <div class="possessed">
                        Vous possédez ce cours
                    </div>
                </div>
                <div v-else-if="!course?.possessed && store.user.isConnected" class="check">
                    <div v-if="Object.keys(store.cart.list).includes(props.id.toString())" class="alreadyInCart">
                        Déjà dans le panier
                    </div>
                    <div v-else class="addToCart">
                        <button class="bttn bttn-succ" @click="handleCart">
                            <va-icon name="add_shopping_cart" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

a {
    text-decoration: none;
    cursor: pointer;
    color: var(--color-text-light);

}
div.container-item{
    background-color: rgb(233, 233, 233);
    display: flex;
    margin-top: 3vh;


    >img {
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
        .check {
            display: flex;
            justify-content: end;
            color: #fff;
            
            > div.possessed {
                padding: .2rem 1rem;
                background-color: #47ad0b;

            }
            > div.alreadyInCart {
                padding: .2rem 1rem;
                background-color: #ca3611;
            }
        }
    }
}

</style>