<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		components: { Replies, SubscribeButton }, 
		props: ['initialRepliesCount'],

		data() {
			return {
				replies_count: this.initialRepliesCount
			}
		}
	}
</script>