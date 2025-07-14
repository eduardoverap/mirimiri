<?php

namespace App\Controllers;

abstract class BaseController
{
  // Method for loading the view
  protected function render(string $viewName, array $data = []): void
  {
    // Set UTF-8 header
    header('Content-Type: text/html; charset=UTF-8');

    // Include header.php
    include_once '../app/Views/partials/header.php';

    // Verify if the requested view exists
    $viewFile = "../app/Views/{$viewName}.php";
    if (file_exists($viewFile)) {
      if (!empty($data)) extract($data);
      include_once $viewFile;
    } else {
      http_response_code(404);
      echo "Not found: {$viewName}.php";
    }

    // Include footer.php
    include_once '../app/Views/partials/footer.php';
  }

  // Main method for the class
  abstract public function index(): void;
}
