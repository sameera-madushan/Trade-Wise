<script setup>
import { Link } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const currentTime = ref('')
const greting = ref('')
const { props } = usePage()
const userName = ref(props.auth.user.name)

const updateTime = () => {
	const now = new Date()
	currentTime.value = now.toLocaleString()
	const hour = now.getHours()
	if (hour < 12) {
		greting.value = `🌅 Good Morning, ${userName.value}`
	} else if (hour < 18) {
		greting.value = `☀️ Good Afternoon, ${userName.value}`
	} else {
		greting.value = `🌙 Good Evening, ${userName.value}`
	}
}

onMounted(() => {
	updateTime()
	setInterval(updateTime, 1000)
})
</script>

<template>
	<nav class="pb-3 mt-2 navbar navbar-top navbar-expand navbar-dashboard navbar-theme-primary navbar-dark ps-0 pe-2 mb-4">
		<div class="container-fluid px-0">
			<div id="navbarSupportedContent" class="d-flex justify-content-between w-100">
				<div class="d-flex align-items-center">
          <div class="greeting-text ms-3">
						{{ greting }}
					</div>
				</div>
				<!-- Navbar links -->
				<ul class="navbar-nav align-items-center">
          <div class="time-text me-3">
						{{ currentTime }}
					</div>
					<li class="nav-item dropdown ms-lg-3">
						<a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<div class="media d-flex align-items-center">
								<img class="avatar rounded-circle" alt="Image placeholder" src="/assets/img/avatar/placeholder.png" />
								<div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
									<!-- <span class="mb-0 font-small fw-bold text-gray-900">{{ $page.props.auth.user.name }}</span> -->
								</div>
							</div>
						</a>
						<div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
							<Link class="dropdown-item d-flex align-items-center" href="/admin/my-profile">
								<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
									<path
										fill-rule="evenodd"
										d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
										clip-rule="evenodd"
									></path>
								</svg>
								My Profile
							</Link>
							<div role="separator" class="dropdown-divider my-1"></div>
							<Link class="dropdown-item d-flex align-items-center" :href="route('logout')" method="post" as="button">
								<svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										stroke-width="2"
										d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
									></path>
								</svg>
								Logout
							</Link>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</template>

<style scoped>
.time-text {
	font-size: 1.2rem;
	font-weight: 500;
	color: white;
}
.greeting-text {
	font-size: 1.5rem;
	font-weight: 500;
	color: white;
}
</style>
