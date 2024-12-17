<script setup>
import TopNav from '@/Components/User/TopNav.vue'
import SidebarNav from '@/Components/User/SidebarNav.vue'
import Footer from '@/Components/User/Footer.vue'
import ToastList from '@/Components/ToastList.vue'

</script>


<template>
	<SidebarNav/>
	<main class="content">
		<TopNav />
		<div class="layout-main-content">
			<slot />
			<ToastList />
		</div>
		<Footer />
	</main>
</template>
