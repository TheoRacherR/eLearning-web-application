<script>

import Number from './Number.vue';
import Graph from './Graph.vue';
import LineChart from './LineChart.vue';
import PieChart from './PieChart.vue';
import PolarAreaChart from './PolarAreaChart.vue'

export default {
  components: {
    LineChart,
    PieChart,
    PolarAreaChart,
    Number
  },
  data() {
    return {
        chartDataLine: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
            {
                label: 'Sales',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                data: [65, 59, 80, 81, 56, 55],
            }
            ]
        },
        chartDataPie: {
            labels: ['Red', 'Blue', 'Yellow'],
            datasets: [
            {
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
                ]
            }
            ]
        },
        chartDataPolar: {
            datasets: [{
                data: [10, 20, 30]
            }],

            labels: [
                'Red',
                'Yellow',
                'Blue'
            ]
        },
        content: [
            {
                title: "Nombre d'élèves :",
                number: 50
            },
            {
                title: "Nombre de prof :",
                number: 195
            },
            {
                title: "Nombre total :",
                number: 250
            },
        ],
        total: 250,
    }
  }
};
</script>



<template>

    <div class="container-main">

        <div class="firstline">
            <div v-for="c in content">
                <Number :title="c.title" :number="c.number" :total="total"/>
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