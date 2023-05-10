<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination } from "swiper";
import 'swiper/css';
import 'swiper/css/pagination';
import { ref } from 'vue';
import { RouterLink } from "vue-router"

const props = defineProps({
  content: {
    type: Array,
    required: true,
  },
});

const modules = [Autoplay, Pagination]
</script>

<template>
    <div class="container">
        <swiper
            :modules="modules"
            :space-between="20"
            :loop="true"
            :pagination="{clickable: true}"
            :autoplay="{
                delay: 7000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            }"
        >
        <swiper-slide v-for="cont in content" :key="cont.description">
            <RouterLink :to="`/detail/${cont.id}`">
                <div class="cont">
                    <h4>{{ cont.title }}</h4>
                    <p>{{ cont.description.slice(0, 100) }}{{ cont.description.length > 103 ? "..." : "" }}</p>
                    
                </div>
                <img v-if="cont.image !== null" src="../../../public/not-found.png" alt="not found image">
                <img v-else :src="cont.image" alt="image">
            </RouterLink>

                
            </swiper-slide>
        </swiper>
    </div>
</template>

<style lang="scss" scoped>

div.container {

    padding: 2rem;
    // background-color: rgb(165, 165, 165);

    a {
        text-decoration: none;
        
        div.cont {
    
            color: black;
            h4, p{
                letter-spacing: 1px;
            }
        }
    
        img {
            width: 400px;
            height: 400px;
            object-fit: cover;
        }
    }

}

</style>