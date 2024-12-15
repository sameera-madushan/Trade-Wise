<script setup>
import { useForm } from '@inertiajs/vue3'
import { nextTick, ref, defineProps } from 'vue'
import { Modal } from 'bootstrap'
import LoadingButton from '@/Components/LoadingButton.vue'

let confirmModal = ref()
const passwordInput = ref(null)

const props = defineProps({
  uuid: {
    type: String,
    default: null,
  }
})

const form = useForm({
	password: '',
})

nextTick(() => {
	confirmModal.value = new Modal(document.getElementById('modal-confirm'), {
		keyboard: false,
	})
})

const confirmUserDeletion = () => {
	confirmModal.value.show()

	nextTick(() => passwordInput.value.focus())
}

const deleteUser = () => {
	form.delete(props.uuid ? `/user/${props.uuid}/my-profile` : '/admin/my-profile', {
		preserveScroll: true,
		onSuccess: () => closeModal(),
		onError: () => passwordInput.value.focus(),
		onFinish: () => form.reset(),
	})
}

const closeModal = () => {
	confirmModal.value.hide()

	form.reset()
}
</script>

<template>
	<section>
		<header>
			<h2 class="font-medium text-gray-900">Delete Account</h2>

			<small class="mt-1 text-gray-600">
				Once your account is deleted, all of its resources and data will be permanently deleted.
			</small>
		</header>

    <div class="d-flex justify-content-end mt-2">
			<button type="button" class="btn btn-danger" @click="confirmUserDeletion">Delete Account</button>
		</div>

		<div id="modal-confirm" class="modal fade" tabindex="-1" aria-labelledby="modal-confirm" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<p class="modal-title text-gray-600">Are you sure you want to delete your account?</p>
						<button type="button" class="btn-close fs-6 ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="py-3">
							<small class="">
								Once your account is deleted, all of its resources and data will be permanently deleted.
							</small>
						</div>
						<div class="form-group mb-4">
							<input
								id="password"
								ref="passwordInput"
								v-model="form.password"
								type="password"
								class="form-control"
								placeholder="Password"
								@keyup.enter="deleteUser"
							/>
							<div v-show="form.errors.password" class="mt-2">
								<small class="text-sm text-danger">{{ form.errors.password }}</small>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm" @click="closeModal">Not Now</button>
						<LoadingButton type="submit" class="btn btn-sm btn-danger ms-2" :is-loading="form.processing" :disabled="form.processing" @click="deleteUser"
							>Delete</LoadingButton
						>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
