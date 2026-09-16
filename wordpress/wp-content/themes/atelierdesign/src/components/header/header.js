const header = document.querySelector('.site-header');
// Mobile menu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
  const menuToggle = document.querySelector('.mobile-menu-toggle');
  const menuOverlay = document.querySelector('.mobile-menu-overlay');
  if (menuToggle) {
    menuToggle.addEventListener('click', function(e) {
      e.preventDefault();
      header.classList.toggle('menu-open');
      
      if (header.classList.contains('menu-open')) {
        menuOverlay.setAttribute('aria-hidden', 'false');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        document.body.setAttribute("data-lenis-prevent", "true");
      } else {
        menuOverlay.setAttribute('aria-hidden', 'true');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        document.body.removeAttribute("data-lenis-prevent");
      }
    });
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && header.classList.contains('menu-open')) {
          menuToggle?.click();
      }
  });
  }

  // Desktop submenu functionality
  const submenuToggles = document.querySelectorAll('.submenu-toggle');
  submenuToggles.forEach(toggle => {
    const parent = toggle.closest('.submenu-parent');
    const submenu = parent.querySelector('.sub-menu');
    
    if (submenu) {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Close other open submenus
        document.querySelectorAll('.submenu-parent .sub-menu').forEach(otherSubmenu => {
          if (otherSubmenu !== submenu) {
            otherSubmenu.classList.add('hidden');
            const otherToggle = otherSubmenu.parentElement.querySelector('.submenu-toggle svg');
            otherToggle.classList.remove('rotate-180');
          }
        });
        
        // Toggle current submenu
        submenu.classList.toggle('hidden');
        const icon = toggle.querySelector('svg');
        icon.classList.toggle('rotate-180');
      });
      
      // Close submenu when clicking outside
      document.addEventListener('click', function(e) {
        if (!parent.contains(e.target)) {
          submenu.classList.add('hidden');
          const icon = toggle.querySelector('svg');
          icon.classList.remove('rotate-180');
        }
      });
    }
  });

  // Handle parent menu link clicks for submenus on desktop
  const submenuParents = document.querySelectorAll('.submenu-parent');
  submenuParents.forEach(parent => {
    const menuLink = parent.querySelector('.menu-link');
    const submenu = parent.querySelector('.sub-menu');
    const toggle = parent.querySelector('.submenu-toggle');
    
    if (menuLink && submenu && toggle) {
      menuLink.addEventListener('click', function(e) {
        // Only prevent default on desktop (check if we're not in mobile view)
        if (window.innerWidth >= 768) { // md breakpoint
          e.preventDefault();
          e.stopPropagation();
          
          // Close other open submenus
          document.querySelectorAll('.submenu-parent .sub-menu').forEach(otherSubmenu => {
            if (otherSubmenu !== submenu) {
              otherSubmenu.classList.add('hidden');
              const otherToggle = otherSubmenu.parentElement.querySelector('.submenu-toggle svg');
              otherToggle.classList.remove('rotate-180');
            }
          });
          
          // Toggle current submenu
          submenu.classList.toggle('hidden');
          const icon = toggle.querySelector('svg');
          icon.classList.toggle('rotate-180');
        }
      });
    }
  });

  // Desktop hover functionality for submenus
  submenuParents.forEach(parent => {
    const submenu = parent.querySelector('.sub-menu');
    const toggle = parent.querySelector('.submenu-toggle');
    let hoverTimeout;
    
    if (submenu && toggle) {
      // Show submenu on hover (desktop only)
      parent.addEventListener('mouseenter', function() {
        if (window.innerWidth >= 600) { // md breakpoint
          clearTimeout(hoverTimeout);
          
          // Close other open submenus
          document.querySelectorAll('.submenu-parent .sub-menu').forEach(otherSubmenu => {
            if (otherSubmenu !== submenu) {
              otherSubmenu.classList.add('hidden');
              const otherToggle = otherSubmenu.parentElement.querySelector('.submenu-toggle svg');
              otherToggle.classList.remove('rotate-180');
            }
          });
          
          // Show current submenu
          submenu.classList.remove('hidden');
          const icon = toggle.querySelector('svg');
          icon.classList.add('rotate-180');
        }
      });
      
      // Hide submenu on mouse leave with delay (desktop only)
      parent.addEventListener('mouseleave', function() {
        if (window.innerWidth >= 600) { // md breakpoint
          hoverTimeout = setTimeout(() => {
            submenu.classList.add('hidden');
            const icon = toggle.querySelector('svg');
            icon.classList.remove('rotate-180');
          }, 200); // Small delay to prevent flickering
        }
      });
    }
  });

  // Mobile submenu functionality
  const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
  mobileSubmenuToggles.forEach(toggle => {
    const parent = toggle.closest('.mobile-submenu-parent');
    const submenu = parent.querySelector('.mobile-sub-menu');
    
    if (submenu) {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Toggle current submenu
        submenu.classList.toggle('hidden');
        const icon = toggle.querySelector('svg');
        icon.classList.toggle('rotate-180');
      });
    }
  });
});


let lastScrollTop = 0;
["DOMContentLoaded", "load", "resize", "scroll"].forEach((event) => {
  window.addEventListener(event, () => {
    if (window.scrollY <= 5) {
      header.classList.remove("scrolling");
    } else {
      header.classList.add("scrolling");
    }
    if (window.scrollY > lastScrollTop + 100) {
      header.classList.add("scroll-hide");
      lastScrollTop = window.scrollY;
    } else if (window.scrollY < lastScrollTop - 100) {
      header.classList.remove("scroll-hide");
      lastScrollTop = window.scrollY;
    } else if (window.scrollY < 250) {
      header.classList.remove("scroll-hide");
      lastScrollTop = window.scrollY;
    }

    // Close all open desktop submenus on resize to prevent layout issues
    if (event === 'resize') {
      document.querySelectorAll('.submenu-parent .sub-menu').forEach(submenu => {
        submenu.classList.add('hidden');
        const toggle = submenu.parentElement.querySelector('.submenu-toggle svg');
        if (toggle) {
          toggle.classList.remove('rotate-180');
        }
      });
    }
  });
});