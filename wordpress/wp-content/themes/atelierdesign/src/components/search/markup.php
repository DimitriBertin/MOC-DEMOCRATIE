<?php
/**
 * Search Modal Component
 *
 * Modale de recherche plein écran, ouverte par les boutons [js-search-open]
 * (header desktop + header mobile). Soumet vers la home avec ?s=…, rendu par search.php
 */
?>
<div
  class="search-modal fixed inset-0 z-[100] theme-dark-green bg-layout-main"
  js-search-modal
  role="dialog"
  aria-modal="true"
  aria-label="Recherche"
>
  <div class="container relative h-full flex flex-col justify-center">

    <button
      type="button"
      class="search-modal-close"
      js-search-close
      aria-label="Fermer la recherche"
    >
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 5L19 19"/>
        <path d="M19 5L5 19"/>
      </svg>
    </button>

    <div class="autoscale flex flex-col @sm:gap-4 @md/lg:gap-6">
      <p class="search-modal-label label text-yellow">Rechercher &amp; appuyer sur Entrée</p>

      <form
        role="search"
        method="get"
        action="<?php echo esc_url(home_url('/')); ?>"
        class="search-modal-form"
      >
        <textarea
          name="s"
          class="heading-lg heading-primary"
          placeholder="Que recherchez-vous ?"
          autocomplete="off"
          rows="1"
          aria-label="Recherche"
          js-search-textarea
        ><?php echo esc_textarea(get_search_query()); ?></textarea>

        <button type="submit" class="appearance-none" aria-label="Lancer la recherche">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
            <circle cx="10.5" cy="10.5" r="7.5"/>
            <path d="M16 16L21 21"/>
          </svg>
        </button>
      </form>
    </div>

  </div>
</div>
