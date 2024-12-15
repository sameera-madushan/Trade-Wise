import { reactive } from 'vue'

export default reactive({
	items: [],
	add(toast) {
		this.items.unshift({
			key: Symbol(),
			...toast,
		})
	},
	remove(index) {
		this.items.splice(index, 1)
	},
	removeAll() {
		this.items = []
	},
})
