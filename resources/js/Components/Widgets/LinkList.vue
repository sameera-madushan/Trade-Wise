<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
	title: {
		type: String,
		default: 'Related links',
	},
	items: {
		type: Array,
		required: true,
	},
})
</script>

<template>
	<div class="card">
		<div class="card-body">
			<div class="border-bottom border-gray-200 pb-3 w-100">
				<h6 class="fw-bold text-gray-500 fs-6 mb-0 text-truncate"><span class="material-icons align-middle">link</span> {{ title }}</h6>
			</div>
			<ul class="list-unstyled mt-2">
				<li v-for="(item, index) in items" :key="index" class="fs-6 text-truncate">
					<small>
						<Link :href="item.to" class="link-gray-500">{{ item.label }}</Link>
					</small>
					<ul v-if="item.hasOwnProperty('items')" class="ps-3 list-unstyled">
						<li v-for="(subItem, idx) in item.items" :key="idx" class="fs-6 text-truncate">
							<small>
								<Link :href="subItem.to" class="link-gray-500">{{ subItem.label }}</Link>
							</small>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</template>
