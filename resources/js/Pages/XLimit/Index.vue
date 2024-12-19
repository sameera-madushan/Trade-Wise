<script setup>
import { getCurrentInstance as instance, ref, watch, onMounted, nextTick } from 'vue';
import DashboardLayout from '@/Layouts/User.vue';
import { Head } from '@inertiajs/vue3';
import { isRequired, isNumeric } from 'intus/rules';
import { useValidatedForm } from '@/Composables';
import { format } from 'date-fns';

const form = useValidatedForm({
  date: [new Date(), [isRequired()]],
  limit: ["", [isRequired(), isNumeric()]],
});

const submit = async () => {

  const formattedDate = format(new Date(form.date), 'yyyy-MM-dd');
  form.date = formattedDate;

  await form.post(route('limit.store'));
};

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
              <div class="row align-items-center mb-3">
                <div class="col-6">
                  <div class="calendar d-flex"><span class="calendar-month">Aug</span><span class="calendar-day py-2">10</span></div>
                </div>
                <div class="col-6">
                  <div class="progress-wrapper"><div class="progress-info"><div class="h6 mb-0">$1,000</div><div class="small fw-bold text-gray-500"><span>75 %</span></div></div><div class="progress mb-0"><div class="progress-bar bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div></div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
</template>
