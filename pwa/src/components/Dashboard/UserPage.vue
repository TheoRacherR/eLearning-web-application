<script setup>

import LeftDashboard from './LeftDashboard/LeftDashboard.vue';




</script>



<template>
    <div class="container-dashboard">

        <LeftDashboard/>
        <div class="main-page">
            <h2>User Page</h2>
            
        </div>
    
    </div>
</template>



<style lang="scss" scoped>

div.container-dashboard {
    width: 100%;
    height: 100%;
    display: flex;
    /* background-color: aqua; */

    div.container-commlist {
        padding: 2rem 5rem;
        width: 90%;

        div.container-grid {
            display: grid;
            grid-auto-columns: auto;
            grid-auto-rows: auto;

            thead {
                background-color: rgb(133, 85, 63);
                color: #fff;
            }
            th, td {
                padding: 1rem;
            }

            td.verifed{
                color: #52b425;
            }

            td.waiting {
                color: rgb(168, 43, 43);
            }
            tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }



        }
    
    }
}




</style>