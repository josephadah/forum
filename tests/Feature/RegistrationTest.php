<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
    	Mail::fake();

    	event(new Registered(factory('App\User')->create()));

    	Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function a_new_user_can_fully_confirm_their_email_address()
    {
        Mail::fake();

    	$this->withoutExceptionHandling();
    	$this->post('/register', [
    		'name' => 'Joe', 
    		'email' => 'joe@mail.com', 
    		'password' => 'password', 
    		'password_confirmation' => 'password'
    	]);

    	$user = User::whereName('Joe')->first();

    	$this->assertFalse($user->confirmed);

    	$this->assertNotNull($user->confirmation_token);

    	// let the user confirm their account. 
    	$response = $this->get('/register/confirm?token=' . $user->confirmation_token);

    	$this->assertTrue($user->fresh()->confirmed);

    	$response->assertRedirect('/threads');
    }

    /** @test */ 
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
