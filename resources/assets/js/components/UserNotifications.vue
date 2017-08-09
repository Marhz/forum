<template>
	<li class="dropdown">
		<a href="#" class='dropdown-togle' :data-toggle="items.length ? 'dropdown' : ''">
			<span class="glyphicon glyphicon-bell"></span>
			<span class="notification__number" v-if="items.length" v-text="items.length"></span>
		</a>
		<ul :class="items.length ? 'dropdown-menu' : ''" @click="fuckYouBootstrap">
			<user-notification
				v-for="(notification, index) in items" 
				:data="notification" 
				:key="notification.id"
				@deleted-notification="remove(index)"
			></user-notification>
		</ul>
	</li>
</template>

<script>
	import UserNotification from './UserNotification.vue'
	import collection from '../mixins/Collection'

	export default {
		components: { UserNotification },
		mixins: [collection],
		methods: {
			fuckYouBootstrap(e) {
				e.stopPropagation()
			}
		},
		created() {
			axios.get("/profiles/"+window.Forum.user.name +"/notifications")
				.then(response => this.items = response.data)
				.catch()
		},
	}
</script>

<style>
	.notification__number {
		position: absolute;
		top: 3px;
		right: 0;
		color: white;
		font-family: Arial;
		font-weight: bold;
		background: rgba(255, 0, 0, .9);
		min-height: 22px;
		min-width: 22px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		border: 1px solid rgba(255, 255, 255, .2);
	}
</style>