<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_can_not_favorite_anything()
    {
        $this->withExceptionHandling();

    	$this->post('replies/1/favorites')
    		->assertRedirect('login');
    }


    /** @test */
    public function an_authenticate_user_can_favorite_any_reply()
    {
    	$this->be($user = factory('App\User')->create());

    	$reply = factory('App\Reply')->create();

    	$this->post('replies/' . $reply->id . '/favorites');

    	$this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticate_user_can_unfavorite_any_reply()
    {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');

        $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_only_favorite_any_reply_once()
    {
    	$this->be($user = factory('App\User')->create());

    	$reply = factory('App\Reply')->create();

    	try {
    		$this->post('replies/' . $reply->id . '/favorites');
	    	$this->post('replies/' . $reply->id . '/favorites');
    	} catch (\Exception $e) {
    		$this->fail('You can only favorite a reply once');
    	}

    	$this->assertCount(1, $reply->favorites);
    }

    /** @test */
   public function delete_favorite_when_reply_is_deleted()
   {
      $this->be($user = factory('App\User')->create());
      $reply = factory('App\Reply')->create(['user_id' => $user->id]);
      $favorite = factory('App\Favorite')->create(['user_id' => $user->id, 'favorited_id' => $reply->id]);

      $this->delete('/reply/'.$reply->id);
      $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
      $this->assertDatabaseMissing('favorites', [
        'id' => $favorite->id, 'user_id' => $user->id, 'favorited_id' => $reply->id
    ]);
   }
}
