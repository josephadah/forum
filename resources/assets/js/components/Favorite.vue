<template>
	<button :class="classes" @click="toggle" v-if="auth">
	    <span class="glyphicon glyphicon-heart"></span>
	    <span v-text="favoritesCount"></span>
	</button>

	<button :class="classes" v-else>
		<a :href="link">
		    <span class="glyphicon glyphicon-heart"></span>
		    <span v-text="favoritesCount"></span>
		</a>
	</button>
</template>

<script>
	export default {
		props: ['reply', 'authcheck'],

		data() {
			return {
				favoritesCount: this.reply.favoritesCount,
				isFavorited: this.reply.isFavorited, 
				auth: this.authcheck,
				link: "/replies/" + this.reply.id + "/favorites"
			}
		}, 

		computed: {
			classes() {
				return ['btn', 'btn-xs', this.isFavorited ? 'btn-primary' : 'btn-default']
			}
		},

		methods: {
			toggle() { this.isFavorited ? this.destroy() : this.create();
			}, 

			destroy() {
				axios.delete("/replies/" + this.reply.id + "/favorites");
				this.favoritesCount--;
				this.isFavorited = false;
			},

			create() {
				axios.post("/replies/" + this.reply.id + "/favorites");
				this.favoritesCount++;
				this.isFavorited = true;
			}
		}
	}
</script>

<style>
	a {
		text-decoration: none;
	}
</style>