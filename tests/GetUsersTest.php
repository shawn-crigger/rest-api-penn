<?php

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class GetUsersTest extends TestCase {

  public function testGetRoute() {

    // Set up the app
    $app = AppFactory::create();

    // Add the route to be tested
    $app->get('/users', \App\Actions\UsersGetAction::class);

    // Create a new GET request to the route
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/users');

    // Invoke the application
    $response = $app->handle($request);

    // Assert that the response status code is 200
    $this->assertEquals(200, $response->getStatusCode());

    $body = (array) json_decode($response->getBody());
    $success = $body['success'];
    $users = $body['users'];
    $msg = $body['message'];
    $count = count($users);

    $this->assertIsArray($body);
    $this->assertArrayHasKey('message', $body);
    $this->assertTrue($success, $msg);
    $this->assertCount($count, $users, $msg);
  }
}

