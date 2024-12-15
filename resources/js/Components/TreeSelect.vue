<script setup>
import { ref, watch } from 'vue'

const emit = defineEmits(['update:modelValue'])

const props = defineProps({
	data: {
		type: Array,
		default: () => [],
		required: true,
	},
	modalValue: {
		type: Array,
		default: () => [],
	},
	selected: {
		type: Array,
		default: () => [],
	},
})

const selectedItems = ref([])

watch(
	() => props.selected,
	(newVal) => {
		if (newVal.length !== 0) {
			selectedItems.value = newVal
		}
	}
)

watch(selectedItems, (newVal) => {
	emit('update:modelValue', newVal)
})

function expand(e, key) {
	const childList = document.querySelector(`[data-child-list="child-list-${key}"]`)
	if (!childList.classList.contains('expanded') && !e.target.classList.contains('expanded')) {
		childList.classList.add('expanded')
		e.target.classList.add('expanded')
	} else {
		childList.classList.remove('expanded')
		e.target.classList.remove('expanded')
	}
}
</script>

<template>
	<ul class="ps-0">
		<li v-for="(item, index) in data" :key="index" class="border-bottom">
			<div class="d-flex">
				<div v-if="item.children.length != 0" class="px-1">
					<span class="material-icons-outlined expand-icon" @click="expand($event, item.key)">expand_more</span>
				</div>
				<div class="form-check" :class="{ 'ms-2rem': item.children.length == 0 }">
					<input
						v-model="selectedItems"
						:input-id="`data-id-${item.key}`"
						class="form-check-input cursor-pointer"
						type="checkbox"
						name="selected[]"
						:value="item.key"
					/>
					<label class="form-check-label" :for="`data-id-${item.key}`">{{ item.label }}</label>
				</div>
			</div>
			<ul v-if="item.children.length != 0" class="child-list ps-5" :data-child-list="`child-list-${item.key}`">
				<li v-for="(child, childIndex) in item.children" :key="childIndex">
					<div class="form-check">
						<input
							v-model="selectedItems"
							:input-id="`data-id-${child.key}`"
							name="selected[]"
							:value="child.key"
							class="form-check-input cursor-pointer"
							type="checkbox"
						/>
						<label class="form-check-label mb-0" :for="`data-id-${child.key}`">{{ child.label }}</label>
					</div>
				</li>
			</ul>
		</li>
	</ul>
</template>

<style lang="css" scoped>
ul {
	list-style-type: none;
}
li {
	margin-bottom: 0.5rem;
}
.ms-2rem {
	margin-left: 2rem;
}
.child-list {
	display: none;
}
.child-list.expanded {
	display: block;
}
.expand-icon {
	color: var(--bs-gray-400);
	cursor: pointer;
	user-select: none;
	transform: rotate(-90deg);
}
.expand-icon.expanded {
	transform: rotate(0deg);
}
</style>
