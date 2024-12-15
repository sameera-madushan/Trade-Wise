<script setup>
import { onMounted, getCurrentInstance as instance, computed, ref } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import StatusCard from '@/Components/Widgets/StatusCard.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import Choices from 'choices.js'
import { isRequired } from 'intus/rules'
import { useValidatedForm } from '@/Composables'

const { proxy } = instance()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Permissions'

let hasId = computed(() => {
	return Object.hasOwn(route().params, 'permission')
})

const choices = ref(null)

let parentPermissions = computed(() => {
	return props.parent_permissions.reduce((arr, item, index) => {
		if (index == 0) {
			arr.push({
				value: 'null',
				label: 'Select a Parent Permission',
				selected: props?.permission?.parent_id == null,
				disabled: props?.permission?.parent_id == null,
			})
		}
		arr.push({
			value: item.id,
			label: item.display_name,
			selected: props?.permission?.parent_id == item.id,
		})
		return arr
	}, [])
})

const breadcrumb = [
	{ label: 'Settings' },
	{ label: 'Permissions', to: '/admin/settings/permissions' },
	{ label: hasId.value ? `Edit` : `Create`, active: true },
]

const form = useValidatedForm({
	name: [null, [isRequired()]],
	display_name: [null, []],
	parent_id: [null, []],
})

const { props } = usePage()

function setFieldValues(data) {
	form.name = data.name
	form.display_name = data.display_name
	form.parent_id = data.parent_id
}

onMounted(() => {
	const ele = document.getElementById('parent-permission')
	choices.value = new Choices(ele, { choices: parentPermissions.value, searchEnabled: true })

	if (hasId.value) setFieldValues(props?.permission)
})

const submit = () => {
	if (hasId.value) {
		form.put(route('permissions.update', { permission: props?.permission.id }), {
			onSuccess: () => form.reset(),
		})
	} else {
		form.post(route('permissions.store'), {
			onSuccess: () => form.reset(),
		})
	}
}

function clearSelectValue() {
	choices.value.setChoiceByValue('null')
	form.parent_id = 'null'
}
</script>

<template>
	<Head :title="hasId ? `Edit Permission` : `Create Permission`" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12 col-xl-9">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :title="hasId ? `Edit Permission` : `Create Permission`">
							<Link href="/admin/settings/permissions" as="button" class="btn btn-gray-200 my-0">Go Back</Link>
						</PageHeader>
					</div>
					<div class="card-body">
						<form @submit.prevent="submit">
							<div class="row mb-3">
								<label for="permission-name" class="col-sm-3 col-form-label">Name</label>
								<div class="col-sm-9">
									<input id="permission-name" v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }" />
									<div v-show="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
								</div>
							</div>

							<div class="row mb-3">
								<label for="display-name" class="col-sm-3 col-form-label">Display Name</label>
								<div class="col-sm-9">
									<input id="display-name" v-model="form.display_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.display_name }" />
									<div v-show="form.errors.display_name" class="invalid-feedback">{{ form.errors.display_name }}</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="parent-permission" class="col-sm-3 col-form-label">Parent Permission</label>
								<div class="col-sm-9">
									<select id="parent-permission" v-model="form.parent_id"></select>
									<div class="text-end">
										<a href="#" class="text-tertiary fs-6" @click.prevent="clearSelectValue()"><small>Clear</small></a>
									</div>
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
