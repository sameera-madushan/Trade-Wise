<script setup>
import { getCurrentInstance as instance, defineProps, onMounted, ref  } from 'vue'
import DashboardLayout from '@/Layouts/User.vue'
import { Head } from '@inertiajs/vue3'
import Chart from 'chart.js/auto';
import axios from 'axios';

const { proxy } = instance()

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Dashboard'

const props = defineProps({
  uuid: {
    type: String,
    default: null,
  }
})

const yearlyChartData = ref({ labels: [], datasets: [] });
const monthlyChartData = ref({ labels: [], datasets: [] });

onMounted(async () => {
  try {

    const [yearlyResponse, monthlyResponse] = await Promise.all([
      axios.get(`/user/${props.uuid}/get-monthly-pnls`),
      axios.get(`/user/${props.uuid}/get-daily-pnls`),
    ]);

    yearlyChartData.value = {
      labels: yearlyResponse.data.labels,
      datasets: [
        {
          label: "Profit",
          data: yearlyResponse.data.profits,
          borderWidth: 1,
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
        },
        {
          label: "Loss",
          data: yearlyResponse.data.losses,
          borderWidth: 1,
          borderColor: 'rgb(255, 99, 132)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });

    monthlyChartData.value = {
      labels: monthlyResponse.data.labels,
      datasets: [
        {
          label: "Profit",
          data: monthlyResponse.data.profits,
          borderWidth: 1,
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
        },
        {
          label: "Loss",
          data: monthlyResponse.data.losses,
          borderWidth: 1,
          borderColor: 'rgb(255, 99, 132)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });

  } catch (error) {
    console.error('Error fetching chart data:', error);
  }
});
</script>

<template>
	<Head title="Dashboard" />

  <DashboardLayout :uuid="props.uuid">
    <div class="row mt-4 mb-4">
      <div class="col-12 col-md-6">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <h3>Yearly Overview</h3>
            <canvas id="yearChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <h3>Monthly Overview</h3>
            <canvas id="monthChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
