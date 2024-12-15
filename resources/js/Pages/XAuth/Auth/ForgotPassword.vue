<script setup>
import GuestLayout from '@/Layouts/Guest.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'

defineProps({
	status: {
		type: String,
		default: null,
	},
})

const form = useForm({
	email: '',
})

const submit = () => {
	form.post(route('password.email'))
}
</script>

<template>
	<GuestLayout>
		<Head title="Forgot Password" />

		<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
			<div class="container">
				<div class="row justify-content-center form-bg-image">
					<p class="text-center">
						<Link :href="route('login')" class="d-flex align-items-center justify-content-center">
							<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
								<path
									fill-rule="evenodd"
									d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
									clip-rule="evenodd"
								></path>
							</svg>
							Back to log in
						</Link>
					</p>
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="signin-inner my-3 my-lg-0 bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">
							<h1 class="h3">Forgot your password?</h1>
							<p class="mb-4">
								Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose
								a new one.
							</p>
							<div v-if="status" class="mb-4 fw-normal text-sm text-success">
								{{ status }}
							</div>
							<form class="mt-4" @submit.prevent="submit">
								<!-- Form -->
								<div class="mb-4">
									<label for="email">Your Email</label>
									<div class="input-group">
										<input
											id="email"
											v-model="form.email"
											type="email"
											class="form-control"
											placeholder="john@company.com"
											required
											autofocus
											autocomplete="username"
										/>
									</div>
									<div v-show="form.errors.email" class="mt-2">
										<p class="text-sm text-danger">{{ form.errors.email }}</p>
									</div>
								</div>
								<!-- End of Form -->
								<div class="d-grid">
									<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing"
										>Recover password</LoadingButton
									>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</GuestLayout>
</template>
