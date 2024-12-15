import { defineAbility, AbilityBuilder, Ability } from '@casl/ability'

export const initAbilities = ({ initialPage: page }) => {
	const abilities = defineAbility((can) => {
		if (page.props.auth.user !== null) {
			can(page.props.auth.user?.permissions ?? [])
		} else {
			can([])
		}
	})

	return abilities
}

export const updateAbilities = (ability, permissions) => {
	const { can, rules } = new AbilityBuilder(Ability)
	can(permissions)
	ability.update(rules)
}
