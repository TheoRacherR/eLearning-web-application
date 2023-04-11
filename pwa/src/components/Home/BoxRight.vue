<script setup>

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
    img: {
        type: String,
        required: true,
    }
});


</script>

<template>

    <div class="box-container bc-right">
        <h2 class="title-box">{{ title }}</h2>

        <div class="bc-main">

            <div class="box-item box-left">
                {{ text }}
            </div>
            
            <div class="box-item box-right">
                <img :src="img" alt="image">
            </div>
        
        </div>

    </div>

</template>


<style scoped>

div.box-container {
    margin-bottom: 4rem;
}

div.box-container > div.title-box {
    text-align: center;
    /* font-size: 2.5rem; */
    width: 100%;
}

div.box-container > div.bc-main {
    display: flex;
    width: 100%;

}

div.box-container > div.bc-main > div.box-left {
    flex: auto;
    padding: 0 1rem 0 0;
    text-align: right;
}


div.box-container > div.bc-main > div.box-right > img {
    width: 250px;
    height: 250px;
    object-fit: cover;
}

</style>