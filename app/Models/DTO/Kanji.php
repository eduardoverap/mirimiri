<?php

namespace App\Models\DTO;

class Kanji
{
  public function __construct(
    public int     $kanjiID,
    public string  $codepoint,
    public ?string $onyomi        = null,
    public ?string $kunyomi       = null,
    public ?string $nanori        = null,
    public ?int    $joyo          = null,
    public ?int    $jlpt          = null,
    public ?string $meaningENKDIC = null,
    public ?string $meaningESKDIC = null,
    public ?string $meaningES     = null,
    public ?string $meaningQU     = null
  ) {}

  public function getKanji(): string {
    return mb_chr(hexdec($this->codepoint));
  }
}
