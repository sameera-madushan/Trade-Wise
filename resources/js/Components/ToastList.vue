<script setup>
import ToastListItem from './ToastListItem.vue'
import { onBeforeUnmount } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/vue3'
import { toast } from '@/stores'

const removeFinishedEventListener = Inertia.on('finish', () => {
	const { props } = usePage()
	if (props.message) {
		toast.add(props.message)
	} else if (Object.hasOwn(props, 'data') && Object.hasOwn(props.data, 'message')) {
		toast.add(props.data.message)
	} else {
		var keys = Object.keys(props.errors)
		if (keys.length == 0) return
		for (var key in props.errors) {
			toast.add({ type: 'error', message: props.errors[key] })
		}
	}
})

onBeforeUnmount(() => removeFinishedEventListener())
</script>

<template>
	<TransitionGroup tag="div" name="toast-element" class="toast-container position-fixed start-0 bottom-0 p-3" style="z-index: 9999">
		<ToastListItem v-for="(item, index) in toast.items" :key="item.key" :type="item.type" :message="item.message" @remove="toast.remove(index)" />
	</TransitionGroup>
</template>

<style lang="scss" scoped>
.toast-element-enter-active,
.toast-element-leave-active {
	transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.toast-element-enter-from,
.toast-element-leave-to {
	opacity: 0;
	transform: translateX(-100%);
}
</style>
