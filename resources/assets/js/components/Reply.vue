<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading level">
            <h5 class="flex">
                <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a> 
                reply, <span v-text="ago"></span>...
            </h5>
            
            <favorite :reply="data" :authcheck="sigedIn"></favorite>

        </div>
        <div class="panel-body">
            <div v-if="editing">
            	<form @submit="update">
	                <div class="form-group">
	                    <textarea class="form-control" rows="1" v-model="body" required></textarea>
	                </div>
	                <button class="btn btn-primary btn-xs">Update</button>
	                <button class="btn btn-link btn-xs" @click="editing = false" type="button">Cancel</button>
	            </form>
            </div>
            <div v-else v-html="body"></div>
            
        </div>
            <div class="panel-footer level" v-if="canUpdate">
                <button class="btn btn-primary btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
            </div>
    </div>
</template>

<script>
	import moment from 'moment';
	import Favorite from "./Favorite.vue";

	export default {
		props: ['data'],
		components: { Favorite },

		data() {
			return {
				editing: false,
				body: this.data.body,
				id: this.data.id,
			}
		}, 

		computed: {
			ago() {
				return moment(this.data.created_at).fromNow();
			},

			sigedIn() {
				return window.App.Auth;
			},

			canUpdate() {
				return this.authorize(user => this.data.user_id == user.id);
			}
		},

		methods: {
			update() {
				axios.patch('/reply/' + this.data.id, {
					body: this.body
				})
				.catch(error => {
					flash(error.response.data, 'danger');
					this.editing = true;
				});

				this.editing = false;

				flash('Reply Updated!');
			},

			destroy() {
				axios.delete('/reply/' + this.data.id);

				this.$emit('deleted', this.data.id);
				// $(this.$el).fadeOut(300);
				flash('Reply deleted successfully!');
			}
		}
	}
</script>