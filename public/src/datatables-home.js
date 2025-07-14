document.addEventListener('DOMContentLoaded', () => {
  // Initialize DataTables after clicking on the More Info button.
  btnMoreInfo.addEventListener('click', () => {
    let tblMoreInfo = new DataTable('#tbl-more-info', {
      destroy: true,
      processing: true,
      serverSide: true,
      searching: false,
      ordering: false,
      paging: false,
      ajax: {
        url: BASE_URL + 'table',
        type: 'POST',
        contentType: 'application/json',
        data: (d) => {
          return JSON.stringify({
            ...d,
            kanjiList: objFrequency
          });
        }
      },
      columns: [
        { title: 'Order' },
        { title: 'Kanji' },
        { title: 'Frequency' },
        { title: 'Readings' },
        {
          title: 'Joyo',
          render: (data, type, row) => data !== null ? JOYO_LEVEL[parseInt(data) - 1] : ''
        },
        {
          title: 'JLPT',
          render: (data, type, row) => data !== null ? JLPT_LEVEL[parseInt(data) - 1] : ''
        },
        { title: 'Meaning' }
      ],
      createdRow: (row, data, dataIndex) => {
        $(row).attr('data-id', data[0]);
      }
    });
  });
});