<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $attributes = ['email' => 'email@test.fr', 'password' => 'testpassword', "password_confirmation" => "testpassword"];
    $this->post('/api/v1/register', $attributes);
    return 'BeforeEach';
});


test('User can login', function () {
    $attributes = ['email' => 'email@test.fr', 'password' => 'testpassword'];
    $response = $this->post('/api/v1/login', $attributes);
    $response->assertStatus(200);
    expect($response->headers->getCookies()[0]->getValue())->not()->toBeEmpty()
        ->and($response->headers->getCookies()[0]->getValue())->not()->toBeNull()
    ->and($response->json()['csrf_token'])->not()->toBeEmpty()
    ->and($response->json()['csrf_token'])->not()->toBeEmpty();
});

test('User can get his data', function () {
    $attributes = ['email' => 'email@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->get('/api/v1/me');
    $response2->assertStatus(200);

    $response2->assertJson(['email' => 'email@test.fr']);
});

test('User can refresh JWT Token', function () {
    $attributes = ['email' => 'email@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->post('/api/v1/refresh');
    $response2->assertStatus(200);
    expect($response2->headers->getCookies()[0]->getValue())->not()->toBeEmpty()
        ->and($response1->headers->getCookies()[0]->getValue())->not()->toBe($response2->headers->getCookies()[0]->getValue());
});

test('User can logout', function () {
    $attributes = ['email' => 'email@test.fr', 'password' => 'testpassword'];
    $response1 = $this->post('/api/v1/login', $attributes);

    $CSRF_TOKEN= $response1->json()['csrf_token'];
    $JWT_TOKEN = $response1->headers->getCookies()[0]->getValue();

    $response2 = $this->withCookie('token',$JWT_TOKEN)->withHeaders(['Accept' => 'application/json', 'CSRF-TOKEN' => $CSRF_TOKEN])->post('/api/v1/logout');
    $response2->assertStatus(200);
    expect($response2->headers->getCookies())->toBeEmpty();
});
