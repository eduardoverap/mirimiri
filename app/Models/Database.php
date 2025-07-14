<?php

namespace App\Models;

use App\Models\Base;
use App\Models\DTO\Draw;
use App\Models\DTO\Kanji;
use PDO;
use PDOException;

class Database extends Base
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getTotalUnfiltered(): int
  {
    try {
      $stmt = $this->conn->query('SELECT COUNT(*) FROM kanjis');
      return intval($stmt->fetchColumn());
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return 0;
    }
  }

  public function getTotalFiltered(?string $searchValue): array
  {
    if (!empty($searchValue)) {
      $result = parseSearchValue($searchValue);
      $where  = $result['where'];
      $params['search'] = "%{$result['search']}%";
    } else {
      $where  = '';
      $params = [];
    }

    try {
      $stmt = $this->conn->prepare('SELECT COUNT(*) FROM kanjis' . $where);
      $stmt->execute($params);
      return [
        'count'  => intval($stmt->fetchColumn()),
        'where'  => $where,
        'params' => $params
      ];
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return [
        'count'  => 0,
        'where'  => '',
        'params' => []
      ];
    }
  }

  public function fetchKanjis(Draw $draw, array $filtered): array
  {
    try {
      $sql = "
        SELECT KanjiID, Codepoint, Joyo, JLPT
        FROM kanjis
        {$filtered['where']}
        ORDER BY {$draw->orderColumn} {$draw->orderDir}
        LIMIT :start, :length
      ";
      $stmt = $this->conn->prepare($sql);

      // Assign parameters
      foreach ($filtered['params'] as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
      }
      $stmt->bindValue('start', $draw->start, PDO::PARAM_INT);
      $stmt->bindValue('length', $draw->length, PDO::PARAM_INT);

      // Execute and return
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return [];
    }
  }

  public function selectKanji(string $codepoint): ?Kanji
  {
    try {
      $stmt = $this->conn->prepare("
        SELECT * FROM kanjis WHERE Codepoint = :codepoint LIMIT 1
      ");
      $stmt->execute([
        'codepoint' => strtolower((string) $codepoint)
      ]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $kanji = new Kanji(
        $data['KanjiID'],
        $data['Codepoint'],
        $data['Onyomi'],
        $data['Kunyomi'],
        $data['Nanori'],
        $data['Joyo'],
        $data['JLPT'],
        $data['MeaningENKDIC'],
        $data['MeaningESKDIC'],
        $data['MeaningES'],
        $data['MeaningQU']
      );
      return $kanji;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return null;
    }
  }

  public function updateKanji(array $arrData): void
  {
    try {
      $stmt = $this->conn->prepare("
        UPDATE kanjis
        SET Onyomi  = :onyomi,
          Kunyomi   = :kunyomi,
          Nanori    = :nanori,
          Joyo      = :joyo,
          JLPT      = :jlpt,
          MeaningES = :meaninges,
          MeaningQU = :meaningqu
        WHERE Codepoint = :codepoint
      ");
      $stmt->execute([
        'onyomi'    => $arrData['Onyomi'],
        'kunyomi'   => $arrData['Kunyomi'],
        'nanori'    => $arrData['Nanori'],
        'joyo'      => $arrData['Joyo'],
        'jlpt'      => $arrData['JLPT'],
        'meaninges' => $arrData['MeaningES'],
        'meaningqu' => $arrData['MeaningQU'],
        'codepoint' => $arrData['Codepoint']
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
