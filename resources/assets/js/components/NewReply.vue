<template>
	<div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
            	<div v-if="signedIn">
                    <div class="form-group">
                        <textarea class="form-control" name="body" id="body" 
                        	rows="2" placeholder="Say something..." 
                        	required v-model="body">
                        </textarea>
                    </div>
                    <button class="btn btn-primary btn-sm" @click="addReply">Reply</button>
                </div>
                <div v-else>
		            <p class="text-center">Please <a href="/login">Sign In</a> to post a reply</p>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
		import 'jquery.caret';
		import 'at.js';
	export default {
		data() {
			return {
				body: '',
				endpoint: location.pathname + '/replies'
			}
		}, 

		computed: {
			signedIn() {
				return window.App.Auth;
			}
		},

		mounted() {
			$('#body').atwho({
				at: "@", 
				delay: 750, 
				callbacks: {
					remoteFilter: function(query, callback) {
						$.getJSON("/api/users", {name: query}, function(usernames) {
							callback(usernames)
						});
					}
				}
			});
		},

		methods: {
			addReply() {
				axios.post(this.endpoint, {body: this.body})
					.catch(error => {
						flash(error.response.data, 'danger');
					})
					.then(response => {
						this.body = '';
						this.$emit('created', response.data);
						flash('Your comment has been posted');
					});

			}
		}
	}
</script>