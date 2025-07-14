<?php

namespace App\Models;

use App\Models\Base;

class CSVImport extends Base
{
  public function __construct()
  {
    parent::__construct();
  }

  public function updateFromCSV(string $kanji, string $field, mixed $value)
  {
    $codepoint = (string) dechex(mb_ord($kanji));
    $sql = "UPDATE kanjis SET $field = :value WHERE Codepoint = :codepoint";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      'value'     => $value,
      'codepoint' => $codepoint
    ]);
  }

  public function __destruct()
  {
    parent::__destruct();
  }
}
