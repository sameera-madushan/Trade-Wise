<script setup>
defineProps({
	summary: {
		type: Object,
		required: true,
	},
})
</script>

<template>
	<template v-if="Object.keys(summary).length !== 0">
		<div v-if="summary?.last_record?.created_at != null && summary?.last_change?.updated_at != null" class="card border-0 shadow">
			<div class="card-body">
				<div class="border-bottom pb-3">
					<div class="h6 mb-0 d-flex">
						<div class="flex-text-truncate">
							<div class="text-truncate min-w-0"><span>Last record on,</span></div>
							<div class="text-truncate min-w-0 small text-gray-500">
								{{ $helpers.showInDeviceTimezone(summary?.last_record?.created_at, 'MMMM DD, YYYY | hh:mm A') }}
							</div>
							<div class="text-truncate min-w-0 mt-3"><span>Added by,</span></div>
							<div class="text-truncate min-w-0 small text-gray-500">{{ summary?.last_record?.created_by ?? 'N/A' }}</div>
						</div>
						<div class="flex-shrink-0 ms-2">
							<div class="calendar d-flex">
								<span class="calendar-month">{{ $helpers.showInDeviceTimezone(summary?.last_record?.created_at, 'MMM') }}</span
								><span class="calendar-day py-2">{{ $helpers.showInDeviceTimezone(summary?.last_record?.created_at, 'D') }}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="pt-3">
					<div class="h6 mb-0 d-flex">
						<div class="flex-text-truncate">
							<div class="text-truncate min-w-0"><span>Last updated on,</span></div>
							<div class="text-truncate min-w-0 small text-gray-500">
								{{ $helpers.showInDeviceTimezone(summary?.last_change?.updated_at, 'MMMM DD, YYYY | hh:mm A') }}
							</div>
							<div class="text-truncate min-w-0 mt-3"><span>Updated by,</span></div>
							<div class="text-truncate min-w-0 small text-gray-500">{{ summary?.last_change?.updated_by ?? 'N/A' }}</div>
						</div>
						<div class="flex-shrink-0 ms-2">
							<div class="calendar d-flex">
								<span class="calendar-month">{{ $helpers.showInDeviceTimezone(summary?.last_change?.updated_at, 'MMM') }}</span
								><span class="calendar-day py-2">{{ $helpers.showInDeviceTimezone(summary?.last_change?.updated_at, 'D') }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</template>
</template>
