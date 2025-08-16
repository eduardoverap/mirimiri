<?php

namespace App\Controllers\Traits;

use XMLReader;
use SimpleXMLElement;
use App\Models\Import;
use App\Models\DTO\Kanji;
use App\Models\DTO\Kotoba;

trait FromXML
{
  private const EL_TO_ITEM = [
    'character' => 'kanji',
    'entry'     => 'word',
  ];

  // Count kanjis/words in XML
  private function countItems(XMLReader $reader, string $element): int|false
  {
    $item      = $this::EL_TO_ITEM[$element];
    $itemCount = 0;

    while ($reader->read()) {
      if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === $element) $itemCount++;
    }

    if ($itemCount !== 1) $item .= 's';

    $reader->close();
    echo "data: Found {$itemCount} {$item}.\n\n";
    flush();
    return $itemCount;
  }

  // Create kanji DTO from XML
  private function createKanji(SimpleXMLElement $node): ?Kanji
  {
    // Get Unicode codepoint and create a kanji DTO
    $kanji = null;
    foreach ($node->codepoint->cp_value as $cp_value) {
      if ((string) $cp_value['cp_type'] === 'ucs') {
        $kanji = new Kanji(codepoint: strtolower((string) $cp_value));
        break;
      }
    }

    // On'yomi and kun'yomi readings
    $onyomi = $kunyomi = [];
    if (isset($node->reading_meaning->rmgroup->reading)) {
      foreach ($node->reading_meaning->rmgroup->reading as $reading) {
        if ((string) $reading['r_type'] === 'ja_on') {
          $onyomi[] = (string) $reading;
        } else if ((string) $reading['r_type'] === 'ja_kun') {
          $kunyomi[] = (string) $reading;
        }
      }
    }
    $kanji->onyomi  = implode('、', $onyomi);
    $kanji->kunyomi = implode('、', $kunyomi);

    // nanori readings
    $nanori = [];
    if (isset($node->reading_meaning->nanori)) {
      foreach ($node->reading_meaning->nanori as $n) {
        $nanori[] = (string) $n;
      }
    }
    $kanji->nanori = implode('、', $nanori);

    // Joyo level
    $kanji->joyo = (int) $node->misc->grade ?? null;

    // EN & ES meanings (from KANJIDIC)
    $meaningsEn = $meaningsEs = [];
    if (isset($node->reading_meaning->rmgroup->meaning)) {
      foreach ($node->reading_meaning->rmgroup->meaning as $meaning) {
        if (!isset($meaning['m_lang'])) {
          $meaningsEn[] = (string) $meaning;
        } else if ((string) $meaning['m_lang'] === 'es') {
          $meaningsEs[] = (string) $meaning;
        }
      }
    }
    $kanji->meaningEnKdic = implode('; ', $meaningsEn);
    $kanji->meaningEsKdic = $kanji->meaningEs = implode('; ', $meaningsEs);

    return $kanji;
  }

  // Create kotoba DTO from XML
  private function createKotoba(SimpleXMLElement $node): ?Kotoba
  {
    $kotoba = new Kotoba(kotoba: '');
    $kotoba->entSeq = (int) $node->ent_seq;

    $kana = [];
    foreach ($node->r_ele as $reading) {
      $kana[] = (string) $reading->reb;
    }
    $kotoba->kana = implode('、', $kana);

    $kotobaVars = [];
    if (isset($node->k_ele)) {
      foreach ($node->k_ele as $word) {
        $kotobaVars[] = (string) $word->keb;
      }
      $kotoba->kotoba = array_shift($kotobaVars);
    } else {
      $kotoba->kotoba = $kana[0];
    }
    $kotoba->kotobaVars = implode('、', $kotobaVars);

    $grammar = [];
    if (isset($node->sense->pos)) {
      foreach ($node->sense->pos as $pos) {
        $grammar[] = (string) $pos;
      }
    }
    $kotoba->grammar = implode('; ', $grammar);

    $meaningsEn = $meaningsEs = [];
    foreach ($node->sense as $sense) {
      foreach ($sense->gloss as $gloss) {
        if (!isset($gloss['xml:lang'])) {
          $meaningsEn[] = (string) $gloss;
        } else if ((string) $gloss['xml:lang'] === 'spa') {
          $meaningsEs[] = (string) $gloss;
        }
      }
    }
    $kotoba->meaningEnJmd = implode('; ', $meaningsEn);
    $kotoba->meaningEsJmd = $kotoba->meaningEs = implode('; ', $meaningsEs);

    return $kotoba;
  }

  // Perform XML extraction calling the other trait methods
  private function extractXMLData(string $path, string $source): bool
  {
    if ($source === 'kd2') {
      $element = 'character';
      $method  = 'createKanji';
    } else if ($source === 'jmd') {
      $element = 'entry';
      $method  = 'createKotoba';
    } else {
      return false;
    }

    // Open XML
    $reader = new XMLReader();
    $reader->open($path);
    if (!$reader) die('XML is unavailable');
    echo "data: XML opened...\n\n";
    flush();

    // Count total of kanjis
    $itemCount    = $this->countItems($reader, $element);
    $progressCount = 0;

    // Reopen XML
    $reader->open($path, null, LIBXML_DTDLOAD | LIBXML_DTDATTR | LIBXML_NOENT);
    $model = new Import();
    $db = $model->getConnection();
    $db->beginTransaction();
    echo "data: DB Connection established...\n\n";
    flush();

    // Extract data
    while ($reader->read()) {
      if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === $element) {
        $node = new \SimpleXMLElement($reader->readOuterXML());
        $item = $this->$method($node);
        unset($node);

        // Insert item
        $model->insertFromXML($item);

        // For progress bar
        $progressCount++;
        if ($progressCount % 100 === 0) {
          $percent = round(($progressCount / $itemCount) * 100);
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
    return true;
  }
}
