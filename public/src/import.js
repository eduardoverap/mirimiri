// Components
const btnXMLImport    = document.getElementById('btn-xml-import');
const prgXMLImport    = document.getElementById('prg-xml-import');
const txtXMLImportLog = document.getElementById('txt-xml-import-log');
const btnCSVImport    = document.getElementById('btn-csv-import');
const prgCSVImport    = document.getElementById('prg-csv-import');
const txtCSVImport    = document.getElementById('txt-csv-import');

/*** Parse the KANJIDIC2 XML ***/
let eventSource;

btnXMLImport.addEventListener('click', () => {
  btnXMLImport.disabled = true;
  prgXMLImport.style.width = 0;
  txtXMLImportLog.textContent = '';

  if (eventSource) {
    eventSource.close();
  }

  eventSource = new EventSource(BASE_URL + 'xmlimport');

  eventSource.onmessage = function(event) {
    txtXMLImportLog.textContent += event.data + "\n";
    txtXMLImportLog.scrollTop = txtXMLImportLog.scrollHeight;
  }

  eventSource.onerror = function() {
    txtXMLImportLog.textContent += 'Oops!\n';
    eventSource.close();
    btnXMLImport.disabled = false;
  }

  eventSource.addEventListener('progress', (event) => {
    const percent = parseInt(event.data, 10);
    prgXMLImport.style.width = percent + '%';
  });

  eventSource.addEventListener('close', () => {
    eventSource.close();
    btnXMLImport.disabled = false;
    txtXMLImportLog.textContent += 'Finished.';
  });
});

/*** Parse CSV data ***/
// Split text for analysis
function splitCSVLinesToJSON(text) {
  const lines = text.split('\n');
  lines.forEach((line, i, arr) => arr[i] = line.split(/\,|\t/));
  return JSON.stringify(lines);
}

btnCSVImport.addEventListener('click', () => {
  fetch(BASE_URL + 'csvimport', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: splitCSVLinesToJSON(txtCSVImport.value)
  })
  .then(response => response.text())
  .then(data => {
    console.log('Response:', data);
  })
  .catch(error => {
    console.error('Error:', error);
  });
});