<script setup>
import { getCurrentInstance as instance, ref, onMounted, computed } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import StatusCard from '@/Components/Widgets/StatusCard.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import TreeSelect from '@/Components/TreeSelect.vue'
import { isRequired } from 'intus/rules'
import { useValidatedForm } from '@/Composables'

const { proxy } = instance()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Roles'

const breadcrumb = [{ label: 'Settings' }, { label: 'Roles', to: '/admin/settings/roles' }, { label: 'Create', to: '/admin/settings/roles/create' }]

const { props } = usePage()

const selectedPermissions = ref([])
const permissions = ref([])

let hasId = computed(() => {
	return Object.hasOwn(route().params, 'role')
})

const form = useValidatedForm({
	name: [null, [isRequired()]],
	display_name: [null, []],
	permissions: [[], []],
})

function setFieldValues(data) {
	form.name = data.name
	form.display_name = data.display_name
	form.parent_id = data.parent_id
	setPermissions(data.permissions)
}

async function getPermissions() {
	const response = await $axios.get('/admin/get-permissions', { params: { children: 1, parent_only: true } })
	if (response.status === 200) {
		permissions.value = response.data.data
	}
}

async function setPermissions(permissions) {
	let data = permissions.reduce((arr, item) => {
		arr.push(item.id)
		return arr
	}, [])
	selectedPermissions.value = data
}

onMounted(async () => {
	await getPermissions()
	if (hasId.value) setFieldValues(props?.role)
})

const submit = () => {
	if (selectedPermissions.value.length != 0) {
		form.permissions = selectedPermissions.value.join(',')
	}
	if (hasId.value) {
		form.put(route('roles.update', { role: props?.role.id }), {
			onSuccess: () => form.reset(),
		})
	} else {
		form.post(route('roles.store'), {
			onSuccess: () => form.reset(),
		})
	}
}
</script>

<template>
	<Head :title="hasId ? `Edit Role` : `Create Role`" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12 col-xl-9">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :breadcrumb="breadcrumb" :title="hasId ? `Edit Role` : `Create Role`">
							<Link href="/admin/settings/roles" as="button" class="btn btn-gray-200 my-0">Go Back</Link>
						</PageHeader>
					</div>
					<div class="card-body">
						<form @submit.prevent="submit">
							<div class="mb-3 row">
								<label for="name" class="col-sm-3 col-form-label">Name</label>
								<div class="col-sm-9">
									<input id="name" v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }" />
									<div v-show="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
								</div>
							</div>
							<div class="mb-3 row">
								<label for="display-name" class="col-sm-3 col-form-label">Display Name</label>
								<div class="col-sm-9">
									<input id="display-name" v-model="form.display_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.display_name }" />
									<div v-show="form.errors.display_name" class="invalid-feedback">{{ form.errors.display_name }}</div>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label">Permissions</label>
								<div class="col-sm-9">
									<TreeSelect v-model="selectedPermissions" :data="permissions" :selected="selectedPermissions" />
								</div>
							</div>
							<div class="col-sm-9 offset-sm-3 d-flex align-items-center justify-content-end">
								<LoadingButton type="submit" class="btn btn-primary ms-auto" :is-loading="form.processing" :disabled="form.processing"
									>Save Changes</LoadingButton
								>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="d-none d-xl-block col-xl-3">
				<StatusCard :summary="$page.props?.model_summary" />
			</div>
		</div>
	</DashboardLayout>
</template>
