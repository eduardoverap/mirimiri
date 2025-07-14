// Home components
const textarea    = document.getElementById('src-text');
const resultsDiv  = document.getElementById('results');
const numFound    = document.getElementById('found');
const allKanji    = document.getElementById('all-kanji');
const btnMoreInfo = document.getElementById('btn-more-info');
const byFrequency = document.getElementById('by-frequency');

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