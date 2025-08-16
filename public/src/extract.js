// Import constants
import { JOYO_LEVEL, JLPT_LEVEL, HAN_REGEX } from './modules/app.js';

// Home components
const textarea    = document.getElementById('src-text');
const resultsDiv  = document.getElementById('results');
const numFound    = document.getElementById('found');
const allKanji    = document.getElementById('all-kanji');

// Sorted kanjis object
let objFrequency = {};

// Kanji extractor listener
textarea.addEventListener('input', () => {
  // Clean object
  objFrequency = {};

  // Extract all kanjis
  const arrKanji = textarea.value.match(HAN_REGEX);

  if (arrKanji && arrKanji.length !== 0) {
    // Define variables
    let strKanji = '';
    let order    = 1;

    // Write text and make object by frequencies
    for (let i = 0; i < arrKanji.length; i++) {
      const currKanji = arrKanji[i];
      if (!objFrequency[currKanji]) {
        strKanji += `<a href="https://jisho.org/search/${currKanji}%20%23kanji" target="_blank" title="More about ${currKanji} in Jisho.org">${currKanji}</a>`;
        objFrequency[currKanji] = { order: order, count: 1 };
        order++;
      } else {
        objFrequency[currKanji].count++;
      }
    }

    // Sort object kanjis by count (DESC) and order (ASC)
    const ordered = Object.entries(objFrequency).sort(([, a], [, b]) => {
      if (b.count !== a.count) {
        return b.count - a.count;
      }
      return a.order - b.order;
    });

    // Change display of results section
    resultsDiv.style.display = 'block';
    numFound.innerText       = ordered.length;
    allKanji.innerHTML       = strKanji;
  } else {
    resultsDiv.style.display = 'none';
  }
});

// Show modal
document.addEventListener('click', event => {
  if (event.target.matches('#btn-more-info')) {
    // Get modal
    fetch(BASE_URL + 'modal/kanji-extracted')
    .then(response => response.text())
    .then(html => {
      // Create modal from fetch
      const container = document.createElement('div');
      container.innerHTML = html;
      document.body.appendChild(container);

      // Get modal components
      const modal         = document.getElementById('modal');
      const btnCloseModal = document.getElementById('btn-close-modal');

      // Close modal: button (×)
      btnCloseModal.addEventListener('click', () => {
        modal.remove();
      });

      // Close modal: outside the box
      window.addEventListener('click', event => {
        if (event.target === modal) {
          modal.remove();
        }
      });

      modal.style.display = 'flex';

      // Initialize DataTables
      let tblMoreInfo = new DataTable('#tbl-more-info', {
        destroy: true,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
          url: BASE_URL + 'table',
          type: 'POST',
          contentType: 'application/json',
          data: (d) => JSON.stringify({
            ...d,
            from: 'home',
            kanjiList: objFrequency
          })
        },
        columns: [
          { name: 'Order' },
          { name: 'Kanji', orderable: false },
          { name: 'Frequency', orderable: false },
          { name: 'Readings', orderable: false },
          {
            name: 'Joyo', orderable: false,
            render: (data, type, row) => data !== null ? JOYO_LEVEL[parseInt(data) - 1] : ''
          },
          {
            name: 'JLPT', visible: false,
            render: (data, type, row) => data !== null ? JLPT_LEVEL[parseInt(data) - 1] : ''
          },
          { name: 'Meaning', orderable: false },
        ],
        columnDefs: [
          { className: 'dt-center', targets: [ 0, 1, 2, 4, 5 ] }
        ],
        createdRow: (row, data, dataIndex) => {
          $(row).attr('data-id', data[0]);
        },
      });
    })
    .catch(error => console.error('Error loading modal:', error));
  }
});
