<script setup>
import { getCurrentInstance as instance, computed, onMounted, ref, watch } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import PageHeader from '@/Components/PageHeader.vue'
import LinkList from '@/Components/Widgets/LinkList.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import StatusCard from '@/Components/Widgets/StatusCard.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import Choices from 'choices.js'
import { isRequired, isIn } from 'intus/rules'
import { useValidatedForm } from '@/Composables'

const { proxy } = instance()

proxy.$appState.parentSelection = 'Settings'
proxy.$appState.elementName = 'Configurations'

const breadcrumb = [
	{ label: 'Settings' },
	{ label: 'Configuration', to: '/admin/settings/configurations' },
	{ label: 'Create', to: '/admin/settings/configurations/create' },
]

const linkList = [
	{ label: 'Dashboard', to: '/admin' },
	{ label: 'Users', to: '/admin/settings/users' },
	{ label: 'Configuration Category', to: '/admin/settings/config-categories', items: [{ label: 'Create', to: '/admin/settings/config-categories/create' }] },
]

const { props } = usePage()

const showKeyValuePairInputs = ref(false)
const keyValuePairs = ref([{ key: null, value: null }])
const choices = ref(null)

let hasId = computed(() => {
	return Object.hasOwn(route().params, 'configuration')
})

let configCategories = computed(() => {
	return props.config_categories.reduce((arr, item, index) => {
		if (index == 0) {
			arr.push({
				value: '',
				label: 'Select a Configuration Category',
				selected: props?.configuration?.category_id == null,
				disabled: props?.configuration?.category_id == null,
			})
		}
		arr.push({
			value: item.id,
			label: item.name,
			selected: props?.configuration?.category_id == item.id,
		})
		return arr
	}, [])
})

const configTypes = ref([
	{ name: 'Select Configuration Type', value: null, disabled: true, selected: true },
	{ name: 'Text', value: 'TX' },
	{ name: 'Dropdown', value: 'DD' },
])

const form = useValidatedForm({
	name: [null, [isRequired()]],
	display_name: [null, [isRequired()]],
	config_type: [null, [isRequired(), isIn('TX', 'DD')]],
	value: [null, []],
	key_array: [null, []],
	value_array: [null, []],
	category_id: ['', [isRequired()]],
	status: [true, []],
})

function setFieldValues(data) {
	form.name = data.name
	form.display_name = data.display_name
	form.config_type = data.config_type
	form.value = data.value
	form.category_id = data.category_id
	form.status = data.status == '1' ? true : false
	setKeyValuePairs(data.options_array)
}

function clearSelectValue() {
	form.config_type = null
}

function clearChoicesSelectValue() {
	choices.value.setChoiceByValue('')
	form.category_id = ''
}

watch(
	() => form.config_type,
	async (newType) => {
		newType == 'DD' ? (showKeyValuePairInputs.value = true) : (showKeyValuePairInputs.value = false)
	}
)

function setKeyValuePairs(pairArray) {
	keyValuePairs.value = Object.entries(pairArray).map((item) => {
		return { key: item[0], value: item[1] }
	})
}

function setKeysArray() {
	form.key_array = keyValuePairs.value.reduce((arr, item) => {
		arr.push(item.key)
		return arr
	}, [])
}

function setValuesArray() {
	form.value_array = keyValuePairs.value.reduce((arr, item) => {
		arr.push(item.value)
		return arr
	}, [])
}

onMounted(async () => {
	const ele = document.getElementById('config-category')
	choices.value = new Choices(ele, { choices: configCategories.value, searchEnabled: true })

	if (hasId.value) setFieldValues(props?.configuration)
})

async function submit() {
	if (keyValuePairs.value.length != 0) {
		setKeysArray()
		setValuesArray()
	}

	if (hasId.value) {
		form.put(route('configurations.update', { configuration: props?.configuration.id }), {
			onSuccess: () => form.reset(),
		})
	} else {
		form.post(route('configurations.store'), {
			onSuccess: () => form.reset(),
		})
	}
}
</script>

<template>
	<Head :title="hasId ? `Edit Configuration` : `Create Configuration`" />

	<DashboardLayout>
		<div class="mt-4">
			<Breadcrumb root="/admin" :items="breadcrumb" />
		</div>
		<div class="row mb-4">
			<div class="col-12 col-xl-9">
				<div class="card border-0 shadow components-section">
					<div class="card-header">
						<PageHeader :breadcrumb="breadcrumb" :title="hasId ? `Edit Configuration` : `Create Configuration`">
							<Link href="/admin/settings/configurations" as="button" class="btn btn-gray-200 my-0">Go Back</Link>
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

							<div class="row mb-3">
								<label for="config-type" class="col-sm-3 col-form-label">Configuration Type</label>
								<div class="col-sm-9">
									<select id="config-type" v-model="form.config_type" class="form-control" :class="{ 'is-invalid': form.errors.config_type }">
										<option v-for="(item, index) in configTypes" :key="index" :value="item.value" :disabled="item?.disabled" :selected="item?.selected">
											{{ item.name }}
										</option>
									</select>
									<div class="text-end">
										<a href="#" class="text-tertiary fs-6" @click.prevent="clearSelectValue()"><small>Clear</small></a>
									</div>
									<div v-show="form.errors.config_type" class="invalid-feedback">{{ form.errors.config_type }}</div>
								</div>
							</div>

							<div class="row mb-3">
								<label for="config-category" class="col-sm-3 col-form-label">Category</label>
								<div class="col-sm-9">
									<select id="config-category" v-model="form.category_id"></select>
									<div class="text-end">
										<a href="#" class="text-tertiary fs-6" @click.prevent="clearChoicesSelectValue()"><small>Clear</small></a>
									</div>
									<div v-show="form.errors.category_id" class="field-invalid">{{ form.errors.category_id }}</div>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="default-value" class="col-sm-3 col-form-label">Default Value</label>
								<div class="col-sm-9">
									<input id="default-value" v-model="form.value" type="text" class="form-control" :class="{ 'is-invalid': form.errors.value }" />
									<div v-show="form.errors.value" class="invalid-feedback">{{ form.errors.value }}</div>
								</div>
							</div>

							<div v-if="showKeyValuePairInputs" class="mb-3 row">
								<label for="state" class="col-sm-3 col-form-label">Dropdown options</label>
								<div class="col-sm-9">
									<template v-for="(pair, index) in keyValuePairs" :key="index">
										<div v-if="index == 0" class="row">
											<div class="col-5">
												<input v-model="pair.key" type="text" class="form-control form-control-sm" />
											</div>
											<div class="col-5">
												<input v-model="pair.value" type="text" class="form-control form-control-sm" />
											</div>
											<div class="col-2">
												<button type="button" class="btn btn-sm btn-success my-0" @click.prevent="keyValuePairs.push({ key: null, value: null })">ADD</button>
											</div>
										</div>
										<div v-else class="mt-3 row">
											<div class="col-5">
												<input v-model="pair.key" type="text" class="form-control form-control-sm" />
											</div>
											<div class="col-5">
												<input v-model="pair.value" type="text" class="form-control form-control-sm" />
											</div>
											<div class="col-2">
												<button
													type="button"
													class="btn btn-sm btn-danger my-0"
													@click.prevent="keyValuePairs = keyValuePairs.filter((item, idx) => idx != index)"
												>
													REMOVE
												</button>
											</div>
										</div>
									</template>
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
