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

  protected function destructureParams(array $fieldsList): array
  {
    $valuesList = array_map(fn($field) => ":{$field}", $fieldsList);
    return [
      'fields' => implode(', ', $fieldsList),
      'values' => implode(', ', $valuesList),
    ];
  }

  public function __destruct()
  {
    $this->conn = null;
  }
}
