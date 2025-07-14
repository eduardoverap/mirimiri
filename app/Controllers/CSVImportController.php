<?php

namespace App\Controllers;

use App\Models\CSVImport;

class CSVImportController
{
  public function index()
  {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData);

    // Get field for update
    $field = (string) $data[0][1];

    // Create model
    $model = new CSVImport();

    // Update each kanji
    for ($i = 1; $i < count($data); $i++) {
      $model->updateFromCSV($data[$i][0], $field, (int) $data[$i][1]);
    }

    /* Write a better response */
    header('Content-Type: text/plain');
    http_response_code(200);
    echo 'Hello from PHP!';
  }
}
