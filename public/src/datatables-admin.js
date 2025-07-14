document.addEventListener('DOMContentLoaded', () => {
  // Initialize DataTables
  let tblKanji = new DataTable('#tbl-kanji', {
    processing: true,
    serverSide: true,
    ajax: BASE_URL + 'table',
    columns: [
      { title: 'ID', searchable: false },
      { title: 'Kanji', orderable: false },
      {
        title: 'Joyo',
        render: (data, type, row) => data !== null ? JOYO_LEVEL[parseInt(data) - 1] : ''
      },
      {
        title: 'JLPT',
        render: (data, type, row) => data !== null ? JLPT_LEVEL[parseInt(data) - 1] : ''
      },
      { title: 'Actions', orderable: false, searchable: false }
    ],
    createdRow: (row, data, dataIndex) => {
      $(row).attr('data-id', data[1].codePointAt(0).toString(16));
    }
  });
  
  // Render action buttons for every row
  document.addEventListener('click', async (event) => {
    if (event.target.classList.contains('btn-edit')) {
      const row = event.target.closest('tr');
      const char = row.dataset.id;

      try {
        const route = BASE_URL + `select?char=${encodeURIComponent(char)}`;
        const response = await fetch(route);
        if (!response.ok) throw new Error('Response error');

        const kanjiData = await response.json();
        fillEditForm(kanjiData);
      } catch (error) {
        console.error('Error:', error);
      }
    }
  });
});