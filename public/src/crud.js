// Get level from index
const getLevelFromIndex = (array, index = null) => {
  const level = (index === null || index === '' || index === 0) ? 0 : array[index - 1]
  return level;
}

// Fill data for editing
const fillEditForm = (objKanji) => {
  const codepoint = '0x' + objKanji.codepoint;
  document.getElementById('codepoint').innerText = objKanji.codepoint;
  document.getElementById('kanji-display').innerText = String.fromCodePoint(codepoint);
  document.getElementById('txt-onyomi').value = objKanji.onyomi ?? '';
  document.getElementById('txt-kunyomi').value = objKanji.kunyomi ?? '';
  document.getElementById('txt-nanori').value = objKanji.nanori ?? '';
  document.getElementById('ddl-joyo').value = objKanji.joyo ?? 0;
  document.getElementById('ddl-jlpt').value = objKanji.jlpt ?? 0;
  document.getElementById('txt-meaning-enkdic').value = objKanji.meaningENKDIC ?? '';
  document.getElementById('txt-meaning-eskdic').value = objKanji.meaningESKDIC ?? '';
  document.getElementById('txt-meaning-es').value = objKanji.meaningES ?? '';
  document.getElementById('txt-meaning-qu').value = objKanji.meaningQU ?? '';
}

// Save edited data
const btnSave = document.getElementById('btn-save');

btnSave.addEventListener('click', () => {
  const objData = {
      codepoint : document.getElementById('codepoint').innerText,
      onyomi : document.getElementById('txt-onyomi').value,
      kunyomi : document.getElementById('txt-kunyomi').value,
      nanori : document.getElementById('txt-nanori').value,
      joyo : document.getElementById('ddl-joyo').value,
      jlpt : document.getElementById('ddl-jlpt').value,
      meaningES : document.getElementById('txt-meaning-es').value,
      meaningQU : document.getElementById('txt-meaning-qu').value,
  }
  fetch(BASE_URL + 'save', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(objData)
  })
  .then(response => response.text())
  .then(data => {
    console.log('Response:', data);
    alert('Successfuly saved!');
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error has occurred.');
  });
});