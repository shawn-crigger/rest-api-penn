<?php

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class UserRedeemTest extends TestCase {

  public function testRedeemRoute() {

    // Set up the app
    $app = AppFactory::create();

    // Set up the points to add to the user
    $points = 10;

    // Setup User ID to add to add the points to the user.
    $id = 1;

    // Add the route to be tested
    $app->post('/users/{id}/redeem', \App\Actions\UsersRedeemAction::class);
    // Create a new GET request to the route

    $request = (new ServerRequestFactory())->createServerRequest('POST', '/users/1/redeem')->withParsedBody(['points' => $points]);

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
    $this->assertStringContainsString('Successfully 10 redeemed points', $msg, 'Testing error message');
    $this->assertTrue($success, $msg);
  }
}

