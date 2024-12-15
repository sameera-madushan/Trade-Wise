<script setup>
import { ref, onMounted, getCurrentInstance as instance } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useAbility } from '@casl/vue'

const { proxy } = instance()
const { can } = useAbility()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Users'

const breadcrumb = [{ label: 'Settings' }, { label: 'Users', to: '/admin/settings/users' }]

const table = ref()
let dt

onMounted(() => {
	dt = table.value.dt
})

const columns = [
	{ data: 'id', visible: false, searchable: false },
	{ data: 'name' },
	{ data: 'email' },
	{
		data: 'action',
		searchable: false,
		render: function () {
			return can('EDIT_USERS')
				? `<button title="Edit" class="btn btn-icon-only text-default btn-sm d-inline-flex align-items-center my-0 edit-users-btn"><span class="material-icons-outlined fs-5">edit</span></button>`
				: ''
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
		url: `${$APP_URL}/admin/get-users`,
		type: 'POST',
	},
}

$(document).ready(() => {
	$('#users-table tbody').on('click', '.edit-users-btn', function () {
		var data = undefined
		data = dt.row($(this).parents('tr')).data()
		if (data == undefined) data = $(this).data('id')
		if (typeof data == 'object') data = data.id
		router.get(`/admin/settings/users/${data}/edit`)
	})
})
</script>

<template>
	<Head title="Permission List" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12">
				<div class="card border-0 shadow">
					<div class="card-header">
						<PageHeader title="Users List">
							<div v-if="$can('ADD_USERS')" class="d-md-none">
								<a id="mobile-dropdown-button" class="text-gray fw-bold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
									><span class="material-icons align-top">more_vert</span></a
								>
								<div class="dropdown-menu dropdown-menu-end mt-2 py-1" aria-labelledby="mobile-dropdown-button">
									<Link class="dropdown-item" href="/admin/settings/users/create">Create</Link>
								</div>
							</div>
							<div class="d-none d-md-block">
								<Link v-if="$can('ADD_USERS')" href="/admin/settings/users/create" as="button" class="btn btn-tertiary">Create</Link>
							</div>
						</PageHeader>
					</div>
					<div class="flexible-table">
						<div class="py-4">
							<DataTables id="users-table" ref="table" :options="options" :columns="columns" class="table table-hover table-text-middle pt-4">
								<thead>
									<tr>
										<th></th>
										<th>Name</th>
										<th>Email</th>
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
