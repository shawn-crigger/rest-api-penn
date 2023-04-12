<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use App\Models\Db;

/**
 * Gets all users in the database.
 *
 * @param Request $request
 * @param Response $response
 * @param array $args
 * @return Response
 */
final class UsersGetAction
{

  /**
   * @inheritdoc
   */
  public function __invoke(ServerRequest $request, Response $response, array $args = []): Response
  {
    $sql = "SELECT * FROM users";

    try {
      $db = new Db();
      $conn = $db->connect();
      $stmt = $conn->query($sql);
      $customers = $stmt->fetchAll(\PDO::FETCH_OBJ);
      unset($db);
      if (empty($customers)) {
        $customers = ['success' => false, 'message' => 'No users found'];
      }

      $results = ["success" => true, "message" => 'Sucessfuly returned all the users', "users" => $customers];
      $response->getBody()->write(json_encode($results));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (\PDOException $e) {
      $handler = new \App\Helpers\Errors_Helper();
      return $handler->dbErrorHandler($response, $e);
    }
  }
}