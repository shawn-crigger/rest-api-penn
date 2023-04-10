<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use App\Models\Db;

final class UsersEarnAction
{

  public function __construct()
  {
  }

  /**
   * Earn points for a user. The request should include the number of points to
   *
   * @param Request $request
   * @param Response $response
   * @param array $args
   * @return Response
   */
  public function __invoke(ServerRequest $request, Response $response, array $args = []): Response
  {
    $data = $request->getParsedBody();

    $id = (int) (isset($data['id'])) ? $data['id'] : $args['id'];
    $points = (int) (isset($data['points'])) ? $data['points'] : $args['points'];
    if (!isset($points)) {
      $err = new \App\Helpers\Errors_Helper();
      $errors = ['success' => false, 'message' => 'Missing parameter points'];
      return $err->errorHandler($response, $errors, 400);
      unset($err);
    }

    $user_helper = new \App\Helpers\Users_Helper();
    $user = $user_helper->getUserByID($response, $id);
    $existing_points = 0;
    if (is_object($user)) {
      $existing_points = $user->points_balance;
      $user_name = $user->name;
    }
    $new_points = $points + $existing_points;

    $sql = "UPDATE users SET points_balance = :points WHERE id = {$id}";
    try {
      $db = new Db();

      $conn = $db->connect();
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':points', $new_points);
      $result = $stmt->execute();

      $db = null;
      $customers = ['success' => true, 'message' => "User {$user_name} Successfully earned {$points} points"];
      $code = 200;
      if ($result === false) {
        $customers = ['success' => false, 'message' => 'Points could not be updated'];
        $code = 422;
      }

      $response->getBody()->write(json_encode($customers));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus($code);
    } catch (\PDOException $e) {
      $handler = new \App\Helpers\Errors_Helper();
      return $handler->dbErrorHandler($response, $e);
    }
  }
}
