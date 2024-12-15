<script setup>
import { computed } from 'vue'
import GuestLayout from '@/Layouts/Guest.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
	status: {
		type: String,
		default: null,
	},
})

const form = useForm({})

const submit = () => {
	form.post(route('verification.send'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
	<GuestLayout>
		<Head title="Email Verification" />

		<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
			<div class="container">
				<div class="row justify-content-center form-bg-image">
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
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="signin-inner my-3 my-lg-0 bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">
							<p class="mb-4">
								Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't
								receive the email, we will gladly send you another.
							</p>
							<div v-if="verificationLinkSent" class="fw-normal text-sm text-success">
								A new verification link has been sent to the email address you provided during registration.
							</div>
							<form class="mt-4" @submit.prevent="submit">
								<div class="d-grid">
									<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing"
										>Resend Verification Email</LoadingButton
									>
								</div>
							</form>
							<div class="d-flex justify-content-center align-items-center mt-4">
								<span class="fw-normal"><Link :href="route('register')" method="post" as="button">Log out</Link></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</GuestLayout>
</template>
