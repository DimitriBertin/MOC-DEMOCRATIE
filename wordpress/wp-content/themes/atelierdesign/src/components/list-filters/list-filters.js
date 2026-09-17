/**
 * List Filters
 *
 * Systeme de filtres AJAX generique pour les pages de liste
 * (page thematique, archive des revues).
 *
 * Le serveur rend la barre de filtres et les resultats : le JS se contente
 * d'envoyer l'etat courant et de remplacer les deux fragments.
 */

class ListFilterSystem {
  constructor(root) {
    this.root = root;
    this.listType = root.dataset.listType || 'thematique';
    this.contextId = parseInt(root.dataset.contextId, 10) || 0;
    this.perPage = parseInt(root.dataset.perPage, 10) || 9;
    this.baseUrl = root.dataset.baseUrl || window.location.pathname;
    this.loading = root.querySelector('.filter-loading');
    this.isLoading = false;

    this.params = this.listType === 'numero'
      ? ['annee', 'mois']
      : ['sous_thematique', 'annee', 'mois', 'auteur', 'tag'];

    this.filters = this.readFiltersFromURL();
    this.page = this.readPageFromURL();

    this.bindEvents();
  }

  /* ---------- URL ---------- */

  readFiltersFromURL() {
    const search = new URLSearchParams(window.location.search);
    const filters = {};

    this.params.forEach((param) => {
      const value = search.get(param);
      if (value) filters[param] = value;
    });

    if (!filters.annee) delete filters.mois;

    return filters;
  }

  readPageFromURL() {
    const search = new URLSearchParams(window.location.search);
    const paged = parseInt(search.get('paged'), 10);

    if (paged > 0) return paged;

    const match = window.location.pathname.match(/\/page\/(\d+)/);
    return match ? parseInt(match[1], 10) : 1;
  }

  buildURL() {
    const url = new URL(this.baseUrl, window.location.origin);

    Object.keys(this.filters).forEach((param) => {
      url.searchParams.set(param, this.filters[param]);
    });

    if (this.page > 1) url.searchParams.set('paged', this.page);

    return url.toString();
  }

  /* ---------- Evenements ---------- */

  bindEvents() {
    this.root.addEventListener('click', (event) => {
      const toggle = event.target.closest('.filter-dropdown');
      if (toggle && this.root.contains(toggle)) {
        event.preventDefault();
        this.toggleDropdown(toggle);
        return;
      }

      const option = event.target.closest('.filter-option');
      if (option) {
        event.preventDefault();
        this.selectOption(option);
        return;
      }

      const remove = event.target.closest('.remove-tag');
      if (remove) {
        event.preventDefault();
        this.removeFilter(remove.dataset.param);
        return;
      }

      const reset = event.target.closest('.reset-filters');
      if (reset) {
        event.preventDefault();
        this.resetAll();
        return;
      }

      const pageLink = event.target.closest('.pagination a');
      if (pageLink) {
        event.preventDefault();
        const page = this.extractPage(pageLink);
        if (page && page !== this.page) {
          this.page = page;
          this.update(true);
        }
      }
    });

    document.addEventListener('click', (event) => {
      if (!event.target.closest('.filter-dropdown-wrapper')) {
        this.closeDropdowns();
      }
    });

    window.addEventListener('popstate', () => {
      this.filters = this.readFiltersFromURL();
      this.page = this.readPageFromURL();
      this.update(false, false);
    });
  }

  extractPage(link) {
    if (link.dataset.page) {
      const fromData = parseInt(link.dataset.page, 10);
      if (fromData > 0) return fromData;
    }

    const href = link.getAttribute('href') || '';
    const query = href.match(/[?&]paged=(\d+)/);
    if (query) return parseInt(query[1], 10);

    const pretty = href.match(/\/page\/(\d+)/);
    if (pretty) return parseInt(pretty[1], 10);

    const text = link.textContent.trim();
    const numeric = parseInt(text, 10);
    if (!isNaN(numeric)) return numeric;

    return null;
  }

  /* ---------- Dropdowns ---------- */

