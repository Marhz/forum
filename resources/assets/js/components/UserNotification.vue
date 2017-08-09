<template>
	<li class="level">
		<div class="notification">
			<a :href="notification.link" v-text="notification.message" @click="markAsRead(notification)"></a>
			<br/>
			<small v-text="ago"></small>		
		</div>
		<div @click="markAsRead(notification)" class="notification__delete level">
			<span class="glyphicon glyphicon-remove"></span>
		</div>
	</li>
</template>

<script>
	import moment from 'moment'
	export default {
		name: "user-notification",
		props: ['data'],

		data() {
			return {
				notification: this.data.data
			}
		},
		methods: {
			markAsRead(notification) {
				axios.delete("/profiles/"+window.Forum.user.name+"/notifications/"+this.data.id)
					.then(response => this.$emit('deleted-notification', this.data.id))
			},
		},
		computed: {
			ago() {
				return moment(this.data.updated_at).fromNow()
			}
		}

	}
</script>

<style>
	.notification {
		padding: 10px 20px;
		display: block;
		width: 350px;
	}
	.notification__delete {
		width: 50px;
		height: 50px;
		font-size: 20px;
		cursor: pointer;
		justify-content: center;
	}
	.notification__delete:hover {
		color: red;
	}
</style>