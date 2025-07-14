<?php

namespace App\Controllers;

use App\Models\Database;

class SaveController
{
  public function index()
  {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData);

    // Create model
    $model = new Database();

    $model->updateKanji([
      'Codepoint' => (string) $data->codepoint,
      'Onyomi'    => (string) $data->onyomi,
      'Kunyomi'   => (string) $data->kunyomi,
      'Nanori'    => (string) $data->nanori,
      'Joyo'      => (int)    $data->joyo,
      'JLPT'      => (int)    $data->jlpt,
      'MeaningES' => (string) $data->meaningES,
      'MeaningQU' => (string) $data->meaningQU,
    ]);

    header('Content-Type: text/plain');
    echo $rawData;
  }
}
