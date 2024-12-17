<script setup>
import LoadingButton from '@/Components/LoadingButton.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
	mustVerifyEmail: Boolean,
	status: {
		type: String,
		default: null,
	},
  isUser: Boolean
})

const user = usePage().props.auth.user

const form = useForm({
	name: user.name,
	email: user.email,
})

</script>

<template>
	<section>
		<header>
			<h2 class="font-medium text-gray-900">Profile Information</h2>

			<small class="mt-1 text-gray-600">Update your account's profile information and email address.</small>
		</header>

		<form class="mt-4" @submit.prevent="form.patch(props.isUser ? `/user/my-profile` : '/admin/my-profile')">
			<div class="row mb-3">
				<label for="name" class="col-sm-3 col-form-label">Name</label>
				<div class="col-sm-9">
					<input
						id="name"
						v-model="form.name"
						type="text"
						class="form-control"
						:class="{ 'is-invalid': form.errors.name }"
						required
						autofocus
						autocomplete="name"
					/>
					<div v-show="form.errors.name" class="mt-2">
						<small class="text-sm text-danger">{{ form.errors.name }}</small>
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<label for="email" class="col-sm-3 col-form-label">Email</label>
				<div class="col-sm-9">
					<input
						id="email"
						v-model="form.email"
						type="email"
						class="form-control"
						:class="{ 'is-invalid': form.errors.email }"
						required
						autocomplete="username"
					/>
					<div v-show="form.errors.email" class="mt-2">
						<small class="text-sm text-danger">{{ form.errors.email }}</small>
					</div>
				</div>
			</div>
			<div v-if="props.mustVerifyEmail && user.email_verified_at === null" class="offset-sm-3 ps-2">
				<p class="text-xs mt-2 text-gray-800">
					Your email address is unverified.
					<br /><Link :href="route('verification.send')" method="post" as="button" class="btn btn-gray-200">Click here to re-send the verification email.</Link>
				</p>

				<small v-show="props.status === 'verification-link-sent'" class="mt-2 text-sm text-success"
					>A new verification link has been sent to your email address.</small
				>
			</div>

			<div class="d-flex align-items-center justify-content-end gap-4">
				<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing">Save changes</LoadingButton>

				<Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
					<small v-if="form.recentlySuccessful" class="text-gray-600">Saved.</small>
				</Transition>
			</div>
		</form>
	</section>
</template>
