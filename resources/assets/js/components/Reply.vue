<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
        	<div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name">{{ data.owner.name }}</a> il y a <span v-text="ago"></span>
        		</h5>
				<div v-if="loggedIn">
            		<favorite :subject="data" type="replies"></favorite>
				</div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
            	<form @submit.prevent="update">
	                <div class="form-group">
	                    <textarea name="" id="" class="form-control" v-model="body" required></textarea>
	                </div>
	                <button class="btn btn-xs btn-primary">Update</button>
	                <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>            		
            	</form>
            </div>
            <div v-else v-text="oldBody"></div>
        </div>
        <div class="panel-footer level" v-if="canUpdate">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
	import moment from 'moment'
	export default {
		props: ['data'],
		data() {
			return {
				editing: false,
				id: this.data.id,
				body: this.data.body,
				oldBody: this.data.body
			}
		},
		methods: {
			update() {
				axios.patch('/replies/'+this.data.id, {
					body: this.body
				}).then(response => {
					this.editing = false
					this.oldBody = this.body
					flash('Updated!')					
				}).catch(error => flash(error.response.data, 'danger'))
			},
			destroy() {
				axios.delete('/replies/'+this.data.id)
				.then(response => {
					// $(this.$el).fadeOut(300, () => {
					// 	flash('Reply deleted!')
					// })
					this.$emit('deleted', this.data.id)
				})
			}
		},
		computed: {
			loggedIn() {
				return window.Forum.loggedIn
			},
			canUpdate() {
				return this.authorize(user => this.data.user_id == user.id)
			},
			ago() {
				return moment(this.data.created_at).fromNow()
			}
		}
	}
</script>