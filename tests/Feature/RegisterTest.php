<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class)->beforeEach(function () {
    return 'BeforeEach';
});

test('User can register', function () {
    $attributes = ['email' => 'email@test.fr', 'password' => 'testpassword', "password_confirmation" => "testpassword"];
    $response = $this->post('/api/v1/register', $attributes);
    $response->assertStatus(201)->assertJson(['message' => 'User successfully registered']);
    $attributes = ['email' => 'email@test.fr'];
    $this->assertDatabaseHas('users', $attributes);
});
