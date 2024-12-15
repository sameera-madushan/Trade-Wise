<script setup>
import { ref, getCurrentInstance as instance, onMounted } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useAbility } from '@casl/vue'

const { proxy } = instance()
const { can } = useAbility()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Permissions'

const breadcrumb = [{ label: 'Settings' }, { label: 'Permissions', to: '/admin/settings/permissions' }]

const table = ref()
let dt

onMounted(() => {
	dt = table.value.dt
})

const columns = [
	{ className: 'dt-control', orderable: false, data: null, defaultContent: '' },
	{ data: 'id', visible: false, searchable: false },
	{ data: 'display_name' },
	{ data: 'name' },
	{
		data: 'action',
		searchable: false,
		render: function () {
			return can('EDIT_PERMISSIONS')
				? `<button title="Edit" class="btn btn-icon-only btn-default btn-sm d-inline-flex align-items-center my-0 edit-permission-btn"><span class="material-icons-outlined fs-5">edit</span></button>`
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
		url: `${$APP_URL}/admin/get-permissions`,
		type: 'POST',
	},
}

$(document).ready(() => {
	$('#permission-table tbody').on('click', '.edit-permission-btn', function () {
		var data = undefined
		data = dt.row($(this).parents('tr')).data()
		if (data == undefined) data = $(this).data('id')
		if (typeof data == 'object') data = data.id
		router.get(`/admin/settings/permissions/${data}/edit`)
	})
	function format(d) {
		// `d` is the original data object for the row
		if (d.sub_permissions.length == 0) return ''
		var tbody = ''
		for (var item of d.sub_permissions) {
			tbody += `<tr>
        <td></td>
        <td>${item.display_name}</td>
        <td>${item.name}</td>
        <td>${
					can('EDIT_PERMISSIONS')
						? `<button title="Edit" class="btn btn-icon-only btn-default btn-sm d-inline-flex align-items-center edit-permission-btn" data-id="${item.id}"><span class="material-icons-outlined fs-5">edit</span></button>`
						: ''
				}</td>
        </tr>`
		}
		return '<table class="table table-hover table-text-middle">' + tbody + '</table>'
	}
	$('#permission-table tbody').on('click', 'td.dt-control', function () {
		var tr = $(this).closest('tr')
		var row = dt.row(tr)
		if (row.child.isShown()) {
			// This row is already open - close it
			row.child.hide()
			tr.removeClass('shown')
		} else {
			// Open this row
			row.child(format(row.data())).show()
			tr.addClass('shown')
		}
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
						<PageHeader title="Permission List">
							<div v-if="$can('ADD_PERMISSIONS')" class="d-md-none">
								<a id="mobile-dropdown-button" class="text-gray fw-bold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
									><span class="material-icons align-top">more_vert</span></a
								>
								<div class="dropdown-menu dropdown-menu-end mt-2 py-1" aria-labelledby="mobile-dropdown-button">
									<Link class="dropdown-item" href="/admin/settings/permissions/create">Create</Link>
								</div>
							</div>
							<div class="d-none d-md-block">
								<Link v-if="$can('ADD_PERMISSIONS')" href="/admin/settings/permissions/create" as="button" class="btn btn-tertiary">Create</Link>
							</div>
						</PageHeader>
					</div>
					<div class="flexible-table">
						<div class="py-4">
							<DataTables id="permission-table" ref="table" :options="options" :columns="columns" class="table table-hover table-text-middle pt-4">
								<thead>
									<tr>
										<th></th>
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
