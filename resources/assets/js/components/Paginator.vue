<template>
  <ul class="pagination" v-if="shouldPaginate">
    <li>
      <a href="#" aria-label="Previous" v-if="prevUrl" @click.prevent="page--">
        <span aria-hidden="true" rel="prev">&laquo; Previous</span>
      </a>
    </li>

    <li>
      <a href="#" aria-label="Next" rel="next" v-if="nextUrl" @click.prevent="page++">
        <span aria-hidden="true">Next &raquo;</span>
      </a>
    </li>
  </ul>
</template>

<script>
	export default {
		props: ['dataSet'], 

		data() {
			return {
				prevUrl: false, 
				page: 1,
				nextUrl: false
			}
		},

		watch: {
			dataSet() {
				this.page = this.dataSet.current_page;
				this.prevUrl = this.dataSet.prev_page_url;
				this.nextUrl = this.dataSet.next_page_url;
			},

			page() {
				this.broadcast().updateUrl();
			}
		}, 

		computed: {
			shouldPaginate() {
				return !! this.prevUrl || !! this.nextUrl;
			}
		}, 

		methods: {
			broadcast() {
				return this.$emit('changed', this.page);	
			}, 

			updateUrl() {
				history.pushState(null, null, '?page=' + this.page);
			}
		}
	}
</script>