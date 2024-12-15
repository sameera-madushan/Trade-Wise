<script setup>
import LoadingButton from '@/Components/LoadingButton.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
	current_password: '',
	password: '',
	password_confirmation: '',
})

const updatePassword = () => {
	form.put(route('password.update'), {
		preserveScroll: true,
		onSuccess: () => form.reset(),
		onError: () => {
			if (form.errors.password) {
				form.reset('password', 'password_confirmation')
				passwordInput.value.focus()
			}
			if (form.errors.current_password) {
				form.reset('current_password')
				currentPasswordInput.value.focus()
			}
		},
	})
}
</script>

<template>
	<section>
		<header>
			<h2 class="font-medium text-gray-900">Update Password</h2>

			<small class="mt-1 text-gray-600">Ensure your account is using a long, random password to stay secure.</small>
		</header>

		<form class="mt-4" @submit.prevent="updatePassword">
			<div class="row mb-3">
				<label for="current-password" class="col-sm-3 col-form-label">Current Password</label>
				<div class="col-sm-9">
					<input
						id="current-password"
						ref="currentPasswordInput"
						v-model="form.current_password"
						type="password"
						class="form-control"
						:class="{ 'is-invalid': form.errors.current_password }"
						autocomplete="current-password"
					/>
					<div v-show="form.errors.current_password" class="mt-2">
						<small class="text-sm text-danger">{{ form.errors.current_password }}</small>
					</div>
				</div>
			</div>

			<div class="row mb-3">
				<label for="password" class="col-sm-3 col-form-label">New Password</label>
				<div class="col-sm-9">
					<input
						id="password"
						ref="passwordInput"
						v-model="form.password"
						type="password"
						class="form-control"
						:class="{ 'is-invalid': form.errors.password }"
						autocomplete="new-password"
					/>
					<div v-show="form.errors.password" class="mt-2">
						<small class="text-sm text-danger">{{ form.errors.password }}</small>
					</div>
				</div>
			</div>

			<div class="row mb-3">
				<label for="password-confirmation" class="col-sm-3 col-form-label">Confirm Password</label>
				<div class="col-sm-9">
					<input
						id="password-confirmation"
						ref="passwordInput"
						v-model="form.password_confirmation"
						type="password"
						class="form-control"
						autocomplete="new-password"
					/>
					<div v-show="form.errors.password_confirmation" class="mt-2">
						<small class="text-sm text-danger">{{ form.errors.password_confirmation }}</small>
					</div>
				</div>
			</div>

			<div class="d-flex align-items-center justify-content-end gap-4">
				<LoadingButton type="submit" class="btn btn-gray-800" :is-loading="form.processing" :disabled="form.processing">Change password</LoadingButton>

				<Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
					<small v-if="form.recentlySuccessful" class="text-gray-600">Saved.</small>
				</Transition>
			</div>
		</form>
	</section>
</template>
