<script setup>
import { getCurrentInstance as instance, onMounted, ref  } from 'vue'
import DashboardLayout from '@/Layouts/User.vue'
import { Head } from '@inertiajs/vue3'
import Chart from 'chart.js/auto';
import axios from 'axios';

const { proxy } = instance()

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Dashboard'

const yearlyChartData = ref({ labels: [], datasets: [] });
const monthlyChartData = ref({ labels: [], datasets: [] });
const todayTradesData = ref([]);

onMounted(async () => {
  try {

    const [yearlyResponse, monthlyResponse, todayTradesResponse] = await Promise.all([
      axios.get(`/user/get-monthly-pnls`),
      axios.get(`/user/get-daily-pnls`),
      axios.get(`/user/get-today-trades`),
    ]);

    const generateColors = (data) => data.map((value) => (value >= 0 ? 'rgba(75, 192, 192, 0.2)' : 'rgba(255, 99, 132, 0.2)'));

    // Yearly Chart
    yearlyChartData.value = {
      labels: yearlyResponse.data.labels,
      datasets: [
        {
          label: "Net PnL",
          data: yearlyResponse.data.net_pnl,
          borderWidth: 1,
          borderColor: yearlyResponse.data.net_pnl.map((value) =>
            value >= 0 ? 'rgb(75, 192, 192)' : 'rgb(255, 99, 132)'
          ),
          backgroundColor: generateColors(yearlyResponse.data.net_pnl),
        },
      ],
    };

    const yearlyCtx = document.getElementById('yearChart');
    if (!yearlyCtx) {
      console.error('Canvas element not found.');
      return;
    }

    new Chart(yearlyCtx, {
      type: 'bar',
      data: yearlyChartData.value,
      options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });

    // Monthly Chart
    monthlyChartData.value = {
      labels: monthlyResponse.data.labels,
      datasets: [
        {
          label: "Net PnL",
          data: monthlyResponse.data.net_pnl,
          borderWidth: 1,
          borderColor: monthlyResponse.data.net_pnl.map((value) =>
            value >= 0 ? 'rgb(75, 192, 192)' : 'rgb(255, 99, 132)'
          ),
          backgroundColor: generateColors(monthlyResponse.data.net_pnl),
        },
      ],
    };

    const ctx = document.getElementById('monthChart');
    if (!ctx) {
      console.error('Canvas element not found.');
      return;
    }

    new Chart(ctx, {
      type: 'bar',
      data: monthlyChartData.value,
      options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });

    // Today Trades
    todayTradesData.value = todayTradesResponse.data;
    const dayChartData = {
      labels: todayTradesData.value.map((trade) => trade.curruncy_pair),
      datasets: [
        {
          label: '',
          data: todayTradesData.value.map((trade) => trade.pnl),
          borderColor: todayTradesData.value.map((trade) =>
            trade.pnl >= 0 ? 'rgb(75, 192, 192)' : 'rgb(255, 99, 132)'
          ),
          backgroundColor: todayTradesData.value.map((trade) =>
            trade.pnl >= 0 ? 'rgba(75, 192, 192, 0.2)' : 'rgba(255, 99, 132, 0.2)'
          ),
          fill: false,
          tension: 0.4,
        },
      ],
    };

    const dayCtx = document.getElementById('dayChart');
    if (dayCtx) {
      new Chart(dayCtx, {
        type: 'line',
        data: dayChartData,
        options: {
          responsive: true,
          maintainAspectRatio: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
          plugins: {
            legend: {
              display: false,
            },
            annotation: {
              annotations: {
                zeroLine: {
                  type: 'line',
                  yMin: 0,
                  yMax: 0,
                  borderColor: 'rgba(0, 0, 0, 0.8)',
                  borderWidth: 2,
                  label: {
                    enabled: true,
                    content: 'Zero Level',
                    position: 'start',
                    color: 'rgba(0, 0, 0, 0.8)',
                  },
                },
              },
            },
          },
        },
        plugins: [
          {
            id: 'drawZeroLine',
            beforeDraw: (chart) => {
              const { ctx, chartArea } = chart;
              ctx.save();
              ctx.strokeStyle = 'rgba(255, 0, 0, 0.8)';
              ctx.lineWidth = 2;
              ctx.beginPath();
              const yZero = chart.scales.y.getPixelForValue(0);
              ctx.moveTo(chartArea.left, yZero);
              ctx.lineTo(chartArea.right, yZero);
              ctx.stroke();
              ctx.restore();
            },
          },
        ],
      });
    }

  } catch (error) {

    console.error('Error fetching chart data:', error);
  }
});
</script>

<template>
	<Head title="Dashboard" />

  <DashboardLayout>
    <div class="card border-0 shadow components-section">
      <div class="card-body d-flex flex-column">
        <div class="card-header border-bottom mb-3">
          <h2 class="fs-5 fw-bold mb-0">Daily Overview</h2>
        </div>
        <div class="chart-wrapper" style="flex: 1; overflow: hidden;">
          <canvas id="dayChart"></canvas>
        </div>
      </div>
    </div>
    <div class="row mt-4 mb-4">
      <div class="col-12 col-md-6 mt-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="card-header border-bottom mb-3"><h2 class="fs-5 fw-bold mb-0">Yearly Overview</h2></div>
            <div class="d-flex justify-content-center align-items-center mb-3">
              <div class="me-4 d-flex align-items-center">
                <span
                  class="legend-box me-2"
                  style="background-color: rgba(75, 192, 192, 0.2); border: 1px solid rgb(75, 192, 192); width: 15px; height: 15px; display: inline-block;">
                </span>
                <span>Profit</span>
              </div>
              <div class="d-flex align-items-center">
                <span
                  class="legend-box me-2"
                  style="background-color: rgba(255, 99, 132, 0.2); border: 1px solid rgb(255, 99, 132); width: 15px; height: 15px; display: inline-block;">
                </span>
                <span>Loss</span>
              </div>
            </div>
            <canvas id="yearChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 mt-4">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="card-header border-bottom mb-3"><h2 class="fs-5 fw-bold mb-0">Monthly Overview</h2></div>
            <div class="d-flex justify-content-center align-items-center mb-3">
              <div class="me-4 d-flex align-items-center">
                <span
                  class="legend-box me-2"
                  style="background-color: rgba(75, 192, 192, 0.2); border: 1px solid rgb(75, 192, 192); width: 15px; height: 15px; display: inline-block;">
                </span>
                <span>Profit</span>
              </div>
              <div class="d-flex align-items-center">
                <span
                  class="legend-box me-2"
                  style="background-color: rgba(255, 99, 132, 0.2); border: 1px solid rgb(255, 99, 132); width: 15px; height: 15px; display: inline-block;">
                </span>
                <span>Loss</span>
              </div>
            </div>
            <canvas id="monthChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>

.chart-wrapper {
  position: relative;
  width: 100%;
  height: auto;
  flex-grow: 1;
  max-height: 300px;
}

canvas {
  display: block;
  max-width: 100%;
  max-height: 100%;
}

</style>

