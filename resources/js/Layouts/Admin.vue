<script setup>
import TopNav from '@/Components/Admin/TopNav.vue'
import SidebarNav from '@/Components/Admin/SidebarNav.vue'
import Footer from '@/Components/Admin/Footer.vue'
import ToastList from '@/Components/ToastList.vue'
</script>

<template>
	<SidebarNav />
	<main class="content">
		<TopNav />
		<div class="layout-main-content">
			<slot />
			<ToastList />
		</div>
		<Footer />
	</main>
</template>
