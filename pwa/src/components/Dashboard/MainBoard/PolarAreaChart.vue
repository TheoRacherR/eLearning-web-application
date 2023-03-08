<template>
    <div>
      <canvas ref="chart"></canvas>
    </div>
  </template>
  
  <script>
  import { Chart } from 'chart.js/auto';
  
  export default {
    name: 'LineChart',
    props: {
      chartData: {
        type: Object,
        required: true
      }
    },
    mounted() {
      this.renderChart();
    },
    methods: {
      renderChart() {
        const ctx = this.$refs.chart.getContext('2d');
  
        new Chart(ctx, {
          type: 'polarArea',
          data: this.chartData
        });
      }
    }
  };
  </script>
  
  <style scoped>
  div {
    /* width: 25%;
    height: 50%; */
  }
  </style>
  