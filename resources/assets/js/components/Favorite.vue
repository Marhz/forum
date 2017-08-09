<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
	export default {
		props: ['subject', 'type'],

		data() {
			return {
				count: this.subject.favoritesCount,
				isFavorited: this.subject.isFavorited
			}
		},

		methods: {
			toggle() {
				return this.isFavorited ? this.unfavorite() : this.favorite()
			},

			favorite() {
				axios.post(this.link)
					.then(r => {
						this.count++
						this.isFavorited = !this.isFavorited
					})
			},

			unfavorite() {
				axios.delete(this.link+"/delete")
					.then(r => {
						this.count--
						this.isFavorited = !this.isFavorited
					})
			}
		},

		computed: {
			classes() {
				return ['btn', 'btn-toggle', this.isFavorited ? 'btn-primary' : 'btn-default']
			},
			link() {
				return "/"+this.type+"/"+this.subject.id+"/favorites"
			}
		}
	}
</script>