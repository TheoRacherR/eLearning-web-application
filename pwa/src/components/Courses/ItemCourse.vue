<script setup>

import { store } from "../../store/store.js";


const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  id: {
    type: Number,
    required: true,
  }
});

const link = "/detail/" + props.id;

</script>

<template>
    <RouterLink :to="link">
        <div class="container-item">
            <img src="https://via.placeholder.com/250x250" alt="image of the course">
            <div class="right-box">
                <div class="title-item">{{ title }}</div>
                <div class="description-item">{{ description }}</div>
                <div class="check" v-if="course?.possessed && store.user.isConnected">
                    <div>
                        Vous possédez ce cours
                    </div>
                </div>
            </div>
        </div>
    </RouterLink>
</template>

<style lang="scss" scoped>

a {
    text-decoration: none;
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
                
                > div {
                    padding: .2rem 1rem;
                    background-color: #49961d;

                }
            }
        }
    }
}
</style>