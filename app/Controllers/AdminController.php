<?php

namespace App\Controllers;

class AdminController extends BaseController
{
  public function index(): void
  {
    $this->render('admin');
  }
}
