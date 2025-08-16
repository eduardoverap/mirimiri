// Fill data for editing
const fillEditForm = kanji => {
  const codepoint = '0x' + kanji.codepoint;
  document.getElementById('codepoint').innerText      = kanji.codepoint;
  document.getElementById('kanji-display').innerText  = String.fromCodePoint(codepoint);
  document.getElementById('txt-onyomi').value         = kanji.onyomi ?? '';
  document.getElementById('txt-kunyomi').value        = kanji.kunyomi ?? '';
  document.getElementById('txt-nanori').value         = kanji.nanori ?? '';
  document.getElementById('ddl-joyo').value           = kanji.joyo ?? 0;
  document.getElementById('ddl-jlpt').value           = kanji.jlpt ?? 0;
  document.getElementById('txt-meaning-enkdic').value = kanji.meaningEnKdic ?? '';
  document.getElementById('txt-meaning-eskdic').value = kanji.meaningEsKdic ?? '';
  document.getElementById('txt-meaning-es').value     = kanji.meaningEs ?? '';
  document.getElementById('txt-meaning-qu').value     = kanji.meaningQu ?? '';
}

export { fillEditForm }
