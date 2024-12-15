<script setup>
import GuestLayout from '@/Layouts/Guest.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'

const form = useForm({
	password: '',
})

const submit = () => {
	form.post(route('password.confirm'), {
		onFinish: () => form.reset(),
	})
}
</script>

<template>
	<GuestLayout>
		<Head title="Confirm Password" />

		<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
			<div class="container">
				<Link href="/" class="d-flex align-items-center justify-content-center mb-4">
					<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path
							fill-rule="evenodd"
							d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
							clip-rule="evenodd"
						></path>
					</svg>
					Back to homepage
				</Link>
				<div class="row justify-content-center form-bg-image">
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">
							<div class="text-center text-md-center mb-4 mt-md-0">
								<div class="avatar avatar-lg mx-auto mb-3">
									<img class="rounded-circle" alt="Image placeholder" src="/assets/img/avatar/placeholder.png" />
								</div>
								<h1 class="h3">{{ $page.props.auth.user.name }}</h1>
								<p class="text-gray">Better to be safe than sorry.</p>
								<div class="text-sm text-dark">This is a secure area of the application. Please confirm your password before continuing.</div>
							</div>
							<form class="mt-5" @submit.prevent="submit">
								<!-- Form -->
								<div class="form-group mb-4">
									<label for="password">Your Password</label>
									<div class="input-group">
										<span id="basic-addon2" class="input-group-text">
											<span class="material-icons text-xs text-gray-600">lock</span>
										</span>
										<input
											id="password"
											v-model="form.password"
											type="password"
											placeholder="Password"
											class="form-control"
											required
											autocomplete="current-password"
											autofocus
										/>
									</div>
									<div v-show="form.errors.password" class="mt-2">
										<p class="text-sm text-danger">{{ form.errors.password }}</p>
									</div>
								</div>
								<!-- End of Form -->
								<div class="d-grid mt-3">
									<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing">Unlock</LoadingButton>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</GuestLayout>
</template>
