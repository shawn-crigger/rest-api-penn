<?php
namespace Tests;

use PHPUnit\Framework\TestCase as PHPUNIT_TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use App\Models\Db;

Class TestCase extends PHPUNIT_TestCase
{
  public $user;
  public $lastID;

  public function getUser()
  {
    $SQL = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
    $db = new Db();
    $conn = $db->connect();
    $stmt = $conn->query($SQL);
    $user = $stmt->fetch(\PDO::FETCH_NAMED);
    $this->user = (object) $user;
    $this->lastID = $this->user->id;
    unset($db);
    return $this->user;
  }

   // ------------------------------------------------------------------------

  public function getUserPoints()
  {
    $id  = $this->getUserID();
    $SQL = "SELECT points_balance FROM users WHERE id = {$id} ORDER BY id DESC LIMIT 1";
    $db = new Db();
    $conn = $db->connect();
    $stmt = $conn->query($SQL);
    $points = (int) $stmt->fetchColumn();
    unset($db);
    return $points;
  }

  // ------------------------------------------------------------------------

  public function getUserID()
  {
    if (!is_int($this->lastID) OR !is_object($this->user)) {
      $this->getUser();
    }

    return $this->user->id;
  }

}