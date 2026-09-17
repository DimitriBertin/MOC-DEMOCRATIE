// Search modal
const searchModal = document.querySelector('[js-search-modal]');
const searchOpenBtns = document.querySelectorAll('[js-search-open]');
const searchCloseBtn = document.querySelector('[js-search-close]');
const searchInput = searchModal ? searchModal.querySelector('[js-search-textarea]') : null;

function openSearch() {
  if (!searchModal) return;
  searchModal.classList.add('is-open');
  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';
  document.body.setAttribute('data-lenis-prevent', 'true');
  if (window.lenis) window.lenis.stop();
  setTimeout(() => {
    if (searchInput) searchInput.focus();
  }, 300);
}

function closeSearch() {
  if (!searchModal) return;
  searchModal.classList.remove('is-open');
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
  document.body.removeAttribute('data-lenis-prevent');
  if (window.lenis) window.lenis.start();
}

// Auto-resize + Enter submit pour tous les textarea de recherche (modale + page résultats)
function initSearchTextarea(textarea) {
  if (!textarea) return;

  // Resize initial si valeur pré-remplie (page résultats)
  textarea.style.height = 'auto';
  textarea.style.height = textarea.scrollHeight + 'px';

  // Auto-resize à la saisie
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
  });

  // Enter soumet le form, Shift+Enter insère un saut de ligne
  textarea.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      const form = textarea.closest('form');
      if (form) form.submit();
    }
  });
}

document.querySelectorAll('[js-search-textarea]').forEach(initSearchTextarea);

if (searchModal) {
  searchOpenBtns.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openSearch();
    });
  });

  if (searchCloseBtn) {
    searchCloseBtn.addEventListener('click', closeSearch);
  }

  // Fermeture avec Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && searchModal.classList.contains('is-open')) {
      closeSearch();
    }
  });
}
