<script setup></script>

<template>
	<div class="card shadow border-0 p-0">
		<div class="card-body">
			<div class="text-center">
				<img src="/assets/img/avatar/placeholder.png" class="avatar-lg rounded-circle mx-auto mb-4" alt="User profile picture" />
			</div>
			<div class="text-truncate text-center">
				<h6 class="h6 d-inline">{{ $page.props.auth.user.name }}</h6>
				<br />
				<small class="mb-3 text-gray-500">{{ $page.props.auth.user.email }}</small>
			</div>
			<div class="overflow-hidden">
				<table class="w-100 border-top border-gray-200 mt-2 pt-2">
					<tr>
						<td class="px-1 text-nowrap"><small>Email verified:</small></td>
						<td class="px-1 w-100">
							<small
								><span v-if="$page.props.auth.user.email_verified_at == null" class="badge bg-danger">NO</span
								><span v-else class="badge bg-success">YES</span></small
							>
						</td>
					</tr>
					<tr>
						<td class="px-1 text-nowrap"><small>Created at:</small></td>
						<td class="px-1 w-100">
							<small>{{ $helpers.showInDeviceTimezone($page.props.auth.user.created_at, 'MMMM DD, YYYY') }}</small>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</template>
