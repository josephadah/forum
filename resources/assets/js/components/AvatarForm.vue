<template>
	<div>
		<h1 v-text="profileUser.name"></h1>
		<h5>Member since {{ profileUser.created_at }}</h5>

			<form v-show="canUpdate" method="POST" enctype="multipart/form-data">
				<input name="avatar" type="file" accept="image/*" @change="onChange">
				<button type="submit" class="btn btn-primary btn-xs">Add Avatar</button>
			</form>
			<img :src="avatar" width="200" height="200">
	</div>
</template>

<script>
	export default {
		props: ['profileUser'], 
		data() {
			return {
				avatar: this.profileUser.avatar_path
			}
		}, 
		computed: {
			canUpdate() {
				return this.authorize(user => user.id === this.profileUser.id)
			}
		}, 
		methods: {
			onChange(e) {
				if (! e.target.files.length) return;

				let file = e.target.files[0];

				let reader = new FileReader();

				reader.readAsDataURL(file);

				reader.onload = e => {
					this.avatar = e.target.result;
				}

				// persist the data to server
				this.persist(file);
			}, 
			persist(file) {
				let data = new FormData();
				data.append('avatar', file);
				axios.post('/api/users/${this.profileUser.name}/avatar', data)
					.then(() => flash('Avatar Uploaded!'));
			}
		}
	}
</script>

<style>
	button {
	}
</style>