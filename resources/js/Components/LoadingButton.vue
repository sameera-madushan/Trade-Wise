<script setup>
defineProps({
	isLoading: {
		type: Boolean,
		default: false,
	},
	disabled: {
		type: Boolean,
		default: false,
	},
})
</script>

<template>
	<button class="position-relative" :disabled="disabled">
		<svg
			v-show="isLoading"
			class="animate-spin"
			fill="none"
			viewBox="0 0 24 24"
			xmlns="http://www.w3.org/2000/svg"
			style="color: currentColor; width: 1.25rem; height: 1.25rem; position: absolute; left: 50%; top: 50%; margin-left: -0.625rem; margin-top: -0.625rem"
		>
			<circle cx="12" cy="12" r="10" stroke="currentColor" style="opacity: 0.25" stroke-width="4"></circle>
			<path
				d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
				fill="currentColor"
				style="opacity: 0.75"
			></path>
		</svg>
		<span :class="{ invisible: isLoading }">
			<slot />
		</span>
	</button>
</template>