  toggleDropdown(button) {
    const wrapper = button.closest('.filter-dropdown-wrapper');
    const menu = wrapper.querySelector('.filter-dropdown-menu');
    const isOpen = button.getAttribute('aria-expanded') === 'true';

    this.closeDropdowns();

    if (!isOpen) {
      button.setAttribute('aria-expanded', 'true');
      menu.classList.remove('hidden');
      const icon = button.querySelector('svg');
      if (icon) icon.style.transform = 'rotate(270deg)';
    }
  }

  closeDropdowns() {
    this.root.querySelectorAll('.filter-dropdown').forEach((button) => {
      button.setAttribute('aria-expanded', 'false');
      const icon = button.querySelector('svg');
      if (icon) icon.style.transform = 'rotate(90deg)';
    });

    this.root.querySelectorAll('.filter-dropdown-menu').forEach((menu) => {
      menu.classList.add('hidden');
    });
  }

  /* ---------- Etat des filtres ---------- */

  selectOption(option) {
    const param = option.dataset.param;
    const value = option.dataset.value;

    if (!param) return;

    // Re-cliquer sur l'element actif le deselectionne.
    if (this.filters[param] === value) {
      this.removeFilter(param);
      this.closeDropdowns();
      return;
    }

    this.filters[param] = value;

    // Changer d'annee invalide le mois, changer de sous-thematique
    // invalide les filtres dependants du perimetre.
    if (param === 'annee') delete this.filters.mois;
    if (param === 'sous_thematique') {
      delete this.filters.auteur;
      delete this.filters.tag;
    }

    this.page = 1;
    this.closeDropdowns();
    this.update();
  }

  removeFilter(param) {
    if (!param) return;

    delete this.filters[param];
    if (param === 'annee') delete this.filters.mois;

    this.page = 1;
    this.update();
  }

  resetAll() {
    this.filters = {};
    this.page = 1;
    this.update();
  }

  /* ---------- AJAX ---------- */

  async update(scroll = false, pushState = true) {
    if (this.isLoading) return;
    if (typeof adListFilters === 'undefined') {
      window.location.href = this.buildURL();
      return;
    }

    this.isLoading = true;
    this.showLoading();

    if (pushState) {
      window.history.pushState({}, '', this.buildURL());
    }

    const formData = new FormData();
    formData.append('action', 'ad_filter_list');
    formData.append('nonce', adListFilters.nonce);
    formData.append('list_type', this.listType);
    formData.append('context_id', this.contextId);
    formData.append('per_page', this.perPage);
    formData.append('page', this.page);

    Object.keys(this.filters).forEach((param) => {
      formData.append(`filters[${param}]`, this.filters[param]);
    });

    try {
      const response = await fetch(adListFilters.ajaxUrl, { method: 'POST', body: formData });

      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const payload = await response.json();

      if (!payload.success) throw new Error('Filter request failed');

      this.replaceFragment('.list-filters__bar', payload.data.bar_html);
      this.replaceFragment('.list-results', payload.data.results_html);

      if (scroll) {
        const results = this.root.querySelector('.list-results');
        if (results) {
          const top = results.getBoundingClientRect().top + window.scrollY - 140;
          window.scrollTo({ top, behavior: 'smooth' });
        }
      }
    } catch (error) {
      console.error('List filters:', error);
      window.location.href = this.buildURL();
    } finally {
      this.isLoading = false;
      this.hideLoading();
    }
  }

  replaceFragment(selector, html) {
    const current = this.root.querySelector(selector);
    if (!current || typeof html !== 'string') return;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = html.trim();

    const next = wrapper.querySelector(selector) || wrapper.firstElementChild;
    if (next) current.replaceWith(next);
  }

  showLoading() {
    if (!this.loading) return;
    this.loading.classList.remove('hidden');
    this.loading.classList.add('flex');
  }

  hideLoading() {
    if (!this.loading) return;
    this.loading.classList.add('hidden');
    this.loading.classList.remove('flex');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.list-filters').forEach((root) => new ListFilterSystem(root));
});
