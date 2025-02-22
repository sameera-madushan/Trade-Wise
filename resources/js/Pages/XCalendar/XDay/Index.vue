<script setup>
import { getCurrentInstance as instance, defineProps, computed, watch, ref, onMounted  } from 'vue'
import DashboardLayout from '@/Layouts/User.vue'
import { Head } from '@inertiajs/vue3'
import { isRequired, isNumeric } from 'intus/rules'
import { useValidatedForm } from '@/Composables';
import axios from 'axios';
import NoteModal from './NoteModal.vue';
import Swal from 'sweetalert2'
import { toast } from '@/stores'

const { proxy } = instance()

const quillData = ref('');
const isModalVisible = ref(false);
const selectedTradeId = ref(null);
const isLoadingNote = ref(false);

const openModal = async (tradeId) => {
    selectedTradeId.value = tradeId;
    isModalVisible.value = true;
    isLoadingNote.value = true;

    try {
        const response = await axios.get(`/user/calendar/${props.timestamp}/${selectedTradeId.value}/get-note`);
        if (response.data.success) {
            quillData.value = response.data.data;
        }
    } catch (error) {
        console.error('Error fetching note content:', error);
    } finally {
        isLoadingNote.value = false;
    }
};


const closeModal = () => {
  isModalVisible.value = false;
  selectedTradeId.value = null;
};

function getCurrentDateTimestamp() {
  const today = new Date();
  today.setHours(5, 30, 0, 0);
  return today.getTime();
}

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Calendar'

const table = ref()
const isEditMode = ref(false)
const editRowId = ref(null)
let dt

const props = defineProps({

  timestamp: {
    type: String,
    default: null,
  }

})

if (Number(props.timestamp) === getCurrentDateTimestamp()) {
    proxy.$appState.elementName = 'Today';
} else if (Number(props.timestamp) !== getCurrentDateTimestamp()) {
    proxy.$appState.elementName = 'Calendar';
}



const form = useValidatedForm({
  curruncy_pair: ["", [isRequired()]],
  buy_value: ["", [isRequired(), isNumeric()]],
  buy_price: ["", [isRequired(), isNumeric()]],
  sell_value: [0, [isNumeric()]],
  sell_price: [0, [isNumeric()]],
  bought_amount: ["", [isRequired(), isNumeric()]],
  position: ["", [isRequired()]],
  pnl: [0, [isNumeric()]],
  editing: [false],
  rowId: [null],
  content: [null],
});

const submit = async () => {
    form.editing = isEditMode.value;
    form.rowId = isEditMode.value ? editRowId.value : null;

    await form.post(
        route('calendar.store', {
            timestamp: props.timestamp
        }),
        {
            onSuccess: () => {
                resetForm();
                if (dt) {
                    dt.ajax.reload(null, false);
                }
            }
        }
    );
};

const saveNote = async (content) => {
  try {
    await axios.post(`/user/calendar/${props.timestamp}/${selectedTradeId.value}/save-note`, { content });
  } catch (error) {
    console.error('Error saving note:', error);
  }
};

const date = computed(() => {
  if (props.timestamp) {

    const timestampNumber = Number(props.timestamp);
    const parsedDate = new Date(timestampNumber);

    if (!isNaN(parsedDate.getTime())) {
      return parsedDate;
    }
  }
  return null;
})

const formattedDate = computed(() => {
  if (date.value) {
    return date.value.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });
  }
  return null;
});


watch(
  [() => form.buy_value, () => form.buy_price],
  ([buy_value, buy_price]) => {
    if (buy_value && buy_price && !isNaN(buy_value) && !isNaN(buy_price)) {
      form.bought_amount = (parseFloat(buy_price) / parseFloat(buy_value)).toFixed(2);
    } else {
      form.bought_amount = 0;
    }
  }
);

watch(
  [() => form.sell_value, () => form.bought_amount],
  ([sell_value, bought_amount]) => {
    if (sell_value && bought_amount && !isNaN(sell_value) && !isNaN(bought_amount)) {
      form.sell_price = (parseFloat(sell_value) * parseFloat(bought_amount)).toFixed(2);
    } else {
      form.sell_price = 0;
    }
  }
);

watch(
  [() => form.sell_price, () => form.buy_price],
  ([sell_price, buy_price]) => {
    if (sell_price && buy_price && !isNaN(sell_price) && !isNaN(buy_price)) {
      form.pnl = (parseFloat(sell_price) - parseFloat(buy_price)).toFixed(2);
    } else {
      form.pnl = 0;
    }
  }
);

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
    }
  },
  { data: 'pnl_percentage' },
  {
    data: 'action',
    searchable: false,
    render: function () {
			return `
				<button title="Edit" class="btn btn-icon-only text-default btn-sm d-inline-flex align-items-center my-0 trade-edit-btn">
					<span class="material-icons-outlined fs-5">edit</span>
				</button>
				<button title="Add Note" class="btn btn-icon-only text-default btn-sm d-inline-flex align-items-center my-0 trade-note-btn">
					<span class="material-icons-outlined fs-5">note_add</span>
				</button>
				<button title="Delete" class="btn btn-icon-only text-danger btn-sm d-inline-flex align-items-center my-0 trade-delete-btn">
					<span class="material-icons-outlined fs-5">delete</span>
				</button>
			`;
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
		url: `${$APP_URL}/user/calendar/${props.timestamp}/get-trades`,
		type: 'POST',
	},
}

const resetForm = () => {
  form.reset()
  isEditMode.value = false
  editRowId.value = null
}

