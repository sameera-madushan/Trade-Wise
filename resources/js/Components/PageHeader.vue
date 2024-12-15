<script setup>
defineProps({
	title: {
		type: String,
		required: true,
	},
})
</script>

<template>
	<div class="d-flex flex-row justify-content-between align-items-center w-100 flex-wrap">
		<div class="mb-1 mb-lg-0">
			<h1 class="h4 mb-0">{{ title }}</h1>
		</div>
		<div>
			<slot />
		</div>
	</div>
</template>
