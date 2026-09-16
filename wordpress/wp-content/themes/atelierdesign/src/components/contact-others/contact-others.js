/**
 * Contact Others Component - Dropdown Tabs Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  initContactOthersDropdowns();
});

function initContactOthersDropdowns() {
  // Initialize all group dropdowns
  const groupDropdowns = document.querySelectorAll('[data-dropdown^="group-options-"]');
  groupDropdowns.forEach(dropdown => {
    initDropdown(dropdown);
  });
}

function initDropdown(toggleButton) {
  const dropdownId = toggleButton.getAttribute('data-dropdown');
  const dropdownMenu = document.getElementById(dropdownId);
  const selectedSpan = toggleButton.querySelector('.selected-item');
  const arrow = toggleButton.querySelector('.dropdown-arrow');

  if (!dropdownMenu || !selectedSpan) return;

  // Toggle dropdown visibility
  toggleButton.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const isOpen = !dropdownMenu.classList.contains('hidden');
    
    // Close all other dropdowns
    closeAllDropdowns();
    
    if (!isOpen) {
      openDropdown(dropdownMenu, arrow, toggleButton);
    }
  });

  // Handle option selection
  const options = dropdownMenu.querySelectorAll('.group-item-option');
  options.forEach(option => {
    option.addEventListener('click', function(e) {
      e.preventDefault();
      
      const groupIndex = this.getAttribute('data-group-index');
      const itemIndex = this.getAttribute('data-item-index');
      const name = this.textContent.trim();
      
      // Update selected text
      selectedSpan.textContent = name;
      
      // Show corresponding details
      showDetails(groupIndex, itemIndex);
      
      // Close dropdown
      closeDropdown(dropdownMenu, arrow, toggleButton);
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    if (!toggleButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
      closeDropdown(dropdownMenu, arrow, toggleButton);
    }
  });

  // Close dropdown on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeDropdown(dropdownMenu, arrow, toggleButton);
    }
  });
}

function openDropdown(dropdownMenu, arrow, toggleButton) {
  dropdownMenu.classList.remove('hidden');
  arrow.style.transform = 'rotate(90deg)';
  toggleButton.setAttribute('aria-expanded', 'true');
}

function closeDropdown(dropdownMenu, arrow, toggleButton) {
  dropdownMenu.classList.add('hidden');
  arrow.style.transform = 'rotate(0deg)';
  toggleButton.setAttribute('aria-expanded', 'false');
}

function closeAllDropdowns() {
  const allDropdowns = document.querySelectorAll('.dropdown-menu');
  const allArrows = document.querySelectorAll('.dropdown-arrow');
  const allToggleButtons = document.querySelectorAll('[data-dropdown]');
  
  allDropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
  allArrows.forEach(arrow => arrow.style.transform = 'rotate(0deg)');
  allToggleButtons.forEach(button => button.setAttribute('aria-expanded', 'false'));
}

function showDetails(groupIndex, itemIndex) {
  const detailsId = `${groupIndex}-${itemIndex}`;
  
  // Hide all details in this group
  const allGroupDetails = document.querySelectorAll(`[data-group-details^="${groupIndex}-"]`);
  allGroupDetails.forEach(detail => {
    detail.classList.add('hidden');
    detail.classList.remove('animate-in');
  });
  
  // Show selected details with animation
  const selectedDetails = document.querySelector(`[data-group-details="${detailsId}"]`);
  if (selectedDetails) {
    selectedDetails.classList.remove('hidden');
    
    // Trigger animation by adding class after a tiny delay
    setTimeout(() => {
      selectedDetails.classList.add('animate-in');
    }, 10);
    
    // Smooth scroll to details
    setTimeout(() => {
      selectedDetails.scrollIntoView({ 
        behavior: 'smooth', 
        block: 'nearest' 
      });
    }, 150);
  }
}

// Export for potential external use
window.contactOthersDropdowns = {
  init: initContactOthersDropdowns,
  closeAll: closeAllDropdowns
};