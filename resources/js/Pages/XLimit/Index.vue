<script setup>
import { getCurrentInstance as instance, ref, watch, onMounted, nextTick } from 'vue';
import DashboardLayout from '@/Layouts/User.vue';
import { Head } from '@inertiajs/vue3';
import { isRequired, isNumeric } from 'intus/rules';
import { useValidatedForm } from '@/Composables';
import { format } from 'date-fns';
import axios from 'axios';

const { proxy } = instance()
const table = ref()
let dt

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

const formatDate = (dateString, formatPattern) => {
  return format(new Date(dateString), formatPattern);
};

const disablePastDates = (date) => {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return date < today;
};


const columns = [
	{ data: 'timestamp', visible: false, searchable: false },
	{ data: 'limit_date' },
	{ data: 'limit_amount' },
	{ data: 'total_buy_price' },
  {
    data: 'percentage_used',
    render: function (data) {
        return data + '%';
    }
  }
]

const options = {
	ordering: true,
	order: [[0, 'desc']],
	processing: true,
	serverSide: true,
	responsive: true,
	autoWidth: false,
  ajax: {
    url: `${$APP_URL}/user/limits/get-limits`,
    type: 'GET',
    data: function (d) {
      d.forDataTable = true;
    },
  },
}

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
      <div class="col-12 mt-4">
					<div class="card border-0 shadow components-section">
						<div class="card-body">
              <div class="flexible-table">
                <div class="py-4">
                  <DataTables
                    id="limits-table"
                    ref="table"
                    :options="options"
                    :columns="columns"
                    class="table table-hover table-text-middle pt-4"
                    width="100%"
                  >
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Limit Amount</th>
                        <th>Amount Used</th>
                        <th>Percentage Used</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                  </DataTables>
                </div>
              </div>
						</div>
					</div>
				</div>
    </DashboardLayout>
</template>
