/**
 * Archive Filter System
 * 
 * Handles AJAX filtering, URL updates, and dropdown interactions
 */

class ArchiveFilterSystem {
    constructor() {
        this.container = document.querySelector('.archive-header');
        this.postsContainer = document.querySelector('.posts-grid, .archive-posts .container');
        this.loadingOverlay = document.querySelector('.filter-loading');
        this.activeFilters = {};
        this.currentPage = 1;
        this.isLoading = false;
        
        if (this.container) {
            this.init();
        }
    }
    
    init() {
        this.bindEvents();
        this.initializeFromURL();
        this.setupClickOutside();
    }
    
    bindEvents() {
        // Filter dropdown toggles
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.filter-dropdown')) {
                e.preventDefault();
                this.toggleDropdown(e.target.closest('.filter-dropdown'));
            }
        });
        
        // Filter option selection
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.filter-option')) {
                e.preventDefault();
                const option = e.target.closest('.filter-option');
                
                if (!option.disabled) {
                    this.selectFilterOption(option);
                }
            }
        });
        
        // Remove filter tags
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.remove-tag')) {
                e.preventDefault();
                const filterType = e.target.closest('.remove-tag').dataset.filter;
                this.removeFilter(filterType);
            }
        });
        
        // Reset all filters
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.reset-filters')) {
                e.preventDefault();
                this.resetAllFilters();
            }
        });
        
        // Handle reset filters CTA in no-results
        document.addEventListener('click', (e) => {
            if (e.target.closest('.reset-filters-cta')) {
                e.preventDefault();
                this.resetAllFilters();
            }
        });
        
        // Handle browser back/forward
        window.addEventListener('popstate', (e) => {
            this.initializeFromURL();
        });
    }
    
    setupClickOutside() {
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.filter-dropdown-wrapper')) {
                this.closeAllDropdowns();
            }
        });
    }
    
    toggleDropdown(button) {
        const wrapper = button.closest('.filter-dropdown-wrapper');
        const menu = wrapper.querySelector('.filter-dropdown-menu');
        const isOpen = button.getAttribute('aria-expanded') === 'true';
        
        // Close all other dropdowns
        this.closeAllDropdowns();
        
        if (!isOpen) {
            button.setAttribute('aria-expanded', 'true');
            menu.classList.remove('hidden');
            button.querySelector('svg').style.transform = 'rotate(270deg)';
        }
    }
    
    updateWrapperActiveStates() {
        // Map filter types to their taxonomy names for wrapper identification
        const filterTypeToTaxonomy = {
            'types': ['type_enjeu', 'type_document'],
            'categories': ['category'],
            'themes': ['theme']
        };
        
        // Update each wrapper based on its filter type
        Object.keys(filterTypeToTaxonomy).forEach(filterType => {
            const taxonomies = filterTypeToTaxonomy[filterType];
            const hasActiveFilter = this.activeFilters[filterType];
            
            // Find wrapper containing options for these taxonomies
            taxonomies.forEach(taxonomy => {
                const option = this.container.querySelector(
                    `.filter-option[data-taxonomy="${taxonomy}"]`
                );
                
                if (option) {
                    const wrapper = option.closest('.filter-dropdown-wrapper');
                    if (wrapper) {
                        if (hasActiveFilter) {
                            wrapper.classList.add('active');
                        } else {
                            wrapper.classList.remove('active');
                        }
                    }
                }
            });
        });
    }
    
    closeAllDropdowns() {
        const buttons = this.container.querySelectorAll('.filter-dropdown');
        const menus = this.container.querySelectorAll('.filter-dropdown-menu');
        
        buttons.forEach(button => {
            button.setAttribute('aria-expanded', 'false');
            button.querySelector('svg').style.transform = 'rotate(90deg)';
        });
        
        menus.forEach(menu => {
            menu.classList.add('hidden');
        });
    }
    
    selectFilterOption(option) {
        const taxonomy = option.dataset.taxonomy;
        const termId = option.dataset.termId;
        const termSlug = option.dataset.termSlug;
        
        // Determine filter type based on taxonomy
        let filterType = '';
        switch (taxonomy) {
            case 'type_enjeu':
            case 'type_document':
                filterType = 'types';
                break;
            case 'category':
                filterType = 'categories';
                break;
            case 'theme':
                filterType = 'themes';
                break;
        }
        
        if (filterType) {
            // Remove active class from other options in the same filter type
            const sameTypeOptions = this.container.querySelectorAll(
                `.filter-option[data-taxonomy="${taxonomy}"]`
            );
            sameTypeOptions.forEach(opt => {
                opt.classList.remove('active');
            });
            
            // Add active class to selected option
            option.classList.add('active');
            
            this.activeFilters[filterType] = termId;
            this.currentPage = 1;
            
            // Update wrapper active states
            this.updateWrapperActiveStates();
            
            this.updateFilters();
        }
        
        this.closeAllDropdowns();
    }
    
    removeFilter(filterType) {
        // Remove active class from the option
        if (this.activeFilters[filterType]) {
            const option = this.container.querySelector(
                `.filter-option[data-term-id="${this.activeFilters[filterType]}"]`
            );
            if (option) {
                option.classList.remove('active');
            }
        }
        
        delete this.activeFilters[filterType];
        this.currentPage = 1;
        
        // Update wrapper active states
        this.updateWrapperActiveStates();
        
        this.updateFilters();
    }
    
    resetAllFilters() {
        // Remove active class from all options
        const allOptions = this.container.querySelectorAll('.filter-option.active');
        allOptions.forEach(option => {
            option.classList.remove('active');
        });
        
        this.activeFilters = {};
        this.currentPage = 1;
        
        // Update wrapper active states
        this.updateWrapperActiveStates();
        
        // For events, reload the page instead of AJAX to ensure proper sections
        const postType = this.container.dataset.postType || 'post';
        if (postType === 'evenement') {
            // Remove all query parameters and reload
            const baseUrl = window.location.href.split('?')[0];
            window.location.href = baseUrl;
        } else {
            this.updateFilters();
        }
    }
    
    async updateFilters() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();
        
        // Add loading class to posts grid
        if (this.postsContainer) {
            const postsGrid = this.postsContainer.querySelector('.posts-grid') || this.postsContainer;
            postsGrid.classList.add('loading');
        }
        
        try {
            // Check if archiveFilterAjax is available
            if (typeof archiveFilterAjax === 'undefined') {
                console.error('archiveFilterAjax is not defined');
                this.showError('Configuration manquante. Veuillez actualiser la page.');
                return;
            }
            
            // Update URL
            this.updateURL();
            
            // Get post type
            const postType = this.container.dataset.postType || 'post';
            
            // Prepare AJAX data
            const formData = new FormData();
            formData.append('action', 'filter_archive_posts');
            formData.append('nonce', archiveFilterAjax.nonce);
            formData.append('post_type', postType);
            formData.append('page', this.currentPage);
            
            // Add filters
            Object.keys(this.activeFilters).forEach(filterType => {
                formData.append(`filters[${filterType}]`, this.activeFilters[filterType]);
            });
            
            // Make AJAX request
            const response = await fetch(archiveFilterAjax.ajaxUrl, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Update posts with fade effect
                if (this.postsContainer) {
                    const postType = this.container.dataset.postType || 'post';
                    
                    if ((postType === 'evenement' || postType === 'campagne') && data.upcoming_html !== undefined) {
                        // Handle events and campaigns with separate sections
                        const upcomingGrid = document.getElementById('upcoming-events-grid');
                        const pastGrid = document.getElementById('past-events-grid');
                        
                        if (upcomingGrid) {
                            upcomingGrid.style.opacity = '0.3';
                            setTimeout(() => {
                                if (data.upcoming_html.trim()) {
                                    upcomingGrid.innerHTML = data.upcoming_html;
                                    // For campaigns, use flex layout for upcoming section
                                    if (postType === 'campagne') {
                                        upcomingGrid.className = 'posts-grid flex flex-col @sm:gap-8 @md/lg:gap-20';
                                    } else {
                                        upcomingGrid.className = 'posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3';
                                    }
                                } else {
                                    const noResultsText = postType === 'campagne' ? 'Aucune campagne à venir.' : 'Aucun événement à venir.';
                                    upcomingGrid.innerHTML = `<p class="paragraph-lg text-gray-600">${noResultsText}</p>`;
                                    upcomingGrid.className = 'no-posts text-center py-8';
                                }
                                upcomingGrid.style.opacity = '1';
                            }, 150);
                        }
                        
                        if (pastGrid) {
                            pastGrid.style.opacity = '0.3';
                            setTimeout(() => {
                                if (data.past_html.trim()) {
                                    pastGrid.innerHTML = data.past_html;
                                    pastGrid.className = 'posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3';
                                } else {
                                    const noResultsText = postType === 'campagne' ? 'Aucune campagne passée.' : 'Aucun événement passé.';
                                    pastGrid.innerHTML = `<p class="paragraph-lg text-gray-600">${noResultsText}</p>`;
                                    pastGrid.className = 'no-posts text-center py-8';
                                }
                                pastGrid.style.opacity = '1';
                            }, 150);
                        }
                    } else {
                        // Standard handling for other post types
                        const postsGrid = this.postsContainer.querySelector('.posts-grid') || this.postsContainer;
                        
                        // Fade out
                        postsGrid.style.opacity = '0.3';
                        
                        setTimeout(() => {
                            postsGrid.innerHTML = data.posts_html;
                            // Fade in
                            postsGrid.style.opacity = '1';
                        }, 150);
                    }
                }
                
                // Update filter counts and disabled states
                this.updateFilterCounts(data.filters);
                
                // Update active filter tags
                this.updateActiveFilterTags();
                
                // Update pagination if exists
                this.updatePagination(data.pagination, data.pagination_html);
                
                // Analytics tracking (if available)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'filter_applied', {
                        'event_category': 'Archive Filters',
                        'event_label': Object.keys(this.activeFilters).join(','),
                        'post_type': postType
                    });
                }
            } else {
                console.error('Filter request failed:', data);
                this.showError('Une erreur est survenue lors du filtrage. Veuillez réessayer.');
            }
            
        } catch (error) {
            console.error('Filter error:', error);
            this.showError('Une erreur de connexion est survenue. Vérifiez votre connexion internet.');
        } finally {
            this.isLoading = false;
            this.hideLoading();
            
            // Remove loading class from posts grid
            if (this.postsContainer) {
                const postsGrid = this.postsContainer.querySelector('.posts-grid') || this.postsContainer;
                postsGrid.classList.remove('loading');
            }
        }
    }
    
    updateFilterCounts(filters) {
        Object.keys(filters).forEach(filterType => {
            const terms = filters[filterType];
            
            terms.forEach(term => {
                const option = this.container.querySelector(
                    `.filter-option[data-term-id="${term.term_id}"]`
                );
                
                if (option) {
                    const countSpan = option.querySelector('.text-gray-500, .ml-2');
                    if (countSpan) {
                        countSpan.textContent = `(${term.count})`;
                    }
                    
                    // Update disabled state
                    if (term.count === 0) {
                        option.disabled = true;
                        option.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        option.disabled = false;
                        option.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                    
                    // Update active state
                    const isActive = this.activeFilters[filterType] && 
                                   this.activeFilters[filterType] == term.term_id;
                    
                    if (isActive) {
                        option.classList.add('active');
                    } else {
                        option.classList.remove('active');
                    }
                }
            });
        });
        
        // Update wrapper active states after updating all filter counts
        this.updateWrapperActiveStates();
    }
    
    updateActiveFilterTags() {
        const activeFiltersContainer = this.container.querySelector('.active-filters');
        const filterTagsContainer = activeFiltersContainer.querySelector('.filter-tags');
        const resetButton = activeFiltersContainer.querySelector('.reset-filters');
        
        // Clear existing tags
        filterTagsContainer.innerHTML = '';
        
        // Show/hide active filters section
        const hasActiveFilters = Object.keys(this.activeFilters).length > 0;
        const hasMultipleFilters = Object.keys(this.activeFilters).length > 1;
        
        if (hasActiveFilters) {
            activeFiltersContainer.style.display = 'flex';
            activeFiltersContainer.classList.remove('hidden');
        } else {
            activeFiltersContainer.style.display = 'none';
            activeFiltersContainer.classList.add('hidden');
        }
        
        // Show/hide reset button based on multiple filters
        if (resetButton) {
            if (hasMultipleFilters) {
                resetButton.style.display = 'block';
                resetButton.classList.remove('hidden');
            } else {
                resetButton.style.display = 'none';
                resetButton.classList.add('hidden');
            }
        }
        
        if (!hasActiveFilters) return;
        
        // Check if this is a "single" archive (evenement or campagne)
        const isSingle = this.container.dataset.single === 'true';
        
        // Tag colors
        const tagColors = {
            'types': 'bg-orange',
            'categories': isSingle ? 'bg-orange' : 'bg-yellow',
            'themes': 'bg-light-green-70'
        };
        
        // Create tags for active filters
        Object.keys(this.activeFilters).forEach(filterType => {
            const termId = this.activeFilters[filterType];
            const option = this.container.querySelector(
                `.filter-option[data-term-id="${termId}"]`
            );
            
            if (option) {
                // Get the term name from the first span element or fallback to textContent
                const termNameElement = option.querySelector('span:first-child') || option.querySelector('span');
                const termName = termNameElement ? termNameElement.textContent.trim() : option.textContent.split('(')[0].trim();
                const colorClass = tagColors[filterType] || 'bg-gray-200';
                
                const tag = document.createElement('div');
                tag.className = `filter-tag badge-surface border-0 flex items-center autoscale @sm:gap-2.5 @md/lg:gap-2.5 ${colorClass}`;
                tag.dataset.filter = filterType;
                tag.dataset.term = termId;
                
                tag.innerHTML = `
                    <span>${termName}</span>
                    <button class="remove-tag @sm:w-[9px] @md/lg:w-[9px] @sm:h-[8px] @md/lg:h-[8px] relative" aria-label="Remove filter" data-filter="${filterType}">
                        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 @sm:w-[11px] @sm:h-[1px] @md/lg:w-[11px] @md/lg:h-[1px] bg-dark-green rotate-45"></span>
                        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 @sm:w-[11px] @sm:h-[1px] @md/lg:w-[11px] @md/lg:h-[1px] bg-dark-green -rotate-45"></span>
                    </button>
                `;
                
                filterTagsContainer.appendChild(tag);
            }
        });
    }
    
    updateURL() {
        const postType = this.container.dataset.postType || 'post';
        const hasActiveFilters = Object.keys(this.activeFilters).length > 0;
        
        if (hasActiveFilters || this.currentPage > 1) {
            // When we have filters or pagination, use query parameters
            const url = new URL(window.location.origin + window.location.pathname.replace(/\/page\/\d+\//, '/'));
            
            // Clear existing filter params
            url.searchParams.delete('type_enjeu');
            url.searchParams.delete('type_document');
            url.searchParams.delete('category');
            url.searchParams.delete('theme');
            url.searchParams.delete('paged');
            
            // Add active filters using WordPress parameter names
            Object.keys(this.activeFilters).forEach(filterType => {
                const termId = this.activeFilters[filterType];
                const option = this.container.querySelector(
                    `.filter-option[data-term-id="${termId}"]`
                );
                
                if (option) {
                    const termSlug = option.dataset.termSlug;
                    let paramName = '';
                    
                    switch (filterType) {
                        case 'types':
                            paramName = postType === 'post' ? 'type_enjeu' : 'type_document';
                            break;
                        case 'categories':
                            paramName = 'category';
                            break;
                        case 'themes':
                            paramName = 'theme';
                            break;
                    }
                    
                    if (paramName && termSlug) {
                        url.searchParams.set(paramName, termSlug);
                    }
                }
            });
            
            // Add paged if not 1
            if (this.currentPage > 1) {
                url.searchParams.set('paged', this.currentPage);
            }
            
            // Update browser history
            window.history.pushState({}, '', url.toString());
        } else {
            // No filters and page 1 - redirect to clean home URL
            const cleanURL = window.location.origin + window.location.pathname.replace(/\/page\/\d+\//, '/');
            window.history.pushState({}, '', cleanURL);
        }
    }
    
    initializeFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const postType = this.container.dataset.postType || 'post';
        
        this.activeFilters = {};
        
        // Get filters from URL using WordPress parameter names
        const typeParam = postType === 'post' ? 'type_enjeu' : 'type_document';
        
        if (urlParams.has(typeParam)) {
            const termSlug = urlParams.get(typeParam);
            const taxonomy = postType === 'post' ? 'type_enjeu' : 'type_document';
            const option = this.container.querySelector(
                `.filter-option[data-taxonomy="${taxonomy}"][data-term-slug="${termSlug}"]`
            );
            if (option) {
                this.activeFilters.types = option.dataset.termId;
            }
        }
        
        if (urlParams.has('category')) {
            const termSlug = urlParams.get('category');
            const option = this.container.querySelector(
                `.filter-option[data-taxonomy="category"][data-term-slug="${termSlug}"]`
            );
            if (option) {
                this.activeFilters.categories = option.dataset.termId;
            }
        }
        
        if (urlParams.has('theme')) {
            const termSlug = urlParams.get('theme');
            const option = this.container.querySelector(
                `.filter-option[data-taxonomy="theme"][data-term-slug="${termSlug}"]`
            );
            if (option) {
                this.activeFilters.themes = option.dataset.termId;
            }
        }
        
        // Get paged parameter from URL or from pretty URL format
        let currentPage = parseInt(urlParams.get('paged')) || 1;
        
        // If no paged parameter, check for /page/X/ in the URL path
        if (currentPage === 1) {
            const pathMatch = window.location.pathname.match(/\/page\/(\d+)\//);
            if (pathMatch) {
                currentPage = parseInt(pathMatch[1]);
            }
        }
        
        this.currentPage = currentPage;
        
        // If we have filters from URL, update the display
        if (Object.keys(this.activeFilters).length > 0) {
            this.updateFilters();
        } else {
            this.updateActiveFilterTags();
        }
        
        // Update wrapper active states on initialization
        this.updateWrapperActiveStates();
    }
    
    updatePagination(pagination, paginationHTML = '') {
        // Find existing pagination container
        let paginationContainer = document.querySelector('.pagination');
        let paginationParent = null;
        
        if (paginationContainer) {
            // Store the parent to insert new pagination
            paginationParent = paginationContainer.parentNode;
            // Remove existing pagination
            paginationContainer.remove();
        } else {
            // If no existing pagination, find the container where posts are
            const postsGrid = document.querySelector('.posts-grid');
            if (postsGrid) {
                paginationParent = postsGrid.parentNode; // This should be the .container div
            }
        }
        
        if (paginationParent && pagination.total_pages > 1 && paginationHTML) {
            // Create a temporary div to parse the HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = paginationHTML;
            
            // Get the nav element from the parsed HTML
            const newPagination = tempDiv.querySelector('.pagination');
            
            if (newPagination) {
                // Append the new pagination to the parent
                paginationParent.appendChild(newPagination);
                
                // Add click handlers for pagination links
                this.bindPaginationEvents(newPagination);
            }
        }
    }
    
    bindPaginationEvents(paginationContainer) {
        paginationContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('pagination-link') || e.target.closest('a')) {
                e.preventDefault();
                
                const link = e.target.classList.contains('pagination-link') ? e.target : e.target.closest('a');
                
                // Extract page number from data-page or href
                let page = parseInt(link.dataset.page);
                
                // If data-page is not a number, try to extract from href or text content
                if (!page) {
                    const href = link.getAttribute('href');
                    if (href && href !== '#') {
                        // Extract page number from URL parameters
                        const matches = href.match(/[?&]paged=(\d+)/);
                        if (matches) {
                            page = parseInt(matches[1]);
                        } else {
                            // Check if it's a page number in the URL path
                            const pathMatches = href.match(/\/page\/(\d+)\//);
                            if (pathMatches) {
                                page = parseInt(pathMatches[1]);
                            }
                        }
                    }
                    
                    // If still no page, try to extract from text content for numbered links
                    if (!page) {
                        const text = link.textContent.trim();
                        const numericText = parseInt(text);
                        if (!isNaN(numericText)) {
                            page = numericText;
                        } else if (text === '← Précédent' || text.includes('Précédent')) {
                            page = Math.max(1, this.currentPage - 1);
                        } else if (text === 'Suivant →' || text.includes('Suivant')) {
                            page = this.currentPage + 1;
                        }
                    }
                }
                
                if (page && page !== this.currentPage && page > 0) {
                    this.currentPage = page;
                    this.updateFilters();
                }
            }
        });
    }
    
    showLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.classList.remove('hidden');
            this.loadingOverlay.classList.add('flex');
        }
    }
    
    hideLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.classList.add('hidden');
            this.loadingOverlay.classList.remove('flex');
        }
    }
    
    showError(message) {
        // Create or update error message
        let errorDiv = document.querySelector('.filter-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'filter-error fixed top-4 right-4 bg-red-500 text-white px-6 py-3 @sm:rounded-lg @md/lg:rounded-lg shadow-lg z-50 hidden';
            document.body.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 5000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ArchiveFilterSystem();
});