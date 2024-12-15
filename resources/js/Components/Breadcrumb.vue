<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
	root: {
		type: String,
		required: true,
	},
	items: {
		type: Array,
		required: true,
	},
})
</script>

<template>
	<nav aria-label="breadcrumb" class="d-none d-md-inline-block">
		<ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
			<li class="breadcrumb-item">
				<Link :href="root">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon icon-xxs align-middle" fill="currentColor">
						<path d="M0 0h24v24H0V0z" fill="none" />
						<path d="M12 5.69l5 4.5V18h-2v-6H9v6H7v-7.81l5-4.5M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z" />
					</svg>
				</Link>
			</li>
			<template v-for="(item, index) of items" :key="index">
				<li v-if="item.to == undefined && item.active == undefined" class="breadcrumb-item">{{ item.label }}</li>
				<li v-else-if="$page.url == item.to || item.active == true" class="breadcrumb-item active" aria-current="page">{{ item.label }}</li>
				<li v-else class="breadcrumb-item">
					<Link :href="item.to">{{ item.label }}</Link>
				</li>
			</template>
		</ol>
	</nav>
</template>
