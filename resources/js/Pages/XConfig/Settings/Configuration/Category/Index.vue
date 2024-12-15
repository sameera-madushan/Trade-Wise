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
proxy.$appState.elementName = 'Configurations'

const breadcrumb = [
	{ label: 'Settings' },
	{ label: 'Configuration', to: '/admin/settings/configurations' },
	{ label: 'Category', to: '/admin/settings/config-categories' },
]

const table = ref()
let dt

onMounted(() => {
	dt = table.value.dt
})

const columns = [
	{ data: 'id', visible: false, searchable: false },
	{ data: 'name' },
	{ data: 'description' },
	{
		data: 'action',
		searchable: false,
		render: function () {
			return can('EDIT_CONFIGURATION_CATEGORIES')
				? `<button title="Edit" class="btn btn-icon-only text-default btn-sm d-inline-flex align-items-center my-0 edit-config-category-btn"><span class="material-icons-outlined fs-5">edit</span></button>`
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
		url: `${$APP_URL}/admin/settings/get-config-categories`,
		type: 'POST',
	},
}

$(document).ready(() => {
	$('#config-category-table tbody').on('click', '.edit-config-category-btn', function () {
		var data = dt.row($(this).parents('tr')).data()
		router.get(`/admin/settings/config-categories/${data.id}/edit`)
	})
})
</script>

<template>
	<Head title="Category List" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12">
				<div class="card border-0 shadow">
					<div class="card-header">
						<PageHeader title="Configuration Category List">
							<div v-if="$can('ADD_CONFIGURATION_CATEGORIES')" class="d-md-none">
								<a id="mobile-dropdown-button" class="text-gray fw-bold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
									><span class="material-icons align-top">more_vert</span></a
								>
								<div class="dropdown-menu dropdown-menu-end mt-2 py-1" aria-labelledby="mobile-dropdown-button">
									<Link class="dropdown-item" href="/admin/settings/config-categories/create">Create</Link>
								</div>
							</div>
							<div class="d-none d-md-block">
								<Link v-if="$can('ADD_CONFIGURATION_CATEGORIES')" href="/admin/settings/config-categories/create" as="button" class="btn btn-tertiary my-0"
									>Create</Link
								>
							</div>
						</PageHeader>
					</div>
					<div class="flexible-table">
						<div class="py-4">
							<DataTables
								id="config-category-table"
								ref="table"
								:options="options"
								:columns="columns"
								class="table table-hover table-text-middle pt-4"
								width="100%"
							>
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Description</th>
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
