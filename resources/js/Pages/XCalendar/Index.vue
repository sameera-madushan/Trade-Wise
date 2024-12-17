<script setup>
import { getCurrentInstance as instance, ref, onMounted  } from 'vue'
import DashboardLayout from '@/Layouts/User.vue'
import { Head, router } from '@inertiajs/vue3'

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import axios from 'axios';

const { proxy } = instance()

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Calander'

const events = ref([]);

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events,
  dateClick: handleDateClick,
})

async function getEvents() {
  try {
    const response = await axios.get(`/user/calendar/get-pnls`);
    events.value = response.data;
  } catch (error) {
    console.error('Failed to fetch events:', error);
  }
}
async function handleDateClick(info) {
  const selectedDate = info.dateStr
  const timestamp = new Date(selectedDate).getTime();

  router.get(`/user/calendar/${timestamp}`)
}

onMounted(() => {
  getEvents()
})

</script>

<template>
	<Head title="Calander" />

	<DashboardLayout>
		<div class="row mt-4 mb-4">
			<div class="col-12 col-xl-12">
				<div class="card border-0 shadow components-section">
					<div class="card-body">
            <FullCalendar :options="calendarOptions" />
          </div>
				</div>
			</div>
		</div>
	</DashboardLayout>
</template>

<style>
  .fc-daygrid-day {
    cursor: pointer;
  }
</style>
