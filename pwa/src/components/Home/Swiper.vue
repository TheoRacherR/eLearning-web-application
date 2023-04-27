<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination } from "swiper";
import 'swiper/css';
import 'swiper/css/pagination';
import { ref } from 'vue';

const props = defineProps({
  content: {
    type: Array,
    required: true,
  },
});

const swiperTextBase = ref([
    {
        author: "Test",
        description: "Ceci est une description pour test 1",
        img: "https://via.placeholder.com/40x40",
    },
    {
        author: "Test2",
        description: "Ceci est une description pour test 2",
        img: "https://via.placeholder.com/40x40",
    },
    {
        author: "Test3",
        description: "Ceci est une description pour test 3",
        img: "https://via.placeholder.com/40x40",
    },
    
])
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
                <div>
                    <h4>{{ cont.title }}</h4>
                    <p>{{ cont.description.slice(0, 100) }}{{ cont.description.length > 103 ? "..." : "" }}</p>
                    
                </div>
                <img v-if="cont.image !== null" src="../../../public/not-found.png" alt="not found image">
                <img v-else :src="cont.image" alt="image">

                
            </swiper-slide>
        </swiper>
    </div>
</template>

<style lang="scss" scoped>

div.container {

    padding: 2rem;
    // background-color: rgb(165, 165, 165);

    img {
        width: 400px;
        height: 400px;
        object-fit: cover;
    }
}

</style>