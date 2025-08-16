// Global constants
const JOYO_LEVEL = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Secondary', 'Jinmeiyo'];
const JLPT_LEVEL = ['N5', 'N4', 'N3', 'N2', 'N1'];
const HAN_REGEX  = /(?![\u2F00-\u2FDF\u3000-\u303F])\p{sc=Han}/gu;

// Get level from index
const getLevelFromIndex = (array, index = null) => {
  const level = (index === null || index === '' || index === 0) ? 0 : array[index - 1]
  return level;
}

export { JOYO_LEVEL, JLPT_LEVEL, HAN_REGEX, getLevelFromIndex };
