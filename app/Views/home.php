    <section class="content-section">
      <div class="content-centered">
        <h2>Kanji extractor</h2>
        <p>Enter some cool Japanese text here.</p>
        <textarea id="src-text"></textarea><br/>
        <small>Inspired by <a href="https://manest.github.io/kanji-extractor/" target="_blank" title="Manest kanji extractor">Manest kanji extractor</a>.</small>
        <section id="results" class="bg-section">
          <h2>Found kanji: <span id="found"></span></h2>
          <small>Clicking on any of these will send you to their respective <a href="https://jisho.org/" target="_blank" title="Jisho.org">Jisho.org</a> info page.</small>
          <p id="all-kanji"></p>
          <input id="btn-more-info" type="button" value="Show more info" />
        </section>
      </div>
    </section>
  </main>
  <script>const BASE_URL = window.location.pathname;</script>
  <script src="src/extract.js" type="module"></script>
