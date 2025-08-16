<?php

namespace App\Controllers;

use App\Controllers\Traits\FromXML;
use App\Controllers\Traits\FromCSV;

class EventController
{
  use FromXML;

  public function index(): void
  {
    // Get URL parts
    $parts       = explode('/', $_GET['route']);
    $source      = $parts[1];
    $sourcesList = ['kd2', 'jmd'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($source) && in_array($source, $sourcesList)) {
      // SSE headers
      header('Content-Type: text/event-stream');
      header('Cache-Control: no-cache');
      header('Connection: keep-alive');

      while (ob_get_level() > 0) ob_end_flush();
      ob_implicit_flush(true);

      echo "data: Starting...\n\n";
      flush();

      // Execute method
      if ($source === 'kd2') $this->extractXMLData(KD2_PATH, $source);
      if ($source === 'jmd') $this->extractXMLData(JMD_PATH, $source);

      echo "event: close\ndata: done\n\n";
      flush();
      exit;
    } else {
      goHome(404);
    }
  }
}
