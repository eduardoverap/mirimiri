<?php

namespace App\Controllers;

use XMLReader;
use App\Models\XMLImport;

class XMLImportController
{
  public function index(string $path = KD2_PATH)
  {
    // SSE headers
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');

    while (ob_get_level() > 0) ob_end_flush();
    ob_implicit_flush(true);

    echo "data: Starting...\n\n";
    flush();

    // Open XML
    $reader = new XMLReader();
    $reader->open($path);
    if (!$reader) die('XML is unavailable');
    echo "data: XML opened...\n\n";
    flush();

    // Count total of kanjis
    $kanjiCount = 0;
    while ($reader->read()) {
      if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'character') $kanjiCount++;
    }
    $reader->close();
    echo "data: Found {$kanjiCount} characters.\n\n";
    flush();

    // Open database
    $reader->open($path);
    $model = new XMLImport();
    $db = $model->getConnection();
    $db->beginTransaction();
    echo "data: DB Connection established...\n\n";
    flush();
    $progressCount = 0;

    // Extract data
    while ($reader->read()) {
      if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'character') {
        $node = new \SimpleXMLElement($reader->readOuterXML());

        // Unicode codepoint
        foreach ($node->codepoint->cp_value as $cp_value) {
          if ((string) $cp_value['cp_type'] === 'ucs') {
            $codepoint = strtolower((string) $cp_value);
            break;
          }
        }

        // On'yomi and kun'yomi readings
        $onyomi = [];
        $kunyomi = [];
        if (isset($node->reading_meaning->rmgroup->reading)) {
          foreach ($node->reading_meaning->rmgroup->reading as $reading) {
            if ((string) $reading['r_type'] === 'ja_on') {
              $onyomi[] = (string) $reading;
            } else if ((string) $reading['r_type'] === 'ja_kun') {
              $kunyomi[] = (string) $reading;
            }
          }
        }

        // Nanori readings
        $nanori = [];
        if (isset($node->reading_meaning->nanori)) {
          foreach ($node->reading_meaning->nanori as $n) {
            $nanori[] = (string) $n;
          }
        }

        // Joyo level
        $joyo = $node->misc->grade ?? null;

        // EN & ES meanings (from KANJIDIC)
        $meaningsEN = [];
        $meaningsES = [];
        if (isset($node->reading_meaning->rmgroup->meaning)) {
          foreach ($node->reading_meaning->rmgroup->meaning as $meaning) {
            if (!isset($meaning['m_lang'])) {
              $meaningsEN[] = (string) $meaning;
            } else if ((string) $meaning['m_lang'] === 'es') {
              $meaningsES[] = (string) $meaning;
            }
          }
        }

        unset($node);

        // Insert kanji
        $model->insertFromKANJIDIC([
          'codepoint'  => $codepoint,
          'onyomi'     => implode('、', $onyomi),
          'kunyomi'    => implode('、', $kunyomi),
          'nanori'     => implode('、', $nanori),
          'meaning_en' => implode('; ', $meaningsEN),
          'meaning_es' => implode('; ', $meaningsES),
        ]);
        
        // For progress bar
        $progressCount++;
        if ($progressCount % 100 === 0) {
          $percent = round(($progressCount / $kanjiCount) * 100);
          echo "event: progress\ndata: {$percent}\n\n";
          flush();
        }

        // Every 1000 registries make a commit
        if ($progressCount % 1000 === 0) {
          $db->commit();
          $db->beginTransaction();
        }
      }
    }

    $reader->close();
    $db->commit();
    echo "event: close\ndata: done\n\n";
    flush();
    die();
  }
}
