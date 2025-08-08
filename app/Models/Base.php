<?php

namespace App\Models;

use PDO;

class Base
{
  protected ?PDO $conn;

  protected function __construct()
  {
    require DB_CONN;
    $this->conn = setConn();
  }

  public function getConnection(): PDO
  {
    return $this->conn;
  }

  public function __destruct()
  {
    $this->conn = null;
  }
}
