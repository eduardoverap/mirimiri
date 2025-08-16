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
    if (!is_null($kanji)) {
      return json_encode($kanji);
    } else {
      return json_encode(['error' => 'Kanji not found.']);
    }
  }

  public function index(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if (isset($_GET['char'])) {
        header('Content-Type: application/json');
        echo $this->selectKanji($_GET['char']);
      } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID not provided.']);
      }
    } else {
      goHome(404);
    }
  }
}
