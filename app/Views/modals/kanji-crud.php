<div id="modal">
  <div id="modal-content">
    <span id="btn-close-modal">&times;</span>
    <h2>Edit kanji</h2>
    <form id="frm-edit">
      <fieldset id="fra-readings" class="two-cols">
        <legend>Readings</legend>
        <div id="div-kanji">
          <span id="kanji-display"></span>
        </div>
        <div id="div-readings">
          <label for="codepoint">Unicode: <span id="codepoint"></span></label>
          <label for="txt-onyomi">On'yomi: <input id="txt-onyomi" type="text" value="" /></label>
          <label for="txt-kunyomi">Kun'yomi: <input id="txt-kunyomi" type="text" value="" /></label>
          <label for="txt-nanori">nanori: <input id="txt-nanori" type="text" value="" /></label>
        </div>
      </fieldset>
      <fieldset class="two-cols">
        <legend>Level</legend>
        <label for="ddl-joyo">Joyo level: <select id="ddl-joyo" name="ddl-joyo">
          <option value="0" selected>None</option>
          <option value="1">Grade 1</option>
          <option value="2">Grade 2</option>
          <option value="3">Grade 3</option>
          <option value="4">Grade 4</option>
          <option value="5">Grade 5</option>
          <option value="6">Grade 6</option>
          <option value="7">Secondary</option>
          <option value="8">Jinmeiyo</option>
        </select></label>
        <label for="ddl-jlpt">JLPT level: <select id="ddl-jlpt" name="ddl-jlpt">
          <option value="0" selected>None</option>
          <option value="1">N5</option>
          <option value="2">N4</option>
          <option value="3">N3</option>
          <option value="4">N2</option>
          <option value="5">N1</option>
        </select></label>
      </fieldset>
      <fieldset class="two-cols">
        <legend>KANJIDIC meanings</legend>
        <label for="txt-meaning-enkdic">Meaning (English): <textarea id="txt-meaning-enkdic" readonly></textarea></label>
        <label for="txt-meaning-eskdic">Meaning (Spanish): <textarea id="txt-meaning-eskdic" readonly></textarea></label>
      </fieldset>
      <fieldset class="two-cols">
        <legend>Translated meanings</legend>
        <label for="txt-meaning-es">Meaning (Spanish): <textarea id="txt-meaning-es" name="txt-meaning-es"></textarea></label>
        <label for="txt-meaning-qu">Meaning (Quechua): <textarea id="txt-meaning-qu" name="txt-meaning-qu"></textarea></label>
      </fieldset>
      <div>
        <input id="btn-save" type="button" value="Save" />
      </div>
    </form>
  </div>
</div>
<script src="src/crud/kanji-crud.js"></script>