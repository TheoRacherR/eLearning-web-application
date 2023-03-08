<script setup>

    const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    validItems: {
        type: Object,
        required: true,
    }
    });

</script>

<template>

    <h3 class="title-list">{{title}}</h3>
    <div class="wrapperItems">
        <div v-for="item in validItems" :key="item.id" class="cart">
            <!--<div v-for="item in validItems" :key="item.id" class="card mt-3" style="width: 18rem">-->
            <img src="https://media.istockphoto.com/id/1322277517/fr/photo/herbe-sauvage-dans-les-montagnes-au-coucher-du-soleil.jpg?s=612x612&w=0&k=20&c=tQ19uZQLlIFy8J6QWMyOL6lPt3pdSHBSDFHoXr1K_g0=" class="card-img-top" alt="..."/>
            <div class="card-body">
                <h5 class="cart-title">{{ item.title }}</h5>
                <p class="card-text">{{ item.description }}</p>
                <router-link v-if="item.possessed" :to="`/course/${item.id}`" class="bttn bttn-prim-out bttn-link" >Reprendre ce cours</router-link>
                <router-link v-if="item.possessed" :to="`/detail/${item.id}`" class="bttn bttn-prim bttn-link" >Détail du cours</router-link>
                <router-link v-else :to="`/detail/${item.id}`" class="bttn bttn-prim bttn-link">Suivre ce cours</router-link>
            </div>
        </div>
    </div>

</template>


<style scoped>

.wrapperItems {
  display: flex;
  overflow-x: scroll;
  -webkit-overflow-scrolling: touch; /* pour le scroll mobile */
  margin-bottom: 7rem;

}

.title-list {
  margin: 3rem 0 0 0;
}


.bttn-link {
  text-decoration: none;
}

.cart {
  min-width: 18rem;
  margin: 2rem;
  border-radius: 0.375rem;
  border: 1px solid rgb(0, 0, 0, 0.175);
  padding-bottom: 1.5rem;
  overflow: hidden;
}

.cart-title {
  padding-top: 1rem;
}

</style>