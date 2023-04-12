<?php

namespace App\Models;
use App\Helpers\Errors_Helper;
use \PDO;
/**
 * A very simple PDO database class to connect to the database.
 */
class Db
{
  /**
   * @var private string Holds the DB Server Host
   */
  private string $host = '';
  /**
   * @var private string User name to the database.
   */
  private string $user = '';
  /**
   * @var private string Holds the password to the db user
   */
  private string $pass = '';
  /**
   * @var private string Holds the database name
   */
  private string $dbname = '';
  /**
   * @var private string Holds the password to the db user
   */
  private string $charset = '';
  /**
   * @var private string Holds the database name
   */
  private string $port = '';

  // ------------------------------------------------------------------------

  /**
   * Setups up the private variables to the class
   */
  public function __construct()
  {
    $dir  = dirname(__FILE__);
    $base = str_replace('src/Models', '', $dir);
    $settings = parse_ini_file($base. '/settings.ini');
    unset($dir, $base);
    $this->host = $settings['DB_HOST'];
    $this->user = $settings['DB_USER'];
    $this->pass = $settings['DB_PASS'];
    $this->dbname = $settings['DB_NAME'];
    $this->port = $settings['DB_PORT'];
    $this->charset = $settings['DB_CHARSET'];
    return $this;
  }

  // ------------------------------------------------------------------------

  /**
   * Returns the DB server connection.
   * @return object Returns the DB Server Host
   */
  public function connect() : object
  {
    $conn = new \PDO("mysql:host={$this->host};dbname={$this->dbname};port={$this->port};charset={$this->charset}", $this->user, $this->pass, [
      \PDO::ATTR_EMULATE_PREPARES => false,
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ]);
    return $conn;
  }
}
