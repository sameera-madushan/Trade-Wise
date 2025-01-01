import './bootstrap'

import { createApp, h, reactive } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { initAbilities, helpers } from './plugins'
import { abilitiesPlugin } from '@casl/vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import { QuillEditor } from '@vueup/vue-quill'


import { SimpleBar } from 'simplebar-vue3'
import DataTables from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'TRADE WISE'

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin }) {
		const myApp = createApp({ render: () => h(App, props) })
			.use(plugin)
			.use(ZiggyVue, Ziggy)

		const abilities = initAbilities(props)
		myApp.use(abilitiesPlugin, abilities, { useGlobalProperties: true })

		myApp.use(helpers)

		DataTables.use(DataTablesLib)
		myApp.component('DataTables', DataTables)
		myApp.component('SimpleBar', SimpleBar)
    myApp.component('VueDatePicker', VueDatePicker)
    myApp.component('QuillEditor', QuillEditor)

		myApp.config.globalProperties.$appState = reactive({ parentSelection: null, elementName: null })

		$.ajaxSetup({
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN': props.initialPage.props?.csrf_token,
			},
			dataType: 'json',
		})

		myApp.mount(el)
		return myApp
	},
	progress: {
		color: '#d40428',
	},
})
