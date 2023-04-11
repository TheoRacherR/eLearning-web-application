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

    <div class="box-container bc-left">
        <h2 class="title-box">{{ title }}</h2>

        <div class="bc-main">

            <div class="box-item box-left">
                <img :src="img" alt="image">
            </div>
            
            <div class="box-item box-right">
                {{ text }}
            </div>
        
        </div>

    </div>

</template>


<style lang="scss" scoped>

div.box-container {
    margin-bottom: 7rem;
    
    h2.title-box {
        text-align: center;
        width: 100%;
        margin-bottom: 5rem;
    }
    div.bc-main {
        display: flex;
        width: 100%;

        div.box-left > img {
            width: 250px;
            height: 250px;
            object-fit: cover;
        }

        div.box-right {
            flex: auto;
            padding: 0 0 0 1rem;
            text-align: left;
        }
    }
}


</style>