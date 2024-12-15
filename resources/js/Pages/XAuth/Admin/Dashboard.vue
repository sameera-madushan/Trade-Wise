<script setup>
import { getCurrentInstance as instance } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import { Head } from '@inertiajs/vue3'

const { proxy } = instance()

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Dashboard'
</script>

<template>
	<Head title="Dashboard" />

	<DashboardLayout>
		<div class="row mt-4 mb-4">
			<div class="col-12 col-xl-9">
				<div class="card border-0 shadow components-section">
					<div class="card-body"></div>
				</div>
			</div>
			<div class="d-none d-xl-block col-xl-3">
				<div class="card border-0 shadow components-section">
					<div class="card-body"></div>
				</div>
			</div>
		</div>
	</DashboardLayout>
</template>
