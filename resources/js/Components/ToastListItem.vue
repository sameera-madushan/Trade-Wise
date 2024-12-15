<script setup>
import { onMounted } from 'vue'

const props = defineProps({
	message: {
		type: String,
		required: true,
	},
	type: {
		type: String,
		required: true,
	},
})

const emits = defineEmits(['remove'])

onMounted(() => {
	setTimeout(() => emits('remove'), 3000)
})
</script>

<template>
	<div class="toast align-items-center bg-white text-default border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="d-flex">
			<div class="toast-body">
				<div class="d-flex gap-3">
					<div>
						<div
							class="icon-shape icon-xs rounded"
							:class="{ 'icon-shape-success': props.type == 'success', 'icon-shape-danger': props.type == 'error', 'icon-shape-warning': props.type == 'warn' }"
						>
							<span v-if="props.type == 'success'" class="material-icons-outlined text-current">done</span>
							<span v-else-if="props.type == 'error'" class="material-icons-outlined text-current">question_mark</span>
							<span v-else-if="props.type == 'warn'" class="material-icons-outlined text-current">report_problem</span>
						</div>
					</div>
					<div>
						<strong>{{ props.type == 'success' ? 'Success' : props.type == 'error' ? 'Error' : props.type == 'warn' ? 'Warning' : '' }}</strong
						><br />
						<small>{{ props.message }}</small>
					</div>
				</div>
			</div>
			<div class="me-2 ms-auto">
				<button type="button" class="btn-close btn-close-default" aria-label="Close" @click="emits('remove')"></button>
			</div>
		</div>
	</div>
</template>
