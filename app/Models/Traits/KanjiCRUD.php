<?php

namespace App\Models\Traits;

use PDO;
use PDOException;
use App\Models\DTO\Kanji;

trait KanjiCRUD
{
  // Read a kanji by codepoint
  public function selectKanji(string $codepoint, array $fields = []): ?Kanji
  {
    $fieldsList = ($fields !== []) ? implode(', ', $fields) : '*';

    try {
      $stmt = $this->conn->prepare("
        SELECT $fieldsList FROM kanjis WHERE codepoint = :codepoint LIMIT 1;
      ");
      $stmt->execute([
        'codepoint' => strtolower((string) $codepoint)
      ]);

      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($data !== false) {
        $kanji = new Kanji(codepoint: $data['codepoint']);
        foreach ($data as $key => $value) {
          $property = snakeToCamel($key);
          $kanji->$property = $value;
        }
        return $kanji;
      } else {
        return null;
      }
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return null;
    }
  }

  // Update a kanji by codepoint
  public function updateKanji(Kanji $kanji): int|false
  {
    // Fill params
    $params = [
      'onyomi'          => $kanji->onyomi,
      'kunyomi'         => $kanji->kunyomi,
      'nanori'          => $kanji->nanori,
      'joyo'            => $kanji->joyo,
      'jlpt'            => $kanji->jlpt,
      'meaning_en_kdic' => $kanji->meaningEnKdic,
      'meaning_es_kdic' => $kanji->meaningEsKdic,
      'meaning_es'      => $kanji->meaningEs,
      'codepoint'       => $kanji->codepoint,
    ];

    $fieldsList = [];
    foreach ($params as $field => $value) {
      if ($field !== 'codepoint') {
        if ($value !== null && $value !== '') {
          $fieldsList[] = "{$field} = :{$field}";
        } else {
          unset($params[$field]);
        }
      }
    }
    $paramsList = implode(', ', $fieldsList);
    echo $paramsList;

    try {
      $stmt = $this->conn->prepare("
        UPDATE kanjis
        SET $paramsList
        WHERE codepoint = :codepoint
      ");
      $stmt->execute($params);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return false;
    }
  }

  // Delete a kanji by codepoint
  public function deleteKanji(string $codepoint): int|false
  {
    try {
      $stmt = $this->conn->prepare('
        DELETE FROM kanji WHERE codepoint = :codepoint
      ');
      $stmt->execute([
        'codepoint' => strtolower((string) $codepoint)
      ]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return false;
    }
  }
}
