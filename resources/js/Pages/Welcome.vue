<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
	canLogin: Boolean,
	canRegister: Boolean,
})

function checkRoles(bigArray, subarray) {
	for (let i = 0; i < subarray.length; i++) {
		if (bigArray.includes(subarray[i])) {
			return true
		}
	}
	return false
}
</script>

<template>
	<Head title="Welcome" />

	<div class="container d-flex align-items-center justify-content-center vh-100">
		<div class="row w-100">
			<!-- Left Column -->
			<div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
				<div class="card shadow border-0 rounded p-4 w-75">
					<div class="text-center mb-4">
            <img class="mb-4" src="/assets/img/logo_black.png" alt="" />
					</div>
					<div>
						<div>
							<Link
								v-if="$page.props.auth.user && checkRoles($page.props.auth.user.roles, ['SUPER_ADMIN', 'SITE_ADMIN', 'SITE_USER'])"
								href="/admin"
								class="dashboard-link"
								>Dashboard</Link
							>
							<Link
								v-if="$page.props.auth.user && checkRoles($page.props.auth.user.roles, ['USER'])"
								href="/user"
								class="dashboard-link"
								>Dashboard</Link
							>
						</div>
						<template v-if="$page.props.auth.user == null">
							<Link :href="route('login')" class="btn btn-primary w-100 mb-3">Log in</Link>
							<Link
								v-if="canRegister"
								:href="route('register')"
								class="btn btn-secondary w-100"
								>Register</Link
							>
						</template>
					</div>
				</div>
			</div>
			<!-- Right Column -->
			<div class="col-md-6 d-flex justify-content-center align-items-center">
				<img
					src="/assets/img/login_page.svg"
					alt="Illustration"
					class="w-75 h-auto svg-animate"
				/>
			</div>
		</div>
	</div>
</template>

<style scoped>
html,
body {
	margin: 0;
	padding: 0;
	height: 100%;
}

.container {
	max-width: 1200px;
}

.card {
	background: #fff;
	border-radius: 10px;
	padding: 2rem;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dashboard-link {
	font-weight: 700;
	color: #0056b3;
	font-size: 1rem;
}

.dashboard-link:hover {
	color: #003d8f;
	text-decoration: none;
}

</style>
