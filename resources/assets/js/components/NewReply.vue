<template>
	<div>
		<div v-if="loggedIn">
	        <div class="form-group">
	            <textarea 
	            	name="body" 
	            	id="body" 
	            	class="form-control" 
	            	placeholder="Your reply" 
	            	rows="5" 
	            	v-model="body"
	            	required 
	            >
	            </textarea>
	        </div>
	        <button type="submit" name="submit" class="btn btn-default pull-right" @click="addReply">Submit</button>			
		</div>
        <p class="text-center" v-else>Please <a href='/login'>sign in</a> to reply</p>
    </div>	
</template>

<script>
	export default {
		props: ['endpoint'],
		data() {
			return {
				body: '',
			}
		},
		methods: {
			addReply() {
				axios.post(this.endpoint, { body: this.body })
					.then(({data}) => {
						this.body = ""
						flash('Reply posted')
						this.$emit('created', data)
					}).catch(error => {
						flash(error.response.data, 'danger')
					})
			}
		},
		computed: {
			loggedIn() {
				return window.Forum.loggedIn
			}
		}
	}
</script>