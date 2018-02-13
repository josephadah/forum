<favorite :reply="{{ $reply }}" inline-template>
	<template>
		<button :class="classes" @click="toggle" v-if="{!! Auth::check() !!}">
		    <span class="glyphicon glyphicon-heart"></span>
		    <span v-text="favoritesCount"></span>
		</button>

		<button v-else>
			<form method="POST" action="{{ route('favorites.store', $reply->id) }}">
				<button type="submit">
					    <span class="glyphicon glyphicon-heart"></span>
					    <span v-text="favoritesCount"></span>
				</button>
			</form>
		</button>
	</template>
</favorite>