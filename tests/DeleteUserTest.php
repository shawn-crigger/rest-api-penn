<?php

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase as TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use App\Models\Db;

class DeleteUserTest extends TestCase {

  public function testDeleteRoute() {

    // Set up the app
    $app = AppFactory::create();

    $lastId = $this->getUserID();    
    $url = '/users/' . $lastId;
    // Add the route to be tested
    $app->delete('/users/{id}', \App\Actions\UsersDeleteAction::class);
    // Create a new GET request to the route
    $request = (new ServerRequestFactory())->createServerRequest('DELETE', $url);

    // Invoke the application
    $response = $app->handle($request);

    // Assert that the response status code is 200
    $this->assertEquals(200, $response->getStatusCode());

    $body = (array) json_decode($response->getBody());
    $success = (bool) $body['success'];
    $msg     = $body['message'];

    // Asset that the user was successfully deleted
    $this->assertIsArray($body);
    $this->assertArrayHasKey('message', $body);
    $this->assertTrue($success, $msg);
  }
}
