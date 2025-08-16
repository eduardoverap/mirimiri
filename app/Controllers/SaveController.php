<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\DTO\Kanji;
use App\Models\DTO\Kotoba;
use stdClass;

class SaveController
{
  private function saveKanji(stdClass $data): int|false
  {
    // Create model
    $model = new Database();

    // Create kanji DTO
    $kanji = new Kanji(
      codepoint: (string) sanitizeInput($data->codepoint),
      onyomi:    (string) sanitizeInput($data->onyomi),
      kunyomi:   (string) sanitizeInput($data->kunyomi),
      nanori:    (string) sanitizeInput($data->nanori),
      joyo:      (int)    sanitizeInput($data->joyo),
      jlpt:      (int)    sanitizeInput($data->jlpt),
      meaningEs: (string) sanitizeInput($data->meaningEs),
    );

    return $model->updateKanji($kanji);
  }

  public function index()
  {
    // Get source data
    $data = json_decode(file_get_contents("php://input"));

    // Get URL parts
    $parts = explode('/', $_GET['route']);

    if ($parts[1] === 'kanji') $this->saveKanji($data);
  }
}
