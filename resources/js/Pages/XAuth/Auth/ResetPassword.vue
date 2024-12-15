<script setup>
import GuestLayout from '@/Layouts/Guest.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
	email: {
		type: String,
		default: null,
	},
	token: {
		type: String,
		default: null,
	},
})

const form = useForm({
	token: props.token,
	email: props.email,
	password: '',
	password_confirmation: '',
})

const submit = () => {
	form.post(route('password.store'), {
		onFinish: () => form.reset('password', 'password_confirmation'),
	})
}
</script>

<template>
	<GuestLayout>
		<Head title="Reset Password" />

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
						<div class="bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">
							<h1 class="h3 mb-4">Reset password</h1>
							<form @submit.prevent="submit">
								<!-- Form -->
								<div class="mb-4">
									<label for="email">Your Email</label>
									<div class="input-group">
										<input
											id="email"
											v-model="form.email"
											type="email"
											class="form-control"
											placeholder="example@company.com"
											required
											autofocus
											disabled
											autocomplete="username"
										/>
									</div>
									<div v-show="form.errors.email" class="mt-2">
										<p class="text-sm text-danger">{{ form.errors.email }}</p>
									</div>
								</div>
								<!-- End of Form -->
								<!-- Form -->
								<div class="form-group mb-4">
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
								<!-- End of Form -->
								<!-- Form -->
								<div class="form-group mb-4">
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
								<!-- End of Form -->
								<div class="d-grid">
									<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing">Reset password</LoadingButton>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</GuestLayout>
</template>
