<?php

namespace App\Models;

use \PDO;
/**
 * A very simple PDO database class to connect to the database.
 */
class Db
{
  /**
   * @var private string Holds the DB Server Host
   */
  private $host = '';
  /**
   * @var private string User name to the database.
   */
  private $user = '';
  /**
   * @var private string Holds the password to the db user
   */
  private $pass = '';
  /**
   * @var private string Holds the database name
   */
  private $dbname = '';

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
    return $this;
  }

  // ------------------------------------------------------------------------

  /**
   * Returns the DB server connection.
   * @return object Returns the DB Server Host
   */
  public function connect() : object
  {
    $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
    $conn = new PDO($conn_str, $this->user, $this->pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
  }
}
