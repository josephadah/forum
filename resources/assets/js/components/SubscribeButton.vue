<template>
	<button :class="classes" @click="unsubscribe" v-if="active">Unsubscribe</button>
	<button :class="classes" @click="subscribe" v-else>Subscribe</button>
</template>

<script>
	export default {
		props: ['subscriptionStatus'],
		data() {
			return {
				active: this.subscriptionStatus
			}
		}, 

		computed: {
			classes() {
				return ['btn', 'btn-sm', this.active ? 'btn-primary' : 'btn-default'];
			}
		}, 

		methods: {
			subscribe() {
				axios.post(location.pathname + '/subscriptions');
				this.active = true;
				flash('You have subscribed to this thread');
			}, 
			unsubscribe() {
				axios.delete(location.pathname + '/subscriptions');
				this.active = false;
				flash('You have unsubscribe from this thread');
			}
		}
	}
</script>