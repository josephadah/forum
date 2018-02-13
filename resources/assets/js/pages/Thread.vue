<script>
	import Replies from '../components/Replies.vue';

	export default {
		components: { Replies }, 
		props: ['initialRepliesCount'],

		data() {
			return {
				replies_count: this.initialRepliesCount
			}
		}
	}
</script>