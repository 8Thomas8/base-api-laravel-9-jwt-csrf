<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $attributes1 = ['email' => 'email1@test.fr', 'password' => 'testpassword', "password_confirmation" => "testpassword"];
    $this->post('/api/v1/register', $attributes1);
    $attributes2 = ['email' => 'email2@test.fr', 'password' => 'testpassword', "password_confirmation" => "testpassword"];
    $this->post('/api/v1/register', $attributes2);
    return 'BeforeEach';
});

test('Get all users', function () {
    $attributes = ['email' => 'email1@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->get('/api/v1/user');
    $response2->assertStatus(200);
    expect($response2->json()[0][0])->toHaveKey('email', 'email1@test.fr')
        ->and($response2->json()[0][1])->toHaveKey('email', 'email2@test.fr');
});

test('Get one user', function () {
    $attributes = ['email' => 'email1@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $id = User::where('email', 'email2@test.fr')->first()->id;

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->get('/api/v1/user/'.$id);
    $response2->assertStatus(200);
    $response2->json(['email' => 'email2@test.fr']);
});

test('Update one user', function () {
    $attributes = ['email' => 'email1@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $id = User::where('email', 'email2@test.fr')->first()->id;

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->patch('/api/v1/user/'.$id, ['email' => 'email3@test.fr']);
    $response2->assertStatus(200);
    $response2->json(['message' => 'User successfully updated']);
    $this->assertDatabaseHas('users', ['email' => 'email3@test.fr']);
    $this->assertDatabaseMissing('users', ['email' => 'email2@test.fr']);
});

test('Delete user', function () {
    $attributes = ['email' => 'email1@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $id = User::where('email', 'email2@test.fr')->first()->id;

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->delete('/api/v1/user/'.$id);
    $response2->assertStatus(204);
    $this->assertDatabaseMissing('users', ['email' => 'email2@test.fr']);
});
