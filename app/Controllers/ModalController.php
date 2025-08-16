<?php

namespace App\Controllers;

class ModalController
{
  public function index(): void
  {
    $parts = explode('/', $_GET['route']);
    $modal = isset($parts[1]) ? "../app/Views/modals/{$parts[1]}.php" : '';
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && is_file($modal)) {
      include $modal;
    } else {
      goHome(404);
    }
  }
}
