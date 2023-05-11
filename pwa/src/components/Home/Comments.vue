<script setup>

import router from "../../router";
import { store } from "../../store/store";
import { ref, isReactive, watchEffect } from "vue";
import Comment from "../Courses/Comment.vue";

const props = defineProps({
    size: {
        type: Number,
        required: true,
    },
});


const itemsComments = ref({})
const validItemsComments = ref({})

watchEffect(() => {

    itemsComments.value = store.comments.list;

    let countComments = 0

    for (const item in itemsComments.value) {
        if (itemsComments.value[item].valid === 1 && itemsComments.value[item].star > 7) {
            validItemsComments.value = {
                ...validItemsComments.value,
                [parseInt(Object.keys(itemsComments.value).at(-1)) - item]: { ...itemsComments.value[item] },
            };
            countComments++;
        }
        if (countComments >= props.size) {
            break;
        }
    }
    console.log(validItemsComments.value)
})

</script>

<template>

    <div class="box-container bc-right">
        <h2 class="title-box">Vos retours :</h2>

        <div class="bc-main">

            <div class="box-item box-left">
                <Comment :items="validItemsComments"/>
            </div>
            
            
        
        </div>

    </div>

</template>


<style lang="scss" scoped>

div.box-container {
    margin-bottom: 4rem;

    div.box-item.box-left {
        margin: 10vh auto 3vh auto;
        width: 50%;
        text-align: left;   
    }
}



</style>