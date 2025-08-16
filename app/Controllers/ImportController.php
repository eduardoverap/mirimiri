<?php

namespace App\Controllers;

use App\Controllers\Traits\FromCSV;

class ImportController extends BaseController
{
  use FromCSV;

  public function index(): void
  {
    $this->render('import');
  }
}
