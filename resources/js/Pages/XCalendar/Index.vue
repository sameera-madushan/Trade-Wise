<script setup>
import { getCurrentInstance as instance, ref, onMounted, watch } from 'vue'
import DashboardLayout from '@/Layouts/User.vue'
import { Head, router } from '@inertiajs/vue3'

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import axios from 'axios'

const { proxy } = instance()

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Calander'

const events = ref([])
const limits = ref([])
const isLoading = ref(true)

const renderLockOpenIcon = (info) => {
  const currentDate = info.date.toLocaleDateString('en-CA');
  const limitForDate = findLimitForDate(currentDate);

  info.el.style.cursor = 'pointer';

  if (limits.value.length === 0) return;

  if (limitForDate) {
    const iconType = limitForDate.percentage_used === 100 ? 'lock' : 'lock_open';
    const icon = createIcon(iconType);
    positionIcon(info.el, icon);
  }
};

const findLimitForDate = (currentDate) => {
  return (limits.value).find(limit => {
    const limitDate = new Date(limit.limit_date).toLocaleDateString('en-CA');
    return limitDate === currentDate;
  });
};

const createIcon = (iconType) => {
  const icon = document.createElement('span');
  icon.classList.add('lock-open-icon');
  icon.innerHTML = `<span class="material-icons">${iconType}</span>`;
  return icon;
};

const positionIcon = (cell, icon) => {
  cell.style.position = 'relative';
  icon.style.position = 'absolute';
  icon.style.top = '50%';
  icon.style.left = '50%';
  icon.style.transform = 'translate(-50%, -50%)';
  icon.style.fontSize = '24px';
  icon.style.color = 'red';
  icon.style.zIndex = '10';
  cell.appendChild(icon);
};

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events,
  dateClick: handleDateClick,
  dayCellDidMount: renderLockOpenIcon,
})

async function getEvents() {
  try {
    const response = await axios.get(`/user/calendar/get-pnls`)
    events.value = response.data
  } catch (error) {
    console.error('Failed to fetch events:', error)
  }
}

async function getLimits() {
  try {
    const response = await axios.get('/user/limits/get-limits')
    limits.value = response.data
  } catch (error) {
    console.error('Failed to fetch limits:', error)
  } finally {
    isLoading.value = false
  }
}

async function handleDateClick(info) {
  const selectedDate = info.dateStr
  const timestamp = new Date(selectedDate).getTime()

  router.get(`/user/calendar/${timestamp}`)
}

onMounted(() => {
  getEvents()
  getLimits()
})
</script>

<template>
  <Head title="Calander" />

  <DashboardLayout>
    <div class="row mt-4 mb-4">
      <div class="col-12 col-xl-12">
        <div class="card border-0 shadow components-section">
          <div class="card-body">
            <FullCalendar v-if="!isLoading" :options="calendarOptions" />
              <div v-else class="spinner">
                <div class="loading-spinner"></div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>

.lock-open-icon {
  font-size: 36px;
  color: white;
  background-color: rgba(0, 0, 0, 0.3);
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 9999;
}

.spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  min-height: 200px;
}

.loading-spinner {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #0a428a;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<style>
.fc-event-title-container {
  text-align: center;
}

.fc-daygrid-event-harness {
  margin-left: 10px;
  margin-right: 10px;
}
</style>