const editRow = (rowData) => {
  form.curruncy_pair = rowData.curruncy_pair;
  form.buy_value = rowData.buy_value;
  form.buy_price = rowData.buy_price;
  form.sell_value = rowData.sell_value;
  form.sell_price = rowData.sell_price;
  form.bought_amount = rowData.bought_amount;
  form.position = rowData.position;
  form.pnl = rowData.pnl;

  isEditMode.value = true;
  editRowId.value = rowData.id;
};

$(document).ready(() => {
	$('#trades-table tbody').on('click', '.trade-edit-btn', function () {
		var data = dt.row($(this).parents('tr')).data()
    editRow(data);
	})
})

$(document).ready(() => {
	$('#trades-table tbody').on('click', '.trade-note-btn', function () {
    var data = dt.row($(this).parents('tr')).data()
    openModal(data.id)
	})
})

$(document).ready(() => {
	$('#trades-table tbody').on('click', '.trade-delete-btn', function () {
		const data = dt.row($(this).parents('tr')).data();
    const tradeId = data.id;
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        axios
          .delete(`/user/calendar/${props.timestamp}/${tradeId}/delete-trade`)
          .then(response => {
            toast.add({ type: 'success', message: response.data.message })
            dt.ajax.reload(null, false);
          })
          .catch(error => {
            console.error("Error deleting trade:", error);
          });
      }
    });
	});
});


onMounted(() => {
	dt = table.value.dt
})


</script>

<template>
	<Head :title="formattedDate" />

  <DashboardLayout>
    <NoteModal
      :visible="isModalVisible"
      :content="quillData"
      :tradeId="selectedTradeId"
      :isLoading="isLoadingNote"
      @close="closeModal"
      @save="saveNote"
    />
		<div class="container-fluid mt-4 mb-4">
			<div class="row">
				<div class="col-12">
					<div class="card border-0 shadow components-section">
						<div class="card-body">
              <div class="mb-4 row">
								<label for="curruncy_pair" class="col-sm-2 col-form-label">Currency Pair</label>
								<div class="col-sm-10">
									<input id="curruncy_pair" v-model="form.curruncy_pair" type="text" class="form-control" :class="{ 'is-invalid': form.errors.curruncy_pair }" placeholder="Curruncy Pair"/>
                  <div v-show="form.errors.curruncy_pair" class="invalid-feedback">{{ form.errors.curruncy_pair }}</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-sm-6 col-md-3">
									<label for="buy_value" class="col-form-label">Buy Value ($)</label>
									<input id="buy_value" v-model="form.buy_value" type="number" class="form-control" :class="{ 'is-invalid': form.errors.buy_value }"  placeholder="Buy Value"/>
                  <div v-show="form.errors.buy_value" class="invalid-feedback">{{ form.errors.buy_value }}</div>
								</div>
								<div class="col-12 col-sm-6 col-md-3">
									<label for="buy_price" class="col-form-label">Buy Price ($)</label>
									<input id="buy_price" v-model="form.buy_price" type="number" class="form-control" :class="{ 'is-invalid': form.errors.buy_price }" placeholder="Buy Price"/>
                  <div v-show="form.errors.buy_price" class="invalid-feedback">{{ form.errors.buy_price }}</div>
								</div>
                <div class="col-12 col-sm-6 col-md-3">
									<label for="sell_value" class="col-form-label">Sell Value ($)</label>
									<input id="sell_value" v-model="form.sell_value" type="number" class="form-control" :class="{ 'is-invalid': form.errors.sell_value }" placeholder="Sell Value"/>
                  <div v-show="form.errors.sell_value" class="invalid-feedback">{{ form.errors.sell_value }}</div>
								</div>
								<div class="col-12 col-sm-6 col-md-3">
									<label for="sell_price" class="col-form-label">Sell Price ($)</label>
									<input id="sell_price" v-model="form.sell_price" type="number" class="form-control" placeholder="Sell Price" readonly/>
								</div>
							</div>
							<div class="row">
                <div class="col-12 col-sm-6 col-md-3">
									<label for="bought_amount" class="col-form-label">Bought Amount</label>
									<input id="bought_amount" v-model="form.bought_amount" type="number" class="form-control" placeholder="Bought Amount" readonly/>
								</div>
								<div class="col-12 col-sm-6 col-md-3">
									<label for="position" class="col-form-label">Position</label>
									<input id="position" v-model="form.position" tpe="text" class="form-control" :class="{ 'is-invalid': form.errors.position }" placeholder="Position"/>
                  <div v-show="form.errors.position" class="invalid-feedback">{{ form.errors.position }}</div>
								</div>
								<div class="col-12 col-sm-6 col-md-3">
									<label for="pnl" class="col-form-label">P & L ($)</label>
									<input id="pnl" v-model="form.pnl" type="text" class="form-control" placeholder="P&L" readonly/>
								</div>
								<div class="col-12 col-sm-6 col-md-3">
									<button :class="['btn', 'w-100', 'add-btn', isEditMode ? 'btn-secondary' : 'btn-primary']" @click.prevent="submit">
                    {{ isEditMode ? 'UPDATE' : 'ADD' }}
                  </button>
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
                    id="trades-table"
                    ref="table"
                    :options="options"
                    :columns="columns"
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
                        <th>Percentage</th>
                        <th>Actions</th>
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
			</div>
		</div>
	</DashboardLayout>
</template>

<style scoped>
  .add-btn {
    margin-top: 2.5rem;
  }
</style>

