<script setup>

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  number: {
    type: Number,
    required: true,
  },
  total: {
    type: Number,
    required: true,
  },
});

const pg = (props.number / props.total) * 100;


</script>


<template>
    <div class="block">
        <h4>{{ title }}</h4>
        <div class="box">
            <div class="number">{{ number }}</div>
            <div class="text"> Soit {{ pg }}% du nombre total</div>
        </div>
    </div>
</template>


<style lang="scss" scoped>

div.block {
    width: 25vw;
    background-color: rgb(230, 230, 230);
    padding: 1rem 1rem 0 1rem;

    h4 {
        text-align: center;
    }
    
    div.box {
        width: 100%;
        height: 75%;
        // background-color: blueviolet;
        padding: 1rem 1rem 2rem 1rem;

        div.number {
            text-align: center;
            font-size: 4rem;
        }

        div.text {
            margin-top: 1rem;
        }
    }
}

</style>