// Global constants
const JOYO_LEVEL = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Secondary', 'Jinmeiyo'];
const JLPT_LEVEL = ['N5', 'N4', 'N3', 'N2', 'N1'];
const HAN_REGEX  = /\p{sc=Han}/gu;

// Modal components
const modal         = document.getElementById('modal');
const btnShowModal  = document.getElementById('btn-more-info');
const btnCloseModal = document.getElementById('btn-close-modal');

document.addEventListener('DOMContentLoaded', () => {
  // Open modal: btnShowModal if exists
  if (btnShowModal) {
    btnShowModal.addEventListener('click', () => {
      modal.style.display = 'flex';
    });
  }

  // Open modal: from Edit buttons
  document.addEventListener('click', async (event) => {
    if (event.target.classList.contains('btn-edit')) {
      modal.style.display = 'flex';
    }
  });

  // Close modal: button (×)
  btnCloseModal.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  // Close modal: outside the box
  window.addEventListener('click', event => {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });
});