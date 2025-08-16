<?php

namespace App\Models;

use App\Models\DTO\Draw;
use App\Models\Traits\KanjiCRUD;
use App\Models\Traits\KotobaCRUD;
use PDO;
use PDOException;

class Database extends Base
{
  use KanjiCRUD;
  use KotobaCRUD;
  
  public function __construct()
  {
    parent::__construct();
  }

  // Get total database count
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

  // Get an array with total filtered and search parameters
  public function getTotalFiltered(?string $searchValue): array
  {
    $filtered = [
      'count'  => 0,
      'where'  => '',
      'params' => []
    ];

    if (!empty($searchValue)) {
      $result = parseSearchValue($searchValue);
      $filtered['where']            = $result['where'];
      $filtered['params']['search'] = "%{$result['search']}%";
    }

    try {
      $stmt = $this->conn->prepare('SELECT COUNT(*) FROM kanjis' . $filtered['where']);
      $stmt->execute($filtered['params']);
      $filtered['count']  = intval($stmt->fetchColumn());
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
    }

    return $filtered;
  }

  public function fetchKanjis(Draw $draw, array $filtered): array
  {
    try {
      $sql = "
        SELECT * FROM kanjis
        {$filtered['where']}
        ORDER BY {$draw->orderColumn} {$draw->orderDir}
        LIMIT :start, :length
      ";
      $stmt = $this->conn->prepare($sql);

      // Assign parameters
      if (!empty($filtered['params'])) {
        foreach ($filtered['params'] as $key => $value) {
          $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
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

  public function __destruct()
  {
    parent::__destruct();
  }
}
