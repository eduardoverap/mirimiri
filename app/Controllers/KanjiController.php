<?php

namespace App\Controllers;

class KanjiController extends BaseController
{
  public function index(): void
  {
    $this->render('kanji');
  }
}
