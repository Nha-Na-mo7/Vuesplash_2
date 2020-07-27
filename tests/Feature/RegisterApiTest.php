<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterApiTest extends TestCase
{
  use RefreshDatabase;
  
  /**
   * @test
   */
  
  public function should_newUserMakeAndReturn() {
    $data = [
        'name' => 'vuesplash user',
        'email' => '111111@gmail.com',
        'password' => '111111',
        'password_confirmation' => '111111',
    ];
  
    $response = $this->json('POST', route('register'), $data);
  
    $user = User::first();
    $this->assertEquals($data['name'], $user->name);
    
    $response
        ->assertStatus(201)
        ->assertJson(['name' => $user->name]);
  }


  
}
