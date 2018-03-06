<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading level">
            <h5 class="flex">
                <a :href="'/profiles/'+reply.owner.name" v-text="reply.owner.name"></a> 
                reply, <span v-text="ago"></span>...
            </h5>
            
            <favorite :reply="reply"></favorite>

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
            <div class="panel-footer level">
            	<div v-if="authorize('updateReply', reply)">
	                <button class="btn btn-primary btn-xs mr-1" @click="editing = true">Edit</button>
	                <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
	            </div>
	            <div class="ml-a" v-if="authorize('updateThread', reply.thread)">
		            <button class="btn btn-xs btn-primary ml-a" 
		            	@click="markBestReply" 
		            	v-show="! isBest">
		            	Best Reply?
		            </button>
		        </div>
            </div>
    </div>
</template>

<script>
	import moment from 'moment';
	import Favorite from "./Favorite.vue";

	export default {
		props: ['reply'],
		components: { Favorite },

		data() {
			return {
				editing: false,
				body: this.reply.body,
				id: this.reply.id,
				isBest: this.reply.isBest, 
			}
		}, 

		computed: {
			ago() {
				return moment(this.reply.created_at).fromNow();
			}
		},

		created() {
			window.events.$on('best-reply-selected', id => {
				this.isBest = (id === this.reply.id)
			});
		},

		methods: {
			update() {
				axios.patch('/reply/' + this.reply.id, {
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
				axios.delete('/reply/' + this.reply.id);

				this.$emit('deleted', this.reply.id);
				// $(this.$el).fadeOut(300);
				flash('Reply deleted successfully!');
			},

			markBestReply() {
				axios.post('/reply/' + this.reply.id + '/best');

				window.events.$emit('best-reply-selected', this.reply.id);
			}
		}
	}
</script>