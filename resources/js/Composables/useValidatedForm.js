import { useForm } from '@inertiajs/vue3'
import intus from 'intus'

export default function useValidatedForm(definition, rememberKey = null) {
	let fields = {}
	let rules = {}

	for (let field in definition) {
		fields[field] = definition[field][0]
		rules[field] = definition[field][1] ?? []
	}

	let form = rememberKey != null ? useForm(rememberKey, fields) : useForm(fields)

	return new Proxy(form, {
		get(target, prop) {
			if (prop == 'submit') {
				form.clearErrors()

				let validation = intus.validate(form.data(), rules)

				if (!validation.passes()) {
					form.setError(validation.errors())

					return () => {}
				}
			}

			return target[prop]
		},
	})
}
