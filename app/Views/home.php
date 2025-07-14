  <main>
    <section id="for-input">
      <h2>Kanji extractor</h2>
      <p>Enter some cool Japanese text here.</p>
      <textarea id="src-text"></textarea><br/>
      <small>Inspired by <a href="https://manest.github.io/kanji-extractor/" target="_blank" title="Manest kanji extractor">Manest kanji extractor</a>.</small>
    </section>
    <section id="results">
      <h2>Found kanji: <span id="found"></span></h2>
      <small>Clicking on any of these will send you to their respective <a href="https://jisho.org/" target="_blank" title="Jisho.org">Jisho.org</a> info page.</small>
      <p id="all-kanji"></p>
      <input id="btn-more-info" type="button" value="Show more info" />
      <div id="modal">
        <div id="modal-content">
          <span id="btn-close-modal">&times;</span>
          <h2>More info</h2>
          <table id="tbl-more-info">
            <thead>
              <tr>
                <th>Kanji</th>
                <th>Order</th>
                <th>Frequency</th>
                <th>Readings</th>
                <th>Joyo</th>
                <th>JLPT</th>
                <th>Meaning</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
  <script>const BASE_URL = window.location.pathname;</script>
  <script src="src/extract.js"></script>
  <script src="src/datatables-home.js"></script>
