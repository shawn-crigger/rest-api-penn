<?php

namespace App\Helpers;

use App\Models\Db;
use App\Helpers\Errors_Helper;
use Psr\Http\Message\ResponseInterface as Response;
use \PDO;

/**
 * Class to provide helper functions for validating input.
 */
class Users_Helper
{
  protected $err_handler;

  function __construct() {
    $this->err_handler = new Errors_Helper();
  }

  /**
   * Helper function to fetch user by ID from database.
   * @param $id
   * @return bool|object
   */
  public function getUserByID(Response $response, int $id)
  {
    if (!is_int($id) || $id <= 0) {
      return false;
    }
    try {
      $db = new Db();
      $conn = $db->connect();

      $sql = "SELECT * FROM users WHERE id = {$id}";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      $result = $stmt->fetch(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
      return $this->err_handler->dbErrorHandler($response, $e);
    }

    if (!is_object($result)) {
      return false;
    }

    return $result;
  }

  // ------------------------------------------------------------------------

  /**
   * Helper function to fetch user by email from database.
   *
   * @param string $email Email addresss to search for.
   * @param string $name Name to search for.
   *
   */
  public function getUerByEmailOrName(Response $response, string $email, string $name)
  {
      try {
        $db = new Db();
        $conn = $db->connect();
        $sql = "SELECT id FROM users WHERE (name = :named AND email = :email) GROUP BY id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':named', $name);
        $stmt->bindParam(':email', $email);
        $customers = $stmt->execute();
        $count = $customers->rowCount();
        if (count($count) > 0) {
          return true;
        }
      } catch (\PDOException $e) {
        return $this->err_handler->dbErrorHandler($response, $e);
      }
      return false;
    }

}
