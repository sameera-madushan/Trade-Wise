<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useAbility } from '@casl/vue'

const { can } = useAbility()

const menu = ref([
	{ name: 'Dashboard', label: 'Dashboard', icon: 'dashboard', route: '/admin' },
	{ separator: true },
	{
		name: 'Settings',
		label: 'Settings',
		icon: 'settings',
		gates: ['MANAGE_SETTING'],
		items: [
			{ name: 'Permissions', label: 'Permissions', route: '/admin/settings/permissions', gates: ['MANAGE_PERMISSIONS'] },
			{ name: 'Roles', label: 'Roles', route: '/admin/settings/roles', gates: ['MANAGE_ROLES'] },
			{ name: 'Users', label: 'Users', route: '/admin/settings/users', gates: ['MANAGE_USERS'] },
		],
	},
])

function canVisible(gates) {
	return gates.some((gate) => can(gate))
}
</script>

<template>
	<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
		<Link class="navbar-brand me-lg-5" href="/admin">
			<img class="navbar-brand-dark" src="/assets/img/logo_white.png" alt="Volt logo" />
			<img class="navbar-brand-light" src="/assets/img/logo_black.png" alt="Volt logo" />
		</Link>
		<div class="d-flex align-items-center">
			<button
				class="navbar-toggler d-lg-none collapsed"
				type="button"
				data-bs-toggle="collapse"
				data-bs-target="#sidebarMenu"
				aria-controls="sidebarMenu"
				aria-expanded="false"
				aria-label="Toggle navigation"
			>
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
	</nav>
	<SimpleBar id="sidebarMenu" class="sidebar d-lg-block bg-primary text-white collapse" data-simplebar>
		<div class="sidebar-inner px-3 pt-3">
			<div class="mb-4 text-center">
				<Link href="/"><img src="/assets/img/logo_white.png" alt="TravelMo Logo" style="max-width: 200px" /></Link>
			</div>
			<div class="nav-separator d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
				<div class="d-flex align-items-center">
					<div class="avatar-lg me-4">
						<img src="/assets/img/avatar/placeholder.png" class="card-img-top rounded-circle border-white" alt="Bonnie Green" />
					</div>
					<div class="d-block">
						<h2 class="h5 mb-3">Hi, Jane</h2>
						<a href="pages/examples/sign-in.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
							<svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path
									stroke-linecap="round"
									stroke-linejoin="round"
									stroke-width="2"
									d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
								></path>
							</svg>
							Sign Out
						</a>
					</div>
				</div>
				<div class="collapse-close d-md-none">
					<a
						href="#sidebarMenu"
						data-bs-toggle="collapse"
						data-bs-target="#sidebarMenu"
						aria-controls="sidebarMenu"
						aria-expanded="true"
						aria-label="Toggle navigation"
					>
						<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
							<path
								fill-rule="evenodd"
								d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
								clip-rule="evenodd"
							></path>
						</svg>
					</a>
				</div>
			</div>
			<ul class="nav flex-column pt-3 pt-md-0">
				<template v-for="(item, index) in menu" :key="index">
					<template v-if="item.hasOwnProperty('items') && item.items.length > 0">
						<li
							v-if="item?.gates == undefined || canVisible(item?.gates)"
							class="nav-item"
							:class="{ active: $page.url == item.route || $appState.elementName == item.name }"
						>
							<span
								class="nav-link d-flex justify-content-between align-items-center"
								:class="{ collapsed: $appState.parentSelection != item.name }"
								data-bs-toggle="collapse"
								:data-bs-target="`#submenu-${item.name}`"
								:aria-expanded="$appState.parentSelection == item.name ? true : false"
							>
								<span>
									<span class="sidebar-icon">
										<span class="material-icons">{{ item.icon }}</span>
									</span>
									<span class="sidebar-text">{{ item.label }}</span>
								</span>
								<span class="link-arrow">
									<svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
										<path
											fill-rule="evenodd"
											d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
											clip-rule="evenodd"
										></path>
									</svg>
								</span>
							</span>
							<div
								:id="`submenu-${item.name}`"
								class="multi-level collapse"
								:class="{ show: $appState.parentSelection == item.name }"
								role="list"
								:aria-expanded="$appState.parentSelection == item.name ? true : false"
							>
								<ul class="flex-column nav">
									<template v-for="(subItem, idx) in item.items" :key="idx">
										<li
											v-if="subItem?.gates == undefined || canVisible(subItem?.gates)"
											class="nav-item"
											:class="{ active: $page.url == subItem.route || $appState.elementName == subItem.name }"
										>
											<Link class="nav-link" :href="subItem.route">
												<span class="sidebar-text">{{ subItem.label }}</span>
											</Link>
										</li>
									</template>
								</ul>
							</div>
						</li>
					</template>
					<template v-else-if="!item.hasOwnProperty('items') && item.separator">
						<li role="separator" class="dropdown-divider mt-3 mb-3 border-primary-700"></li>
					</template>
					<template v-else>
						<li
							v-if="item?.gates == undefined || canVisible(item?.gates)"
							class="nav-item"
							:class="{ active: $page.url == item.route || $appState.elementName == item.name }"
						>
							<Link :href="item.route" class="nav-link">
								<span class="sidebar-icon">
									<span class="material-icons">{{ item.icon }}</span>
								</span>
								<span class="sidebar-text">{{ item.label }}</span>
							</Link>
						</li>
					</template>
				</template>
			</ul>
		</div>
	</SimpleBar>
</template>
