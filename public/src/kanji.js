// Import constants
import { JOYO_LEVEL, JLPT_LEVEL } from './modules/app.js';
import { fillEditForm } from './crud/kanji-crud.js';

// Initialize DataTables
document.addEventListener('DOMContentLoaded', () => {
  let tblKanji = new DataTable('#tbl-kanji', {
    processing: true,
    serverSide: true,
    ajax: {
      url: BASE_URL + 'table',
      method: 'POST',
      contentType: 'application/json',
      data: d => JSON.stringify({...d, from: 'kanji'}),
    },
    columns: [
      { name: 'ID', searchable: false },
      { name: 'Kanji', orderable: false, searchable: false },
      {
        name: 'Joyo',
        render: (data, type, row) => data !== null ? JOYO_LEVEL[parseInt(data) - 1] : ''
      },
      {
        name: 'JLPT',
        render: (data, type, row) => data !== null ? JLPT_LEVEL[parseInt(data) - 1] : ''
      },
      { name: 'Actions', orderable: false, searchable: false }
    ],
    columnDefs: [
      { className: 'dt-center', targets: [ 0, 1, 2, 3, 4 ] }
    ],
    createdRow: (row, data, dataIndex) => {
      $(row).attr('data-id', data[1].codePointAt(0).toString(16));
    },
  });

  // Render action buttons for every row
  document.addEventListener('click', event => {
    if (event.target.classList.contains('btn-edit')) {
      fetch(BASE_URL + 'modal/kanji-crud')
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

        // Get kanji data
        const row = event.target.closest('tr');
        const char = row.dataset.id;

        const route = BASE_URL + `select?char=${encodeURIComponent(char)}`;
        fetch(route)
        .then(response => response.json())
        .then(kanjiData => fillEditForm(kanjiData))
        .catch(error => console.error('Response error:', error));

        // Save button
        const btnSave = document.getElementById('btn-save');

        btnSave.addEventListener('click', () => {
          const objData = {
            codepoint : document.getElementById('codepoint').innerText,
            onyomi    : document.getElementById('txt-onyomi').value,
            kunyomi   : document.getElementById('txt-kunyomi').value,
            nanori    : document.getElementById('txt-nanori').value,
            joyo      : document.getElementById('ddl-joyo').value,
            jlpt      : document.getElementById('ddl-jlpt').value,
            meaningEs : document.getElementById('txt-meaning-es').value,
            meaningQu : document.getElementById('txt-meaning-qu').value,
          }

          fetch(BASE_URL + 'save/kanji', {
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
            tblKanji.ajax.reload(null, false);
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error has occurred.');
          });
        });
      })
      .catch(error => console.error('Error loading modal:', error));
    }
  });
});
