<script setup>
import { getCurrentInstance as instance, ref, onMounted } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useAbility } from '@casl/vue'
import { toast } from '@/stores'

const { proxy } = instance()
const { can } = useAbility()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Roles'

const breadcrumb = [{ label: 'Settings' }, { label: 'Roles', to: '/admin/settings/roles' }]

const table = ref()
let dt

onMounted(() => {
	dt = table.value.dt
})

const columns = [
	{ data: 'id', visible: false, searchable: false },
	{ data: 'display_name' },
	{ data: 'name' },
	{
		data: 'action',
		searchable: false,
		render: function () {
			var buttons = can('EDIT_ROLES')
				? `<button title="Edit" class="btn btn-icon-only text-default btn-sm d-inline-flex align-items-center my-0 edit-role-btn"><span class="material-icons-outlined fs-5">edit</span></button>`
				: ''
			buttons += can('DELETE_ROLES')
				? `<button title="Delete" class="btn btn-icon-only text-danger btn-sm d-inline-flex align-items-center my-0 ms-2 delete-role-btn"><span class="material-icons-outlined fs-5">delete</span></button>`
				: ''
			return buttons
		},
	},
]
const options = {
	ordering: true,
	order: [[0, 'desc']],
	processing: true,
	serverSide: true,
	responsive: true,
	autoWidth: false,
	ajax: {
		url: `${$APP_URL}/admin/get-roles`,
		type: 'POST',
	},
}

async function deleteRole(id) {
	var response = await $axios.delete(`/admin/settings/roles/${id}`)
	if (response.status == 200) {
		toast.add({ type: response.data.status, message: response.data.message })
		dt.ajax.reload(null, false)
	} else if (response.status == 403) {
		toast.add({ type: 'warn', message: response.data.message })
	} else {
		toast.add({ type: 'error', message: response.data.message })
	}
}

$(document).ready(() => {
	$('#role-table tbody').on('click', '.edit-role-btn', function () {
		var data = dt.row($(this).parents('tr')).data()
		router.get(`/admin/settings/roles/${data.id}/edit`)
	})
	$('#role-table tbody').on('click', '.delete-role-btn', async function () {
		var data = dt.row($(this).parents('tr')).data()
		if (confirm('Are you sure you want to delete?')) {
			await deleteRole(data.id)
		}
	})
})
</script>

<template>
	<Head title="Role List" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :breadcrumb="breadcrumb" title="Role List">
							<Link href="/admin/settings/roles/create" as="button" class="btn btn-tertiary my-0">Create</Link>
						</PageHeader>
					</div>
					<div class="flexible-table">
						<div class="py-4">
							<DataTables id="role-table" ref="table" :options="options" :columns="columns" class="table table-hover table-text-middle pt-4">
								<thead>
									<tr>
										<th>ID</th>
										<th>Display Name</th>
										<th>Name</th>
										<th>Action</th>
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
