<?php

namespace App\Controllers;

use App\Models\Database;

class SelectController
{
  public function selectKanji($codepoint): string
  {
    // Get DB connection
    $model  = new Database();
    $kanji = $model->selectKanji($codepoint);

    // Encode as JSON
    return json_encode($kanji);
  }

  public function index()
  {
    if (isset($_GET['char'])) {
      header('Content-Type: application/json');
      echo $this->selectKanji($_GET['char']);
    } else {
      http_response_code(400);
      echo json_encode(['error' => 'ID not provided.']);
    }
  }
}
