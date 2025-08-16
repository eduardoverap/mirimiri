<?php

namespace App\Models\Traits;

use App\Models\DTO\Kotoba;
use PDOException;

trait KotobaCRUD
{
  // Update a kanji by codepoint
  public function updateKotoba(Kotoba $kotoba): int|false
  {
    // Fill params
    $params = [
      'kotoba'         => $kotoba->kotoba,
      'kotoba_id'      => $kotoba->kotobaId,
      'ent_seq'        => $kotoba->entSeq,
      'kana'           => $kotoba->kana,
      'grammar'        => $kotoba->grammar,
      'meaning_en_jmd' => $kotoba->meaningEnJmd,
      'meaning_es_jmd' => $kotoba->meaningEsJmd,
      'meaning_es'     => $kotoba->meaningEs,
      'example'        => $kotoba->example,
      'example_en'     => $kotoba->exampleEn,
      'example_es'     => $kotoba->exampleEs,
    ];

    $fieldsList = [];
    foreach ($params as $field => $value) {
      if ($field !== 'kotoba_id') {
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
        UPDATE words
        SET $paramsList
        WHERE kotoba_id = :kotoba_id
      ");
      $stmt->execute($params);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return false;
    }
  }
}
