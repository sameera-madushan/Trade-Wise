<script setup>
import { getCurrentInstance as instance, ref, onMounted } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useAbility } from '@casl/vue'

const { proxy } = instance()
const { can } = useAbility()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Configurations'

const breadcrumb = [{ label: 'Settings' }, { label: 'Configuration', to: '/admin/settings/configurations' }]

const table = ref()
let dt

onMounted(() => {
	dt = table.value.dt
})

const columns = [
	{ data: 'id', visible: false, searchable: false },
	{ data: 'display_name' },
	{ data: 'name' },
	{ data: 'category', searchable: false },
	{
		data: 'status',
		render: function (data) {
			return data == '1' ? `<span class="badge bg-success">Active</span>` : `<span class="badge bg-danger">Inactive</span>`
		},
	},
	{
		data: 'action',
		searchable: false,
		render: function () {
			return can('EDIT_CONFIGURATIONS')
				? `<button title="Edit" class="btn btn-icon-only text-default btn-sm d-inline-flex align-items-center my-0 edit-config-btn"><span class="material-icons-outlined fs-5">edit</span></button>`
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
		url: `${$APP_URL}/admin/settings/get-config`,
		type: 'POST',
	},
}

$(document).ready(() => {
	$('#configuration-table tbody').on('click', '.edit-config-btn', function () {
		var data = dt.row($(this).parents('tr')).data()
		router.get(`/admin/settings/configurations/${data.id}/edit`)
	})
})
</script>

<template>
	<Head title="Configuration List" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :breadcrumb="breadcrumb" title="Configuration List">
							<div
								v-if="
									$can('MANAGE_CONFIGURATION') && $can('ADD_CONFIGURATIONS') && $can('MANAGE_CONFIGURATION_CATEGORIES') && $can('ADD_CONFIGURATION_CATEGORIES')
								"
								class="d-md-none"
							>
								<a id="mobile-dropdown-button" class="text-gray fw-bold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
									><span class="material-icons align-top">more_vert</span></a
								>
								<div class="dropdown-menu dropdown-menu-end mt-2 py-1" aria-labelledby="mobile-dropdown-button">
									<Link class="dropdown-item" href="/admin/settings/config-categories">Category</Link>
									<Link class="dropdown-item" href="/admin/settings/configurations/create">Create</Link>
								</div>
							</div>
							<div class="d-none d-md-block">
								<Link
									v-if="$can('MANAGE_CONFIGURATION_CATEGORIES') && $can('ADD_CONFIGURATION_CATEGORIES')"
									href="/admin/settings/config-categories"
									as="button"
									class="btn btn-white my-0"
									>Category</Link
								>
								<Link
									v-if="$can('MANAGE_CONFIGURATION') && $can('ADD_CONFIGURATIONS')"
									href="/admin/settings/configurations/create"
									as="button"
									class="btn btn-tertiary my-0 ms-3"
									>Create</Link
								>
							</div>
						</PageHeader>
					</div>
					<div class="flexible-table">
						<div class="py-4">
							<DataTables
								id="configuration-table"
								ref="table"
								:options="options"
								:columns="columns"
								class="table table-hover table-text-middle pt-4"
								width="100%"
							>
								<thead>
									<tr>
										<th>ID</th>
										<th>Display Name</th>
										<th>Name</th>
										<th>Category</th>
										<th>Status</th>
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
