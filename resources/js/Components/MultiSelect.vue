<script setup>
import { ref, watch, onMounted, computed } from 'vue'

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
	placeholder: {
		type: String,
		default: () => 'Select Items...',
	},
})

const selectedItemsPreview = ref(props.placeholder)
const searchInput = ref('')
const selectedItems = ref([])

const getData = computed(() => {
	if (searchInput.value != '') {
		return props.data.filter((item) => {
			try {
				if (new RegExp(`${searchInput.value}+`, 'gi').test(item.label)) {
					return item
				} else return
			} catch (error) {
				return item
			}
		})
	}
	return props.data
})

watch(
	() => props.selected,
	(newVal) => {
		if (newVal.length !== 0) {
			selectedItems.value = newVal
		}
	}
)

watch(selectedItems, (newVal) => {
	selectedItemsPreview.value =
		newVal.length == 0
			? props.placeholder
			: props.data
					.filter((item) => newVal.includes(item.value))
					.map((item) => item.label)
					.join(', ')
	emit('update:modelValue', newVal)
})

function closeDropdown(event) {
	if (event.target.closest('.multiselect-box') == null) {
		var openDropdowns = document.querySelectorAll('.multiselect-box__dropdown.show')
		openDropdowns.forEach((element) => {
			element.classList.remove('show')
		})
	}
}

onMounted(() => {
	document.addEventListener('click', (event) => {
		closeDropdown(event)
	})
})

function toggleDropdown(e) {
	var openDropdowns = document.querySelectorAll('.multiselect-box__dropdown.show')
	openDropdowns.forEach((element) => {
		if (element != e.target.nextElementSibling) element.classList.remove('show')
	})

	if (e.target.nextElementSibling && e.target.nextElementSibling.classList.contains('multiselect-box__dropdown')) {
		e.target.nextElementSibling.classList.toggle('show')
	}
}
</script>

<template>
	<div class="multiselect-box">
		<div class="form-control d-flex justify-content-between align-items-center cursor-pointer" @click.prevent="toggleDropdown">
			<div class="truncate-text pe-none">
				<span class="user-select-none">{{ selectedItemsPreview }}</span>
			</div>
			<div class="pe-none">
				<span class="material-icons-outlined align-text-top fs-18px user-select-none">arrow_drop_down</span>
			</div>
		</div>
		<div class="multiselect-box__dropdown" data-multiselect-dropdown>
			<div class="multiselect-box__search-items p-3">
				<input v-model="searchInput" type="text" class="form-control form-control-sm" />
			</div>
			<SimpleBar class="multiselect-box__checks-container" data-simplebar-autohide="false" data-simplebar>
				<ul class="multiselect-box__check-list">
					<li v-for="(item, index) in getData" :key="index">
						<div class="form-check">
							<input :id="`check-${index}`" v-model="selectedItems" class="form-check-input" type="checkbox" :value="item.value" />
							<label class="form-check-label multiselect-box__check-label" :for="`check-${index}`">{{ item.label }}</label>
						</div>
					</li>
				</ul>
			</SimpleBar>
		</div>
	</div>
</template>

<style lang="css" scoped>
.fs-18px {
	font-size: 18px;
}
</style>
