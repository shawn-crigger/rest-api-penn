<?php

namespace App\Helpers;

use \PDOException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class to provide helper functions for validating input.
 */
class Errors_Helper
{

  /**
   * Helper function to handle DB errors.
   * @param Response $response
   * @param PDOException $e
   * @return Response
   */
  function dbErrorHandler(Response $response, $e)
  {
    $error = array(
      'successs' => false,
      "message" => $e->getMessage()
    );

    $response->getBody()->write(json_encode($error));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(500);
  }

  // ------------------------------------------------------------------------

  /**
   * Helper function to handle validation errors.
   * @param Response $response
   * @param array $errors Errors array.
   * @param int $code Status code to send to with error message.
   * @return Response
   */
  function errorHandler(Response $response, $errors, $code)
  {
    $response->getBody()->write(json_encode($errors));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus($code);
  }
}
