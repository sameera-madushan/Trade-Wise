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
								v-if="$page.props.auth.user && checkRoles($page.props.auth.user.roles, ['AGENCY_ADMIN', 'AGENCY_USER'])"
								href="/agency"
								class="dashboard-link"
								>Dashboard</Link
							>
							<Link
								v-if="$page.props.auth.user && checkRoles($page.props.auth.user.roles, ['AGENT_ADMIN', 'AGENT_USER'])"
								href="/agent"
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

.btn {
	display: inline-block;
	padding: 12px 24px;
	font-size: 14px;
	font-weight: 600;
	text-align: center;
	text-decoration: none;
	border-radius: 30px;
	transition: all 0.3s ease;
	cursor: pointer;
}

.btn-primary {
	background: linear-gradient(45deg, #1e90ff, #00bfff);
	color: white;
	border: none;
}

.btn-secondary {
	background: linear-gradient(45deg, #ff6f61, #ff9966);
	color: white;
	border: none;
}

.btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
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

/* Animation for SVG */
.svg-animate {
	animation: float 3s ease-in-out infinite;
}

@keyframes float {
	0% {
		transform: translateY(0);
	}
	50% {
		transform: translateY(-10px);
	}
	100% {
		transform: translateY(0);
	}
}
</style>
