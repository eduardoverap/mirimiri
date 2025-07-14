<?php

namespace App\Models\DTO;

class Draw
{
  public function __construct(
    public array   $columns,
    public int     $drawCount        = 1,
    public int     $start            = 0,
    public int     $length           = 10,
    public int     $orderColumnIndex = 0,
    public ?string $orderColumn      = null,
    public string  $orderDir         = 'asc',
    public ?string $searchValue      = null,
    public int     $recordsTotal     = 0,
    public int     $recordsFiltered  = 0
  )
  {
    $this->orderColumn      = (string) $this->columns[$this->orderColumnIndex];
  }
}