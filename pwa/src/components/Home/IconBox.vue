<script setup>


const props = defineProps({
    content: {
        type: Object,
        required: true,
    },

});

</script>



<template>

    <div class="tb-container">
        <div v-for="item in content" :key="item.id" class="tbc-item">
            <div class="tb-item tb-icon">
                <va-icon :name="item.icon"/>
            </div>
            <div class="tb-item tb-text">{{ item.text }}</div>
        </div>
    </div>
</template>


<style scoped>

div.tb-container {
    display: flex;
    width: 100%;
    /* background-color: blue; */
    justify-content: space-around;
    margin-bottom: 7rem;
}

div.tb-container div.tbc-item {
    display: flex;
    min-height: 3rem;
}

div.tb-container div.tbc-item > div.tb-icon {
    background-color: #d1d7dc;
    border-radius: 50%;
    padding: 1.5rem;
}

div.tb-container div.tbc-item div.tb-text{
    margin: auto 0 auto 1.5rem;
}

</style>