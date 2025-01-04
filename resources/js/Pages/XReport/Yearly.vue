<script setup>
import { getCurrentInstance as instance, ref, watch, onMounted, nextTick } from 'vue';
import DashboardLayout from '@/Layouts/User.vue';
import { Head } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import axios from 'axios';

const { proxy } = instance();

proxy.$appState.parentSelection = 'Reports';
proxy.$appState.elementName = 'yearly_pnl';

const year = ref(new Date().getFullYear());

const tableRef = ref(null);
let dataTable = null;
const chartRef = ref(null);
let donutChart = null;

const options = {
  ordering: true,
  order: [[0, 'desc']],
  processing: true,
  serverSide: true,
  responsive: true,
  autoWidth: false,
  ajax: {
    url: '',
    type: 'GET',
    dataSrc: (json) => {

      let totalProfit = 0;
      let totalLoss = 0;

      json.data.forEach((row) => {
        const pnlValue = parseFloat(row.pnl);
        if (pnlValue > 0) {
          totalProfit += pnlValue;
        } else if (pnlValue < 0) {
          totalLoss += Math.abs(pnlValue);
        }
      });

      if (donutChart) {
        donutChart.data.datasets[0].data = [totalProfit, totalLoss];
        donutChart.update();
      }

      return json.data;
    },
  },
};

const columns = [
  { data: 'id', visible: false, searchable: false },
  { data: 'curruncy_pair' },
  { data: 'position' },
  { data: 'buy_value' },
  { data: 'buy_price' },
  { data: 'bought_amount' },
  { data: 'sell_value' },
  { data: 'sell_price' },
  {
    data: 'pnl',
    render: function (data) {
      const pnlValue = parseFloat(data);
      const colorClass = pnlValue > 0 ? 'text-success' : pnlValue < 0 ? 'text-danger' : '';
      return `<span class="${colorClass}">${pnlValue}</span>`;
    },
  },
];

onMounted(async () => {
  await nextTick();
  const formattedYear = `${year.value}`;
  options.ajax.url = `${$APP_URL}/user/report/get-yearly-report-data/${formattedYear}`;

  dataTable = $(tableRef.value).DataTable({
    ...options,
    columns,
  });

  donutChart = new Chart(chartRef.value, {
    type: 'doughnut',
    data: {
      labels: ['Total Profit', 'Total Loss'],
      datasets: [
        {
          data: [0, 0],
          backgroundColor: ['#28a745', '#dc3545'],
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
        tooltip: {
          enabled: true,
        },
      },
    },
    plugins: [
      {
        id: 'centerText',
        beforeDraw: function (chart) {
          const ctx = chart.ctx;
          const width = chart.chartArea.width;
          const height = chart.chartArea.height;
          const centerX = chart.chartArea.left + width / 2;
          const centerY = chart.chartArea.top + height / 2;

          const profit = chart.data.datasets[0].data[0] || 0;
          const loss = chart.data.datasets[0].data[1] || 0;
          const overallPnL = profit - loss;

          ctx.save();

          // First Line
          const textLine1 = 'Overall PnL';
          const fontSize1 = 14;
          ctx.font = `${fontSize1}px sans-serif`;
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          ctx.fillStyle = '#000';
          ctx.fillText(textLine1, centerX, centerY - 10);

          // Second Line
          const textLine2 = overallPnL.toFixed(2);
          const fontSize2 = 16;
          ctx.font = `bold ${fontSize2}px sans-serif`;
          ctx.fillStyle = overallPnL >= 0 ? '#28a745' : '#dc3545';
          ctx.fillText(textLine2, centerX, centerY + 10);

          ctx.restore();
        },
      },
    ],
    });
});

const downloadExcel = async () => {
  try {
    const formattedYear = `${year.value}`;
    const response = await axios.post(
      `${$APP_URL}/user/report/generate-report`,
      {
        date: formattedYear,
        type: 'yearly',
      },
      {
        responseType: 'blob',
      }
    );

    // Create a link to download the file
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;

    const contentDisposition = response.headers['content-disposition'];
    const filename = contentDisposition
      ? contentDisposition.split('filename=')[1].replace(/"/g, '')
      : 'report.xlsx';

    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error('Error downloading report:', error);
  }
};

watch(year, async (newYear) => {
  if (dataTable) {
    await nextTick();
    const formattedYear = `${newYear}`;
    const newUrl = `${$APP_URL}/user/report/get-yearly-report-data/${formattedYear}`;
    dataTable.ajax.url(newUrl).load();
  }
});

</script>

<template>
  <Head title="Monthly PnL Report" />

  <DashboardLayout>
    <div class="row mt-4 mb-4">
      <div class="col-12 col-xl-12">
        <div class="d-flex justify-content-end mb-2">
          <button class="btn btn-primary" @click="downloadExcel">Download Excel</button>
        </div>
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="row align-items-center">
              <!-- Date Picker -->
              <div class="col-12">
                  <VueDatePicker v-model="year" year-picker />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4 mb-4">
      <div class="col-12 col-xl-12">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <div class="chart-container" style="position: relative; height: 250px; width: 250px; margin: 0 auto;">
              <canvas ref="chartRef"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4 mb-4">
      <div class="col-12 col-xl-12">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <table
              id="daily-trades-table"
              ref="tableRef"
              class="table table-hover table-text-middle pt-4"
              width="100%"
            >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Currency Pair</th>
                  <th>Position</th>
                  <th>Buy Value</th>
                  <th>Buy Price</th>
                  <th>Bought Amount</th>
                  <th>Sell Value</th>
                  <th>Sell Price</th>
                  <th>P and L</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
