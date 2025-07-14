<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\DTO\Draw;
use PDOException;

class TableController
{
  private Draw   $draw;
  private array  $columns;
  private ?array $kanjiList = null;

  public function __construct(
  ) {
    if (isset($_GET['draw'])) {
      $this->columns = ['KanjiID', 'Kanji', 'Joyo', 'JLPT', 'Actions'];
      $this->draw = new Draw($this->columns);
      $this->draw->drawCount        = (int)    $_GET['draw'];
      $this->draw->start            = (int)    $_GET['start'];
      $this->draw->length           = (int)    $_GET['length'];
      $this->draw->orderColumnIndex = (int)    $_GET['order'][0]['column'] ?? 0;
      $this->draw->orderDir         = (string) $_GET['order'][0]['dir'] ?? 'asc';
      $this->draw->searchValue      = (string) $_GET['search']['value'] ?? null;
    } else {
      $data = json_decode(file_get_contents('php://input'), true);
      $this->columns = ['Order', 'Kanji', 'Frequency', 'Readings', 'Joyo', 'JLPT', 'Meaning'];
      $this->draw = new Draw($this->columns);
      $this->draw->drawCount        = (int)    $data['draw'];
      $this->draw->start            = (int)    $data['start'];
      $this->draw->length           = (int)    $data['length'];
      //$this->draw->orderColumnIndex = (int)    $data['order'][0]['column'] ?? 0;
      //$this->draw->orderDir         = (string) $data['order'][0]['dir'] ?? 'asc';
      $this->draw->searchValue      = (string) $data['search']['value'] ?? null;

      $this->kanjiList = $data['kanjiList'];
    }
  }

  private function ajaxAdminFetch(): array
  {
    try {
      $model = new Database();

      // Get total unfiltered
      $this->draw->recordsTotal = $model->getTotalUnfiltered();

      // Get total filtered and params
      $filtered = $model->getTotalFiltered($this->draw->searchValue);
      $this->draw->recordsFiltered = $filtered['count'];

      // Fetch desired kanjis and render the table
      $kanjis = $model->fetchKanjis($this->draw, $filtered);
      $data = $this->renderAdminTable($kanjis);
      return $data;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return [];
    }
  }

  private function ajaxHomeFetchAndRender(): array
  {
    try {
      $model = new Database();

      $data = [];
      foreach($this->kanjiList as $kanji => $kanjiData) {
        $codepoint = dechex(mb_ord($kanji));
        $kanjiInfo = $model->selectKanji($codepoint);
        $meaning   = '';
        $readings  = [];
        $joyo = $jlpt = null;

        if (!empty($kanjiInfo->onyomi))  $readings[] = 'On\'yomi: ' . $kanjiInfo->onyomi;
        if (!empty($kanjiInfo->kunyomi)) $readings[] = 'Kun\'yomi: ' . $kanjiInfo->kunyomi;
        if (!empty($kanjiInfo->nanori))  $readings[] = 'Nanori: ' . $kanjiInfo->nanori;

        if (!empty($kanjiInfo->joyo)) $joyo = (int) $kanjiInfo->joyo;
        if (!empty($kanjiInfo->jlpt)) $jlpt = (int) $kanjiInfo->jlpt;

        if (!empty($kanjiInfo->meaningENKDIC)) $meaning = $kanjiInfo->meaningENKDIC;

        $data[] = [
          $kanjiData['order'],
          $kanji,
          $kanjiData['count'],
          implode('<br />', $readings),
          $joyo,
          $jlpt,
          $meaning
        ];
      }
      return $data;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return [];
    }
  }

  private function renderAdminTable(array $kanjis): array
  {
    $data = [];
    foreach ($kanjis as $kanji) {
      $id        = (int)    $kanji['KanjiID'];
      $codepoint = (string) $kanji['Codepoint'];
      $joyo = $jlpt = null;
      if (!empty($kanji['Joyo'])) $joyo = (int) $kanji['Joyo'];
      if (!empty($kanji['JLPT'])) $jlpt = (int) $kanji['JLPT'];

      $data[] = [
        $id,
        mb_chr(hexdec($codepoint)),
        $joyo,
        $jlpt,
        BTN_ACTIONS
      ];
    }
    return $data;
  }

  public function index(): void
  {
    if (
      $_SERVER['REQUEST_METHOD'] === 'GET' &&
      isset($_GET['draw'])
    ) {
      $data = $this->ajaxAdminFetch();
    } else if (
      $_SERVER['REQUEST_METHOD'] === 'POST' &&
      isset($_SERVER['HTTP_ACCEPT']) &&
      strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
    ) {
      $data = $this->ajaxHomeFetchAndRender();
    } else {
      goHome();
    }
    header("Content-type: application/json; charset=utf-8");
    echo json_encode([
      "draw" => $this->draw,
      "data" => $data,
      "recordsTotal" => $this->draw->recordsTotal,
      "recordsFiltered" => $this->draw->recordsFiltered
    ]);
  }
}
