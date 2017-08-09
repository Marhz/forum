<template>
	<div>
		<!-- <transition-group name="slide-fade"> -->
			<div v-for="(reply, index) in items" :key="reply.id">
				<reply :data="reply" @deleted="remove(index)"></reply>
			</div>
		<!-- </transition-group> -->
		<paginator :dataSet="dataSet" @updated="fetchPage"></paginator>
		<new-reply :endpoint="endpoint" @created="addReply"></new-reply>
	</div>
</template>

<script>
	import Reply from './Reply.vue'
	import NewReply from './NewReply.vue'
	import collection from '../mixins/Collection'

	export default {
		components: { Reply, NewReply },
		mixins: [collection],
		data() {
			return {
				dataSet: false,
				endpoint: location.pathname + '/replies',
			}
		},

		methods: {
			fetch(page) {
				axios.get(this.url(page))
					.then(this.refresh)
			},
			url(page) {
				if(!page) {
					let query = location.search.match(/page=(\d+)/)
					page = query ? query[1] : 1
				}
				return location.pathname+'/replies?page='+page
			},
			refresh({data}) {
				this.dataSet = data
				this.items = data.data
			},
			addReply(reply) {
				if(this.dataSet.last_page !== this.dataSet.current_page)
					this.fetch(this.dataSet.last_page)
				this.add(reply)
			},
			fetchPage(page) {
				if(this.dataSet.current_page == page)
					return
				this.fetch(page)
				window.scrollTo(0,0)
			}
		},
		created() {
			this.fetch()
		}
	}
</script>

<style>
	.slide-fade-enter-active {
	 	transition: all .3s ease;
	}
	.slide-fade-leave-active {
		transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}
	.slide-fade-enter, .slide-fade-leave-to {
		transform: translateX(10px);
		opacity: 0;
	}
</style>