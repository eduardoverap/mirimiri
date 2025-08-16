// Components
const btnKDICImport = document.getElementById('btn-kdic-import');
const prgKDICImport = document.getElementById('prg-kdic-import');
const KDICImportLog = document.getElementById('kdic-import-log');

const btnJMDImport  = document.getElementById('btn-jmd-import');
const prgJMDImport  = document.getElementById('prg-jmd-import');
const JMDImportLog  = document.getElementById('jmd-import-log');

let eventSource;

const importEventSource = (route, button, prgBar, logArea) => {
  button.disabled     = true;
  prgBar.style.width  = 0;
  logArea.textContent = '';
  
  if (eventSource) {
    eventSource.close();
  }

  eventSource = new EventSource(BASE_URL + 'event/' + route);

  eventSource.onmessage = event => {
    logArea.textContent += event.data + "\n";
    logArea.scrollTop    = logArea.scrollHeight;
  }

  eventSource.onerror = () => {
    eventSource.close();
    logArea.textContent += 'Oops!\n';
    button.disabled      = false;
  }

  eventSource.addEventListener('progress', event => {
    const percent      = parseInt(event.data, 10);
    prgBar.style.width = percent + '%';
  });

  eventSource.addEventListener('close', () => {
    eventSource.close();
    button.disabled      = false;
    logArea.textContent += 'Finished.';
  });
};

/*** Parse the KANJIDIC2 XML ***/
btnKDICImport.addEventListener('click', () => {
  importEventSource('kd2', btnKDICImport, prgKDICImport, KDICImportLog);
});

btnJMDImport.addEventListener('click', () => {
  importEventSource('jmd', btnJMDImport, prgJMDImport, JMDImportLog);
});