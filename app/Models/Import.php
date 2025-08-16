<?php

namespace App\Models;

use App\Models\DTO\Kanji;
use App\Models\DTO\Kotoba;
use PDOException;

class Import extends Base
{
  public function __construct() {
    parent::__construct();
    $this->initializeTables();
  }

  // Create tables if not exist
  private function initializeTables(): bool
  {
    $sql = '
      CREATE TABLE IF NOT EXISTS kanjis (
        kanji_id        INTEGER PRIMARY KEY AUTOINCREMENT,
        codepoint       TEXT NOT NULL,
        onyomi          TEXT,
        kunyomi         TEXT,
        nanori          TEXT,
        joyo            INTEGER,
        jlpt            INTEGER,
        meaning_en_kdic TEXT,
        meaning_es_kdic TEXT,
        meaning_es      TEXT
      );

      CREATE TABLE IF NOT EXISTS words (
        kotoba_id      INTEGER PRIMARY KEY AUTOINCREMENT,
        kotoba         TEXT NOT NULL,
        kotoba_vars    TEXT NOT NULL,
        ent_seq        TEXT,
        kana           TEXT,
        grammar        TEXT,
        meaning_en_jmd TEXT,
        meaning_es_jmd TEXT,
        meaning_es     TEXT,
        example        TEXT,
        example_en     TEXT,
        example_es     TEXT
      );
    ';
    try {
      $this->conn->exec($sql);
      return true;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      exit;
    }
  }

  // XML import: Insert from XML
  public function insertFromXML(Kanji|Kotoba $dto): bool
  {
    // Fill params
    $table = $params = null;
    if ($dto instanceof Kanji) {
      $table  = 'kanjis';
      $params = [
        'codepoint'       => $dto->codepoint,
        'onyomi'          => $dto->onyomi,
        'kunyomi'         => $dto->kunyomi,
        'nanori'          => $dto->nanori,
        'meaning_en_kdic' => $dto->meaningEnKdic,
        'meaning_es_kdic' => $dto->meaningEsKdic,
        'meaning_es'      => $dto->meaningEs,
      ];
    } else if ($dto instanceof Kotoba) {
      $table  = 'words';
      $params = [
        'kotoba'         => $dto->kotoba,
        'kotoba_vars'    => $dto->kotobaVars,
        'ent_seq'        => $dto->entSeq,
        'kana'           => $dto->kana,
        'grammar'        => $dto->grammar,
        'meaning_en_jmd' => $dto->meaningEnJmd,
        'meaning_es_jmd' => $dto->meaningEsJmd,
        'meaning_es'     => $dto->meaningEs,
        'example'        => $dto->example,
        'example_en'     => $dto->exampleEn,
        'example_es'     => $dto->exampleEs,
      ];
    } else {
      return false;
    }

    ['fields' => $fields, 'values' => $values] = $this->destructureParams(array_keys($params));

    try {
      $stmt = $this->conn->prepare("
        INSERT INTO $table ($fields) VALUES ($values)
      ");
      $stmt->execute($params);
      return true;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return false;
    }
  }

  public function __destruct()
  {
    parent::__destruct();
  }
}
