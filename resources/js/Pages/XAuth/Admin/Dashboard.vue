<script setup>
import { getCurrentInstance as instance, onMounted, ref } from 'vue'
import DashboardLayout from '@/Layouts/Admin.vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios';

const { proxy } = instance()
const usersCount = ref(0);

proxy.$appState.parentSelection = null
proxy.$appState.elementName = 'Dashboard'

onMounted(async () => {
  try {
    const response = await axios.get(`/admin/get-users-count`);
    usersCount.value = response.data;
  } catch (error) {
    console.error(error);
  }
});
</script>

<template>
  <Head title="Dashboard" />
	<DashboardLayout>
		<div class="row mt-4 mb-4">
			<div class="col-12 col-xl-9">
				<div class="row">
					<!-- Users Card -->
					<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
						<div class="card">
							<div class="card-body p-3">
								<div class="row">
									<div class="col-8">
										<div class="numbers">
											<p class="text-sm mb-0 text-uppercase font-weight-bold">Users</p>
											<h5 class="font-weight-bolder">
												{{ usersCount ?? '0' }}
											</h5>
											<p class="mb-0">
												<a :href="'/admin/settings/users'" class="text-success text-sm font-weight-bolder">
													View All Users
												</a>
											</p>
										</div>
									</div>
									<div class="col-4 text-end">
										<div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
											<span class="material-icons">people_alt</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Orders Card -->
					<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
						<div class="card">
							<div class="card-body p-3">
								<div class="row">
									<div class="col-8">
										<div class="numbers">
											<p class="text-sm mb-0 text-uppercase font-weight-bold">Online Users</p>
											<h5 class="font-weight-bolder">
												{{ ordersCount ?? '0' }}
											</h5>
											<p class="mb-0">
												<a :href="'/admin/orders'" class="text-success text-sm font-weight-bolder">
													View All Orders
												</a>
											</p>
										</div>
									</div>
									<div class="col-4 text-end">
										<div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
											<span class="material-icons">cloud</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</DashboardLayout>
</template>



<style scoped>
.icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    color: white;
    font-size: 24px;
    text-align: center;
    line-height: 1;
}

.shadow-primary {
    box-shadow: 0px 4px 6px rgba(0, 123, 255, 0.4);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745, #218838);
}

.shadow-success {
    box-shadow: 0px 4px 6px rgba(40, 167, 69, 0.4);
}

.text-center {
    text-align: center;
}

.text-end {
    text-align: end;
}

.material-icons {
    font-size: 24px;
    line-height: 1;
}
</style>
