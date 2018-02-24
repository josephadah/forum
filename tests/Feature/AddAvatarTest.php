<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
	use RefreshDatabase;

	/**
	 @test
	 */
    public function only_authenticated_users_can_add_avatar()
    {
        $this->json('post', '/api/users/1/avatar')->assertStatus(401);
    }

    /**
     @test
     */
    public function a_valid_avatar_must_be_provided()
    {
    	$this->be(factory('App\User')->create());

    	$this->json('post', '/api/users/' . auth()->id() . '/avatar', [
    		'avatar' => 'not-a-valid image'
    	])->assertStatus(422);
    }

    /**
     @test
     */
    public function an_authenticated_user_may_upload_their_avatar_to_their_profile()
    {
    	$this->signIn();

    	Storage::fake('public');

    	$this->json('post', 'api/users/' . auth()->id() . '/avatar', [
    		'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
    	]);

    	$this->assertEquals(asset('storage/avatars/' . $file->hashName()), auth()->user()->avatar_path);

    	Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
