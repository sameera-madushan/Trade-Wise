<script setup>
import GuestLayout from '@/Layouts/Guest.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'

const form = useForm({
	name: '',
	email: '',
	password: '',
	password_confirmation: '',
	terms: false,
})

const submit = () => {
	form.post(route('register'), {
		onFinish: () => form.reset('password', 'password_confirmation'),
	})
}
</script>

<template>
	<GuestLayout>
		<Head title="Register" />

		<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
			<div class="container">
				<p class="text-center">
					<Link href="/" class="d-flex align-items-center justify-content-center">
						<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
							<path
								fill-rule="evenodd"
								d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
								clip-rule="evenodd"
							></path>
						</svg>
						Back to homepage
					</Link>
				</p>
				<div class="row justify-content-center form-bg-image">
					<div class="col-12 d-flex align-items-center justify-content-center">
						<!-- Adjusted the card width here to fmxw-800 to make it wider -->
						<div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-800">
							<div class="text-center text-md-center mb-4 mt-md-0">
                <img src="/assets/img/logo_black.png" alt="" />
								<h1 class="mb-0 mt-4 h3">Create an Account</h1>
							</div>
							<form class="mt-4" @submit.prevent="submit">
								<!-- Form Row for Name and Email -->
								<div class="row">
									<div class="col-12 col-md-6 mb-4">
										<label for="name">Name</label>
										<div class="input-group">
											<span class="input-group-text">
												<span class="material-icons text-xs text-gray-600">person</span>
											</span>
											<input id="name" v-model="form.name" type="text" class="form-control" placeholder="John Doe" required autofocus autocomplete="name" />
										</div>
										<div v-show="form.errors.name" class="mt-2">
											<p class="text-sm text-danger">{{ form.errors.name }}</p>
										</div>
									</div>
									<div class="col-12 col-md-6 mb-4">
										<label for="email">Your Email</label>
										<div class="input-group">
											<span class="input-group-text">
												<span class="material-icons text-xs text-gray-600">email</span>
											</span>
											<input
												id="email"
												v-model="form.email"
												type="email"
												class="form-control"
												placeholder="example@company.com"
												required
												autocomplete="username"
											/>
										</div>
										<div v-show="form.errors.email" class="mt-2">
											<p class="text-sm text-danger">{{ form.errors.email }}</p>
										</div>
									</div>
								</div>

								<!-- Form Row for Password and Confirm Password -->
								<div class="row">
									<div class="col-12 col-md-6 mb-4">
										<label for="password">Your Password</label>
										<div class="input-group">
											<span class="input-group-text">
												<span class="material-icons text-xs text-gray-600">lock</span>
											</span>
											<input
												id="password"
												v-model="form.password"
												type="password"
												placeholder="Password"
												class="form-control"
												required
												autocomplete="new-password"
											/>
										</div>
										<div v-show="form.errors.password" class="mt-2">
											<p class="text-sm text-danger">{{ form.errors.password }}</p>
										</div>
									</div>
									<div class="col-12 col-md-6 mb-4">
										<label for="confirm_password">Confirm Password</label>
										<div class="input-group">
											<span class="input-group-text">
												<span class="material-icons text-xs text-gray-600">lock</span>
											</span>
											<input
												id="confirm_password"
												v-model="form.password_confirmation"
												type="password"
												placeholder="Confirm Password"
												class="form-control"
												required
												autocomplete="new-password"
											/>
										</div>
										<div v-show="form.errors.password_confirmation" class="mt-2">
											<p class="text-sm text-danger">{{ form.errors.password_confirmation }}</p>
										</div>
									</div>
								</div>

								<!-- Terms & Conditions -->
								<div class="mb-4">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" required />
										<label class="form-check-label fw-normal mb-0" for="remember">
											I agree to the <a href="#" class="fw-bold">terms and conditions</a>
										</label>
									</div>
								</div>

								<!-- Submit Button -->
								<div class="d-grid">
									<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing">Sign up</LoadingButton>
								</div>
							</form>

							<!-- Login Link -->
							<div class="d-flex justify-content-center align-items-center mt-4">
								<span class="fw-normal">
									Already have an account?
									<Link :href="route('login')" class="fw-bold">Login here</Link>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</GuestLayout>
</template>
