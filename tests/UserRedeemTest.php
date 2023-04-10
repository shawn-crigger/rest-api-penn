<?php

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase as TestCase;
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

    $existing_points = $this->getUserPoints();

    // Set up the points to add to the user
    $points = 10;

    $expected_total = $existing_points - $points;
    // Setup User ID to add to add the points to the user.
    $id = $this->getUserID();

    // Add the route to be tested
    $app->post('/users/{id}/redeem', \App\Actions\UsersRedeemAction::class);
    // Create a new GET request to the route
    $url = '/users/' . $id . '/redeem';
    $request = (new ServerRequestFactory())->createServerRequest('POST', $url)->withParsedBody(['points' => $points]);

    // Invoke the application
    $response = $app->handle($request);

    // Assert that the response status code is 200
    $this->assertEquals(200, $response->getStatusCode());

    // Get the new total points after modifying them
    $total_points = $this->getUserPoints();

    $body = (array) json_decode($response->getBody());
    $success = (bool) $body['success'];
    $msg     = $body['message'];

    // Test that the user was successfully modified.
    $this->assertIsArray($body);
    // Test that the response has a message
    $this->assertArrayHasKey('message', $body, $msg);
    // Test the total points equal points earned + existing points.
    $this->assertEquals($expected_total, $total_points);
    // Test that the response message is correct.
    $this->assertStringContainsString('Successfully ' . $points .' redeemed points', $msg, 'Testing error message');
    // Test that the success response key is true;
    $this->assertTrue($success, $msg);
  }
}
