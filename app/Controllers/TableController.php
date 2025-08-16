<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\DTO\Draw;
use PDOException;

class TableController
{
  const TBL_COLUMNS = [
    'home'  => ['Order', 'Kanji', 'Frequency', 'Readings', 'Joyo', 'JLPT', 'Meaning'],
    'kanji' => ['KanjiID', 'Kanji', 'Joyo', 'JLPT', 'Actions'],
  ];

  public function __construct(
    private ?Draw $draw        = null,
    private array $columns     = [],
    private array $kanjiList   = [],
    private array $moreInfo    = []
  ) {}

  // Create draw DTO
  private function createDraw(array $requestData, int $drawIndex, array $columns): Draw
  {
    // Fill draw data
    $draw = new Draw(columns: $columns);
    $draw->drawCount   = $drawIndex;
    $draw->start       = (int)    $requestData['start'];
    $draw->length      = (int)    $requestData['length'];
    $draw->searchValue = (string) $requestData['search']['value'] ?? null;
    if (array_key_exists(0, $requestData['order'])) {
      $draw->orderColumnIndex = (int)    $requestData['order'][0]['column'] ?? 0;
      $draw->orderDir         = (string) $requestData['order'][0]['dir'] ?? 'asc';
    }

    return $draw;
  }

  // Fetch for 'admin' view
  private function ajaxKanjiFetch(): array
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
      $data   = [];
      foreach ($kanjis as $kanji) {
        $id        = (int)    $kanji['kanji_id'];
        $codepoint = (string) $kanji['codepoint'];
        $joyo = $jlpt = null;
        if (!empty($kanji['joyo'])) $joyo = (int) $kanji['joyo'];
        if (!empty($kanji['jlpt'])) $jlpt = (int) $kanji['jlpt'];

        $data[] = [
          $id,
          mb_chr(hexdec($codepoint)),
          $joyo,
          $jlpt,
          BTN_ACTIONS
        ];
      }

      return $data;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return [];
    }
  }

  private function ajaxHomeFetch(): array
  {
    try {
      $model = new Database();

      // Declare variables
      $data = $kanjiCodepoints = $notFound = [];
      $caseList   = '';
      $totalKanji = 0;
      $kanjiList  = $this->kanjiList;
      $kanjiCount = count($kanjiList);

      // Get total filtered and unfiltered
      $this->draw->recordsTotal = $this->draw->recordsFiltered = $kanjiCount;

      // Create placeholder for ' WHERE kanji_id IN (...)' and 'CASE WHEN ... THEN ... END' list
      foreach ($kanjiList as $kanji => $kanjiData) {
        $currCodepoint     = '"' . dechex(mb_ord($kanji)) . '"';
        $kanjiCodepoints[] = $currCodepoint;
        $caseList         .= "WHEN {$currCodepoint} THEN {$totalKanji} ";
        $totalKanji++;
      }
      $placeholder = implode(', ', $kanjiCodepoints);

      $this->draw->orderColumn = "CASE codepoint {$caseList}END";

      // Create $filtered array
      $filtered = [
        'count' => $kanjiCount,
        'where' => " WHERE codepoint IN ({$placeholder})",
        'params' => []
      ];

      // Fetch desired kanjis and render the table
      $kanjis = $model->fetchKanjis($this->draw, $filtered);
      $data = [];
      foreach($kanjis as $kanji) {
        $codepoint = (string) $kanji['codepoint'];
        $char      = mb_chr(hexdec($codepoint));
        if (!array_key_exists($char, $kanjiList)) {
          $notFound[] = $char;
        } else {
          $meaning  = '';
          $readings = [];
          $joyo = $jlpt = null;

          if (!empty($kanji['onyomi']))  $readings[] = 'On\'yomi: ' . $kanji['onyomi'];
          if (!empty($kanji['kunyomi'])) $readings[] = 'Kun\'yomi: ' . $kanji['kunyomi'];
          if (!empty($kanji['nanori']))  $readings[] = 'Nanori: ' . $kanji['nanori'];

          if (!empty($kanji['joyo'])) $joyo = (int) $kanji['joyo'];
          if (!empty($kanji['jlpt'])) $jlpt = (int) $kanji['jlpt'];

          if (!empty($kanji['meaning_en_kdic'])) $meaning = $kanji['meaning_en_kdic'];

          $data[] = [
            $kanjiList[$char]['order'],
            $char,
            $kanjiList[$char]['count'],
            implode('<br />', $readings),
            $joyo,
            $jlpt,
            $meaning
          ];
        }
      }

      $this->moreInfo['notFoundCount'] = count($notFound);
      $this->moreInfo['notFoundList']  = $notFound;

      return $data;
    } catch (PDOException $e) {
      logErrorWithTimestamp($e, __FILE__);
      return [];
    }
  }

  public function index(): void
  {
    if (
      $_SERVER['REQUEST_METHOD'] === 'POST' &&
      isset($_SERVER['HTTP_ACCEPT']) &&
      strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
    ) {
      // Get data from POST
      $requestData = json_decode(file_get_contents('php://input'), true);

      // Get draw index
      $drawIndex = (int) $requestData['draw'] ?? 0;

      // Get columns
      $from    = (string) $requestData['from'];
      $columns = $this::TBL_COLUMNS[$from];
      
      $this->draw     = $this->createDraw($requestData, $drawIndex, $columns);
      switch ($from) {
        case 'kanji':
          $data = $this->ajaxKanjiFetch();
          break;
        case 'home':
          $this->kanjiList = (array) $requestData['kanjiList'];
          $data = $this->ajaxHomeFetch();
          break;
        default:
          $data = [];
          break;
      }
    } else {
      goHome(404);
    }

    header("Content-type: application/json; charset=utf-8");
    echo json_encode([
      'draw'            => $drawIndex,
      'data'            => $data,
      'recordsTotal'    => $this->draw->recordsTotal,
      'recordsFiltered' => $this->draw->recordsFiltered,
      'moreInfo'        => $this->moreInfo
    ]);
    exit;
  }
}
