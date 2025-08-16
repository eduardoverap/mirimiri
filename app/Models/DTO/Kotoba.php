<?php

namespace App\Models\DTO;

class Kotoba
{
  public function __construct(
    public string  $kotoba,
    public ?int    $kotobaId     = null,
    public ?string $kotobaVars   = null,
    public ?int    $entSeq       = null,
    public ?string $kana         = null,
    public ?string $grammar      = null,
    public ?string $meaningEnJmd = null,
    public ?string $meaningEsJmd = null,
    public ?string $meaningEs    = null,
    public ?string $example      = null,
    public ?string $exampleEn    = null,
    public ?string $exampleEs    = null,
  ) {}
}
