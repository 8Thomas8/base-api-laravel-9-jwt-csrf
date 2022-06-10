<?php
// Auth routes
test('POST Route /api/v1/register exist', function () {
    $response = $this->post('/api/v1/register');
    expect($response->getStatusCode())->not()->toBe(404);
});

test('POST Route /api/v1/login exist', function () {
    $response = $this->post('/api/v1/login');
    expect($response->getStatusCode())->not()->toBe(404);
});

test('POST Route /api/v1/logout exist', function () {
    $response = $this->post('/api/v1/logout');
    expect($response->getStatusCode())->not()->toBe(404);
});

test('POST Route /api/v1/refresh exist', function () {
    $response = $this->post('/api/v1/refresh');
    expect($response->getStatusCode())->not()->toBe(404);
});

test('GET Route /api/v1/me exist', function () {
    $response = $this->get('/api/v1/me');
    expect($response->getStatusCode())->not()->toBe(404);
});

// Users routes
test('GET Route /api/v1/user exist', function () {
    $response = $this->get('/api/v1/user');
    expect($response->getStatusCode())->not()->toBe(404);
});
test('GET Route /api/v1/user/1 exist', function () {
    $response = $this->get('/api/v1/user/1');
    expect($response->getStatusCode())->not()->toBe(404);
});
test('PATCH Route /api/v1/user/1 exist', function () {
    $response = $this->patch('/api/v1/register');
    expect($response->getStatusCode())->not()->toBe(404);
});
test('DELETE Route /api/v1/user/1 exist', function () {
    $response = $this->delete('/api/v1/user/1');
    expect($response->getStatusCode())->not()->toBe(404);
});
