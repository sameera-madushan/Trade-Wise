<script setup>
import { defineProps } from 'vue'
import TopNav from '@/Components/User/TopNav.vue'
import SidebarNav from '@/Components/User/SidebarNav.vue'
import Footer from '@/Components/User/Footer.vue'
import ToastList from '@/Components/ToastList.vue'

const props = defineProps({
  uuid: {
    type: String,
    default: null,
  }
})

</script>


<template>
	<SidebarNav :uuid="props.uuid"/>
	<main class="content">
		<TopNav :uuid="props.uuid" />
		<div class="layout-main-content">
			<slot />
			<ToastList />
		</div>
		<Footer />
	</main>
</template>
