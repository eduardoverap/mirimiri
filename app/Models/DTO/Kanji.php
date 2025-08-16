<?php

namespace App\Models\DTO;

class Kanji
{
  public function __construct(
    public string  $codepoint,
    public ?int    $kanjiId       = null,
    public ?string $onyomi        = null,
    public ?string $kunyomi       = null,
    public ?string $nanori        = null,
    public ?int    $joyo          = null,
    public ?int    $jlpt          = null,
    public ?string $meaningEnKdic = null,
    public ?string $meaningEsKdic = null,
    public ?string $meaningEs     = null,
  ) {}

  public function __set(string $name, string|int $value): void
  {
    if (property_exists($this, $name)) $this->$name = $value;
  }

  public function getKanji(): string {
    return mb_chr(hexdec($this->codepoint));
  }
}
