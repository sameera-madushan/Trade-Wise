<script setup>
import { getCurrentInstance as instance, onMounted, computed } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import LinkList from '@/Components/Widgets/LinkList.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import StatusCard from '@/Components/Widgets/StatusCard.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { isRequired } from 'intus/rules'
import { useValidatedForm } from '@/Composables'

const { proxy } = instance()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Configurations'

const breadcrumb = [
	{ label: 'Settings' },
	{ label: 'Configuration', to: '/admin/settings/configurations' },
	{ label: 'Category', to: '/admin/settings/config-categories' },
	{ label: 'Create', to: '/admin/settings/config-categories/create' },
]

const linkList = [
	{ label: 'Dashboard', to: '/admin' },
	{ label: 'Users', to: '/admin/settings/users' },
	{ label: 'Configurations', to: '/admin/settings/configurations', items: [{ label: 'Create', to: '/admin/settings/configurations/create' }] },
]

const { props } = usePage()

let hasId = computed(() => {
	return Object.hasOwn(route().params, 'config_category')
})

const form = useValidatedForm({
	name: [null, [isRequired()]],
	description: [null, []],
	status: [true, []],
})

function setFieldValues(data) {
	form.name = data.name
	form.description = data.description
	form.status = data.status == '1' ? true : false
}

async function submit() {
	if (hasId.value) {
		form.put(route('config-categories.update', { config_category: props?.configcategory.id }), {
			onSuccess: () => form.reset(),
		})
	} else {
		form.post(route('config-categories.store'), {
			onSuccess: () => form.reset(),
		})
	}
}

onMounted(() => {
	if (hasId.value) setFieldValues(props?.configcategory)
})
</script>

<template>
	<Head :title="hasId ? 'Edit Configuration Category' : `Create Configuration Category`" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12 col-xl-9">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :breadcrumb="breadcrumb" :title="hasId ? 'Edit Configuration Category' : `Create Configuration Category`">
							<Link href="/admin/settings/config-categories" as="button" class="btn btn-gray-200 my-0">Go Back</Link>
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
								<label for="description" class="col-sm-3 col-form-label">Description</label>
								<div class="col-sm-9">
									<textarea
										id="description"
										v-model="form.description"
										class="form-control"
										rows="3"
										:class="{ 'is-invalid': form.errors.description }"
									></textarea>
									<div v-show="form.errors.description" class="invalid-feedback">{{ form.errors.description }}</div>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="status" class="col-3 col-form-label">Status</label>
								<div class="col-9 d-flex align-items-center">
									<div class="form-check form-switch">
										<input id="status" v-model="form.status" class="form-check-input" type="checkbox" />
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
				<LinkList :items="linkList" class="mb-4" />
				<StatusCard :summary="$page.props?.model_summary" />
			</div>
		</div>
	</DashboardLayout>
</template>
