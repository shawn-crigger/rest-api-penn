<?php

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class CreateUserTest extends TestCase {

  public function testCreateUserRoute() {

    // Set up the app
    $app = AppFactory::create();

    // Add the route to be tested
    $app->post('/users', \App\Actions\UsersCreateAction::class);
    // Create a new GET request to the route

    $val  = rand(1, 100);
    $name = 'Test User-' . $val;
    $email = 'testuser' . $val . '@gmail.com';

    $request = (new ServerRequestFactory())->createServerRequest('POST', '/users')->withParsedBody(
      [
        'name' => $name,
        'email' => $email,
      ]
    );

    // Invoke the application
    $response = $app->handle($request);

    // Assert that the response status code is 200
    $this->assertEquals(200, $response->getStatusCode());

    $body = (array) json_decode($response->getBody());
    $success = (bool) $body['success'];
    $msg     = $body['message'];

    // Asset that the user was successfully deleted
    $this->assertIsArray($body);
    $this->assertArrayHasKey('message', $body, $msg);
    $this->assertStringContainsString('Successfully added new user', $msg, 'Testing error message');
    $this->assertTrue($success, $msg);
  }
}

