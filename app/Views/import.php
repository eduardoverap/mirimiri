    <section class="content-section">
      <div class="content-centered">
        <h2>Import center</h2>
        <section>
          <h3>Import kanjis from KANJIDIC XML file</h3>
          <textarea id="kdic-import-log" placeholder="Click on the button to start!" readonly></textarea><br />
          <div class="progress-bar">
            <div id="prg-kdic-import" class="current-progress"></div>
          </div>
          <input id="btn-kdic-import" type="button" value="Import from KANJIDIC XML" />
        </section>
        <section>
          <h3>Import words from JMdict XML file</h3>
          <textarea id="jmd-import-log" placeholder="Click on the button to start!" readonly></textarea><br />
          <div class="progress-bar">
            <div id="prg-jmd-import" class="current-progress"></div>
          </div>
          <input id="btn-jmd-import" type="button" value="Import from JMdict XML" />
        </section>
        <section>
      </div>
    </section>
  </main>
  <script>const BASE_URL = window.location.pathname.replace('import', '');</script>
  <script src="src/import.js"></script>
