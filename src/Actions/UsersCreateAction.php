<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use App\Models\Db as Db;

final class UsersCreateAction
{

  public function __invoke(ServerRequest $request, Response $response, array $args = []): Response
  {
    $data = $request->getParsedBody();
    $name = (isset($data['name'])) ? $data['name'] : $args['name'];
    $email = (isset($data['email'])) ? $data['email'] : $args['email'];
    $points = 0;

    $val = new \App\Helpers\Validation_Helper();
    $error_handler = new \App\Helpers\Errors_Helper();
    $users_val = new \App\Helpers\Users_Helper();

    try {
      $db = new Db();
      $conn = $db->connect();

      $errors = [];
      $errors = array_merge($val->validateName(($name)), $val->validateEmail($email, $email));

      $user_exists = $users_val->getUerByEmailOrName($response, $email, $name);
      if (TRUE == $user_exists) {
        $user_error = ['success' => false, 'error' => 'User already exists'];
        array_merge($errors, $user_error);
        unset($user_error);
      }

      if (!empty($errors) && count($errors) > 0) {
        $response->getBody()->write(json_encode($errors));
        return $response
          ->withHeader('content-type', 'application/json')
          ->withStatus(400);
      }

      $sql = "INSERT INTO users (name, email, points_balance) VALUES (:name, :email, :points)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':points', $points);

      $result = $stmt->execute();
      if ($result !== false) {
        $result = ['success' => true, 'message' => "Successfully added new user {$name} with 0 points."];
      }

      $db = null;
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (\PDOException $e) {
      return $error_handler->dbErrorHandler($response, $e);
    }
  }
}
