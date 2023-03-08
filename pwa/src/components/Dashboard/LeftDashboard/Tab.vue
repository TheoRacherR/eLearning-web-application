<script setup>

import { RouterLink } from "vue-router";

const props = defineProps({
  text: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  link: {
    type: String,
    required: true,
  }
});

</script>



<template>
    <div>
      <!--<button class="bttn link_tab" type="button">-->
      <RouterLink :to="link">

        <div class="container-tab">

          <va-icon :name="icon" size="small"/>
          {{ text }}
          
        </div>
        <va-icon name="chevron_right" />
      </RouterLink>
      <!--</button>-->
    </div>
</template>



<style lang="scss" scoped>


a {
  display: flex;
  justify-content: space-between;
  width: 100%;
  text-decoration: none;
  color: white;
  
  div.container-tab {
    font-size: 15px;
    // width: 2rem;
    display: flex;

    > i {
      margin-right: 1rem;
    }
  }
  // div.right-div {
  //   margin-right: 1rem;
  // }
   > i {
    margin-right: 1rem;
   }
  
}


</style>