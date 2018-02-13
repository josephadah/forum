<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading level">
            <h5 class="flex">
                <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a> 
                reply, {{ data.created_at }}...
            </h5>
            
            <favorite :reply="data" :authcheck="sigedIn"></favorite>

        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" rows="1" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-xs" @click="update">Update</button>
                <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
            
        </div>
            <div class="panel-footer level" v-if="canUpdate">
                <button class="btn btn-primary btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
            </div>
    </div>
</template>

<script>
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