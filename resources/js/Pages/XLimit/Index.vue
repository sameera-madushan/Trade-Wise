<script setup>
import { getCurrentInstance as instance, ref, watch, onMounted, nextTick } from 'vue';
import DashboardLayout from '@/Layouts/User.vue';
import { Head } from '@inertiajs/vue3';
import { isRequired, isNumeric } from 'intus/rules';
import { useValidatedForm } from '@/Composables';
import { format } from 'date-fns';
import axios from 'axios';

const { proxy } = instance()

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Limits'

const limits = ref([]);

const form = useValidatedForm({
  date: [new Date(), [isRequired()]],
  limit: ["", [isRequired(), isNumeric()]],
});

const submit = async () => {

  const formattedDate = format(new Date(form.date), 'yyyy-MM-dd');
  form.date = formattedDate;

  await form.post(route('limit.store'));
  fetchLimits();
};

const fetchLimits = async () => {
  try {
    const response = await axios.get('/user/limits/get-limits');
    limits.value = response.data;
  } catch (error) {
    console.error('Error fetching limits:', error);
  }
};

const formatDate = (dateString, formatPattern) => {
  return format(new Date(dateString), formatPattern);
};

const getProgressBarClass = (percentage) => {
  if (percentage === 100) {
    return 'bg-danger';
  } else if (percentage > 50) {
    return 'bg-warning';
  } else {
    return 'bg-success';
  }
};

const disablePastDates = (date) => {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return date < today;
};

onMounted(() => {
  fetchLimits();
});
</script>

<template>
    <Head title="Limits" />

    <DashboardLayout>
      <div class="row mt-4 mb-4">
        <div class="col-12 col-xl-12">
          <div class="card border-0 shadow components-section">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-4">
                <VueDatePicker
                  v-model="form.date"
                  :enable-time-picker="false"
                  placeholder="Select a date"
                  :format="'yyyy-MM-dd'"
                  :disabled-dates="disablePastDates"
                />
              </div>
              <div class="col-4">
                <input id="limit" v-model="form.limit" type="number" class="form-control" :class="{ 'is-invalid': form.errors.limit }" placeholder="Limit"/>
              </div>
              <div class="col-4">
                <button @click="submit" class="btn btn-primary w-100">Submit</button>
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
              <div v-for="(limit, index) in limits" :key="index" class="row align-items-center mb-3">
                <div class="col-6">
                  <div class="calendar d-flex">
                    <span class="calendar-month"> {{ formatDate(limit.limit_date, 'yyyy') }} {{ formatDate(limit.limit_date, 'MMM') }}</span>
                    <span class="calendar-day py-2">{{ formatDate(limit.limit_date, 'dd') }}</span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="progress-wrapper">
                    <div class="progress-info">
                      <div class="h6 mb-0">
                        ${{ limit.limit_amount }}
                      </div>
                      <div class="small fw-bold text-gray-500">
                        <span>{{ limit.percentage_used }} %</span>
                      </div>
                    </div>
                    <div class="progress mb-0">
                      <div :class="getProgressBarClass(limit.percentage_used)" role="progressbar" aria-valuenow="limit.percentage_used" aria-valuemin="0" aria-valuemax="100" :style="{ width: `${limit.percentage_used}%` }"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
</template>
