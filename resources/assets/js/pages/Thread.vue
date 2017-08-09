<script>
	import Replies from '../components/Replies.vue'
	import SubscribeButton from '../components/SubscribeButton'

	export default {
		props: ['starterRepliesCount'],

		components: { Replies, SubscribeButton },

		data() {
			return {
				repliesCount: this.starterRepliesCount
			}
		},
	}
</script>