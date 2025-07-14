<?php

namespace App\Models;

use App\Models\Base;
use PDOException;

class XMLImport extends Base
{
  public function __construct() {
    parent::__construct();
    $this->initializeTable();
  }

  private function initializeTable(): void
  {
    // Drop previous table and create a new blank one
    $sql = <<<HERESQL
    CREATE TABLE IF NOT EXISTS kanjis (
      KanjiID       INTEGER PRIMARY KEY AUTOINCREMENT,
      Codepoint     TEXT NOT NULL,
      Onyomi        TEXT,
      Kunyomi       TEXT,
      Nanori        TEXT,
      Joyo          INTEGER,
      JLPT          INTEGER,
      MeaningENKDIC TEXT,
      MeaningESKDIC TEXT,
      MeaningES     TEXT,
      MeaningQU     TEXT
    );
    HERESQL;
    try {
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
    }
  }

  public function insertFromKANJIDIC(array $data): void
  {
    try {
      $stmt = $this->conn->prepare("
        INSERT INTO kanjis (Codepoint, Onyomi, Kunyomi, Nanori, MeaningENKDIC, MeaningESKDIC, MeaningES)
        VALUES (:codepoint, :onyomi, :kunyomi, :nanori, :meaningenkdic, :meaningeskdic, :meaninges)
      ");
      $stmt->execute([
        ':codepoint'     => $data['codepoint'],
        ':onyomi'        => $data['onyomi'],
        ':kunyomi'       => $data['kunyomi'],
        ':nanori'        => $data['nanori'],
        ':meaningenkdic' => $data['meaning_en'],
        ':meaningeskdic' => $data['meaning_es'],
        ':meaninges'     => $data['meaning_es']
      ]);
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
    }
  }

  public function __destruct()
  {
    parent::__destruct();
  }
}
