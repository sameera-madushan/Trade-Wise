<script setup>
import { getCurrentInstance as instance, computed, defineProps, provide, defineAsyncComponent  } from 'vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import { Head, Link } from '@inertiajs/vue3'

const { proxy } = instance()

proxy.$appState.parentSelection = 'PageExamples'
proxy.$appState.elementName = 'MyProfile'

const props = defineProps({
	mustVerifyEmail: Boolean,
	status: {
		type: String,
		default: null,
	},
  uuid: {
    type: String,
    default: null,
  }
})

const dashboardUrl = computed(() => (props.uuid ? `/user/${props.uuid}` : '/admin'))

const DashboardLayout = computed(() => {
	return props.uuid
		? defineAsyncComponent(() => import('@/Layouts/User.vue'))
		: defineAsyncComponent(() => import('@/Layouts/Admin.vue'))
})

</script>

<template>
	<Head title="My Profile" />

	<component :is="DashboardLayout">
		<div class="row my-4">
			<div class="col-12 col-xl-12">
				<Breadcrumb :root="dashboardUrl" />
				<PageHeader title="My Profile" class="mb-4">
					<Link :href="dashboardUrl" as="button" class="btn btn-tertiary">Go to dashboard</Link>
				</PageHeader>
				<div class="card border-0 shadow components-section mb-4">
					<div class="card-body">
						<UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" :uuid="props.uuid"/>
					</div>
				</div>
				<div class="card border-0 shadow components-section mb-4">
					<div class="card-body">
						<UpdatePasswordForm />
					</div>
				</div>
				<div class="card border-0 shadow components-section">
					<div class="card-body">
						<DeleteUserForm :uuid="props.uuid"/>
					</div>
				</div>
			</div>
		</div>
	</component>
</template>
