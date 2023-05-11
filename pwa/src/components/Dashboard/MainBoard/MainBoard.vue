<script setup>

import Number from './Number.vue';
import Graph from './Graph.vue';
import LineChart from './LineChart.vue';
import PieChart from './PieChart.vue';
import PolarAreaChart from './PolarAreaChart.vue'
import { ref, watchEffect } from "vue"
import { store } from "../../../store/store"

const chartDataLine = ref({})
const chartDataPie = ref({})
const chartDataPolar = ref({})
const content = ref([])
const totalCountUser = ref(0)

const userAdmin = ref({})
const userTeacher = ref({})
const userUser = ref({})

const monthCourses = ref({})

watchEffect(() => {

    for (const item in store.users.list) {
        if (store.users.list[item].roles.includes("ROLE_ADMIN")) {
            userAdmin.value = {
                ...userAdmin.value,
                [item]: { ...store.users.list[item] },
            }
        }
        else if (store.users.list[item].roles.includes('ROLE_FORMER')) {
            userTeacher.value = {
                ...userTeacher.value,
                [item]: { ...store.users.list[item] },
            }
        }
        else {
            userUser.value = {
                ...userUser.value,
                [item]: { ...store.users.list[item] },  
            }
        }
    }

    for (const item in store.courses.list) {
        let month = store.courses.list[item].created_at.getMonth();

        month = parseInt(month);

        if (monthCourses.value[month] === undefined) {
            monthCourses.value[month] = 1;
        }
        else {
            monthCourses.value[month] += 1;
        }
    }

    let count = 1;
    let arrayMonth = [];
    for (const i in monthCourses.value) {
        if (i != count) {
            let diff = parseInt(i) - parseInt(count);
            for (let j=0; j < parseInt(diff); j++) {
                arrayMonth.push(0);
            }
            count = parseInt(i);
        }
        arrayMonth.push(monthCourses.value[i]);
        count++;
    }


    content.value = [
        {
            title: "Nombre d'élèves :",
            number: Object.values(userUser.value).length
        },
        {
            title: "Nombre de prof :",
            number: Object.values(userTeacher.value).length
        },
        {
            title: "Nombre d'administrateurs :",
            number: Object.values(userAdmin.value).length
        },
    ]
    chartDataLine.value = {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sept', 'Oct', 'Nov', 'Déc'],
        datasets: [
            {
                label: 'Cours',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                data: arrayMonth,
            },
        ]
    };
    
    chartDataPie.value = {
        labels: ['Élèves', 'Professeurs', 'Administrateurs'],
        datasets: [
            {
                label: 'My First Dataset',
                data: [Object.values(userUser.value).length, Object.values(userTeacher.value).length, Object.values(userAdmin.value).length],
                backgroundColor: [
                    'rgb(255, 205, 86)',
                    'rgb(92, 209, 24)',
                    'rgb(54, 162, 235)',
                ]
            }
        ]
    };
    
    chartDataPolar.value = {
        datasets: [{
            data: [Object.values(store.comments.list).length, Object.values(store.users.list).length, Object.values(store.reports.list).length]
        }],
    
        labels: [
            'Commentaires',
            'Utilisateurs',
            'Signalements'
        ]
    };

    totalCountUser.value = Object.values(store.users.list).length;
})
</script>



<template>

    <div class="container-main">

        <div class="firstline">
            <div v-for="c in content">
                <Number :title="c.title" :number="c.number" :total="totalCountUser"/>
            </div>
        </div>
        <div class="secondline">
            <LineChart :chart-data="chartDataLine"/>

            <div class="wrapper">
                <PolarAreaChart :chart-data="chartDataPolar"/>
            </div>

            <div class="wrapper">
                <PieChart :chart-data="chartDataPie"/>
            </div>
        </div>

    </div>

    

</template>



<style lang="scss" scoped>

div.container-main {
    width: 85%;
    min-height: 100vh;
    padding: 2rem 2rem;

    div.firstline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    div.secondline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;

        div.wrapper {
            // width: 25vw;
            background-color: rgb(230, 230, 230);
            padding: 1rem;
        }
    }
}


</style>