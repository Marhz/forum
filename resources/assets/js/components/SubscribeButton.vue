<template>
	<button 
		:class="classes" 
		@click="subscribe"
		v-text="active ? 'Unsubscribe' : 'Subscribe'"
	></button>
</template>

<script>
	export default {
		props: ['isActive'],
		data() {
			return {
				active: this.isActive
			}
		},
		methods: {
			subscribe() {
				let requestType = this.active ? 'delete' : 'post'
				axios[requestType](location.pathname + '/subscriptions')
					.then(response => {
						this.active = !this.active
					})
			}
		},
		computed: {
			classes() {
				return ['btn', this.active ? 'btn-primary' : 'btn-default']
			}
		}
	}
</script>