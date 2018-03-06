<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		components: { Replies, SubscribeButton }, 
		props: ['thread'],

		data () {
			return {
				replies_count: this.thread.replies_count,
				locked: this.thread.locked,
				editing: false,
				form : {
					title: this.thread.title,
					body: this.thread.body
				},
				title : this.thread.title,
				body : this.thread.body
			}
		}, 

		methods: {
			toggleLock () {
				axios[this.locked ? 'delete' : 'post']('/locked-threads/' + this.thread.slug);

				this.locked = ! this.locked;
			}, 
			update () {
				let uri = '/threads/' + this.thread.channel.slug + '/' + this.thread.slug;
				console.log(uri);
				axios.patch(uri, this.form)
					.then(() => { 
						this.editing = false;
						this.title = this.form.title;
						this.body = this.form.body;

						flash('Thread Updated Successfully');
						});
			}, 
			cancel () {
				this.form.title = this.thread.title;
				this.form.body = this.thread.body;
				this.editing = false;
			}
		}
	}
</script>