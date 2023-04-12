<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use App\Models\Db as Db;

/**
 * Delete a user by their ID.
 *
 * @param Request $request
 * @param Response $response
 * @param array $args
 * @return Response
 */
final class UsersDeleteAction
{

  /**
   * @inheritDoc
   */
  public function __invoke(ServerRequest $request, Response $response, array $args = []): Response
  {
    $data = $request->getParsedBody();
    $id = (int) (isset($data['id'])) ? $data['id'] : $args['id'];
    $error_handler = new \App\Helpers\Errors_Helper();
    $user_helper = new \App\Helpers\Users_Helper();
    $val = new \App\Helpers\Validation_Helper();
    $isValidID = $val->validateID($id);
    if (!$isValidID) {
      $errors = ['success' => false, 'message' => 'Invalid ID parameter'];
      return $error_handler->errorHandler($response, $errors, 422);
    }

    $user = $user_helper->getUserByID($response, $id);
    if (!is_object($user))
    {
      $errors = ['success' => false, 'message' => "User with ID:{$id} does not exist."];
      return $error_handler->errorHandler($response, $errors, 400);
    }


    try {
      $db = new Db();
      $conn = $db->connect();
      $sql = "DELETE FROM users WHERE id = {$id}";

      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      $deleted = $stmt->rowCount();
      $result = ['success' => true, 'message' => 'Successfully deleted user'];
      $code = 200;
      if (!$deleted or $deleted <= 0) {
        $result = ['success' => false, 'message' => 'User not able to be deleted user'];
        $code = 422;
      }

      $db = null;

      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus($code);
    } catch (\PDOException $e) {
      return $error_handler->dbErrorHandler($response, $e);
    }
  }
}
