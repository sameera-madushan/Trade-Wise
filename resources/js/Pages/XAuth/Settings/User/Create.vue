<script setup>
import { onMounted, getCurrentInstance as instance, computed, ref } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import StatusCard from '@/Components/Widgets/StatusCard.vue'
import { Head, usePage } from '@inertiajs/vue3'
import Choices from 'choices.js'
import { isRequired } from 'intus/rules'
import { useValidatedForm } from '@/Composables'

const { proxy } = instance()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Users'

let hasId = computed(() => {
	return Object.hasOwn(route().params, 'user')
})

const choices = ref(null)

let roles = computed(() => {
	return props.roles.reduce((arr, item) => {
		arr.push({
			value: item.name,
			label: item.display_name,
			selected: props?.user?.user_roles.includes(item.name),
		})
		return arr
	}, [])
})

const breadcrumb = [{ label: 'Settings' }, { label: 'Users', to: '/admin/settings/users' }, { label: hasId.value ? `Edit` : `Create`, active: true }]

const form = useValidatedForm({
	name: [null, [isRequired()]],
	email: [null, [isRequired()]],
	password: [null, []],
	password_confirmation: [null, []],
	roles: [null, []],
})

const { props } = usePage()

function setFieldValues(data) {
	form.name = data.name
	form.email = data.email
	form.password = data.password
}

onMounted(() => {
	const ele = document.getElementById('user-roles')
	choices.value = new Choices(ele, {
		choices: roles.value,
		searchEnabled: true,
		removeItems: true,
		removeItemButton: true,
	})
	if (hasId.value) setFieldValues(props?.user)
})

const submit = () => {
	if (hasId.value) {
		form.put(route('users.update', { user: props?.user.id }), {
			onSuccess: () => form.reset(),
		})
	} else {
		form.post(route('users.store'), {
			onSuccess: () => form.reset(),
		})
	}
}
</script>

<template>
	<Head :title="hasId ? `Edit User` : `Create User`" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12 col-xl-9">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :title="hasId ? `Edit User` : `Create User`" />
					</div>
					<div class="card-body">
						<form @submit.prevent="submit">
							<div class="row mb-3">
								<label for="user-name" class="col-sm-3 col-form-label">Name</label>
								<div class="col-sm-9">
									<input id="user-name" v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }" />
									<div v-show="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
								</div>
							</div>

							<div class="row mb-3">
								<label for="user-email" class="col-sm-3 col-form-label">Email</label>
								<div class="col-sm-9">
									<input id="user-email" v-model="form.email" type="email" class="form-control" :class="{ 'is-invalid': form.errors.email }" />
									<div v-show="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="user-password" class="col-sm-3 col-form-label">Password</label>
								<div class="col-sm-9">
									<input id="user-password" v-model="form.password" type="password" class="form-control" :class="{ 'is-invalid': form.errors.password }" />
									<div v-show="form.errors.password" class="invalid-feedback">{{ form.errors.password }}</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="user-password-confirmation" class="col-sm-3 col-form-label">Confirm Password</label>
								<div class="col-sm-9">
									<input
										id="user-password-confirmation"
										v-model="form.password_confirmation"
										type="password"
										class="form-control"
										:class="{ 'is-invalid': form.errors.password_confirmation }"
									/>
									<div v-show="form.errors.password_confirmation" class="invalid-feedback">{{ form.errors.password_confirmation }}</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="user-roles" class="col-sm-3 col-form-label">Role</label>
								<div class="col-sm-9">
									<select id="user-roles" v-model="form.roles" multiple class="form-control"></select>
									<div v-show="form.errors.roles" class="invalid-feedback">{{ form.errors.roles }}</div>
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
