<?php
$logo = get_field('logo', 'acf-options-global-fields');
$logo_contrasted = get_field('logo_contrasted', 'acf-options-global-fields');

$header_alt = (is_archive() || is_home() || is_search()) ? 'header-alt' : '';
?>

<header class="site-header autoscale group fixed w-full z-50 transition-all [.scroll-hide&]:-translate-y-full [.scrolling&]:bg-white <?php echo $header_alt; ?>">
  <!-- Mobile Header (sm only) -->
  <div class="mobile-header md:hidden flex items-stretch justify-between border-b border-b-light-green z-50 relative group-[&.menu-open]:bg-dark-green transition-colors">
    <?php if ($logo): ?>
      <a href="<?php echo home_url('/'); ?>" class="logo-link @sm:px-5 @sm:py-3">
        <img 
          src="<?php echo esc_url($logo['url']); ?>" 
          alt="<?php echo esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>"
          class="@sm:max-w-[180px] md:max-w-none @sm:h-10 w-auto group-[&.scrolling]:opacity-0 group-[&.header-alt]:opacity-0 group-[&.menu-open]:!opacity-100 transition-opacity"
        />
        <img 
          src="<?php echo esc_url($logo_contrasted['url']); ?>" 
          alt="<?php echo esc_attr($logo_contrasted['alt'] ?: get_bloginfo('name')); ?>"
          class="@sm:max-w-[180px] md:max-w-none @sm:h-10 w-auto absolute @sm:top-3 @sm:left-5 opacity-0 group-[&.scrolling:not(.menu-open)]:opacity-100 group-[&.header-alt:not(.menu-open)]:opacity-100 transition-opacity"
        />
      </a>
    <?php endif; ?>
    
    <div class="flex items-stretch">
      <button
        type="button"
        class="search-mobile-btn flex items-center justify-center text-dark-green @sm:px-4 group-[&.menu-open]:!text-white transition-colors"
        js-search-open
        aria-label="Ouvrir la recherche"
      >
        <svg class="@sm:w-5 @sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
          <circle cx="10.5" cy="10.5" r="7.5"/>
          <path d="M16 16L21 21"/>
        </svg>
      </button>

      <button 
      class="mobile-menu-toggle bg-light-green flex items-center @sm:gap-2 @sm:px-5"
      aria-label="Toggle menu"
      aria-expanded="false"
    >
      <span class="menu">Menu</span>
      <div class="hamburger-icon flex flex-col @sm:gap-1.5">
        <span class="@sm:w-5 @sm:h-[1px] bg-dark-green block transition-transform group-[&.menu-open]:-rotate-45 @sm:group-[&.menu-open]:translate-y-[7px]"></span>
        <span class="@sm:w-5 @sm:h-[1px] bg-dark-green block transition-opacity group-[&.menu-open]:opacity-0"></span>
        <span class="@sm:w-5 @sm:h-[1px] bg-dark-green block transition-transform group-[&.menu-open]:rotate-45 @sm:group-[&.menu-open]:-translate-y-[7px]"></span>
      </div>
    </button>
    </div>
  </div>

  <!-- Mobile Menu Overlay (hidden by default, shown with JS) -->
  <div class="mobile-menu-overlay hidden md:hidden fixed inset-0 theme-dark-green bg-layout-main group-[&.menu-open]:block z-40" aria-hidden="true">

    <nav class="mobile-menus flex flex-col @sm:gap-6 h-full overflow-y-auto  @sm:px-5 @sm:pt-[130px] @sm:pb-[115px]">

      <!-- Mobile Primary Menu -->
      <div class="mobile-primary">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary-menu',
          'menu_class' => 'mobile-primary-menu flex flex-col gap-4 list-none m-0 p-0',
          'container' => false,
          'fallback_cb' => false,
          'walker' => new class extends Walker_Nav_Menu {
            function start_lvl(&$output, $depth = 0, $args = null) {
              $output .= '<ul class="mobile-sub-menu @sm:mt-3 @sm:p-5 flex flex-col @sm:gap-4 @sm:rounded-xl @md/lg:rounded-xl hidden bg-[#D4E8F8]/10">';
            }
            
            function end_lvl(&$output, $depth = 0, $args = null) {
              $output .= '</ul>';
            }
            
            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
              $classes = empty($item->classes) ? [] : (array) $item->classes;
              $has_children = in_array('menu-item-has-children', $classes);
              
              $li_class = $classes ? implode(' ', $classes) : 'menu-item';
              if ($depth === 0 && $has_children) {
                $li_class .= ' mobile-submenu-parent';
              }
              
              $output .= '<li class="' . $li_class . ' group/item">';
              
              if ($depth === 0) {
                if ($has_children) {
                  $output .= '<button class="mobile-submenu-toggle w-full text-left flex items-center justify-between text-white menu hover:text-yellow transition-colors">';
                  $output .= esc_html($item->title);
                  $output .= '<svg class="@sm:w-4 @sm:h-4 transition-transform" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.590475 3.29129C0.931048 2.90639 1.53845 2.85438 1.94713 3.17513L5.18351 5.71513L8.41988 3.17513C8.82857 2.85438 9.43596 2.90639 9.77654 3.29129C10.1171 3.67619 10.0619 4.24823 9.6532 4.56898L5.80017 7.59295C5.44295 7.8733 4.92406 7.8733 4.56684 7.59295L0.713807 4.56898C0.305119 4.24823 0.249901 3.67619 0.590475 3.29129Z" fill="white"/>
                  </svg>';
                  $output .= '</button>';
                } else {
                  $output .= '<a href="' . esc_url($item->url) . '" class="menu-link menu text-white hover:text-yellow group-[.current-menu-item]/item:text-yellow  transition-colors">';
                  $output .= esc_html($item->title);
                  $output .= '</a>';
                }
              } else {
                $output .= '<a href="' . esc_url($item->url) . '" class="block font-safiro font-medium @sm:text-[16px]/normal @sm:tracking-[0.8px] text-white hover:text-yellow group-[.current-menu-item]/item:text-yellow  transition-colors">';
                $output .= esc_html($item->title);
                $output .= '</a>';
              }
            }
            
            function end_el(&$output, $item, $depth = 0, $args = null) {
              $output .= '</li>';
            }
          }
        ]);
        ?>
      </div>
      <!-- Separator -->
      <div class="separator border-t border-[#fff]/20 w-full"></div>
      <!-- Mobile Services Menu -->
      <div class="mobile-services">
        <?php
        wp_nav_menu([
          'theme_location' => 'services-menu',
          'menu_class' => 'mobile-services-menu flex flex-col gap-4 list-none m-0 p-0',
          'container' => false,
          'fallback_cb' => false,
          'walker' => new class extends Walker_Nav_Menu {
            function start_lvl(&$output, $depth = 0, $args = null) {
              $output .= '<ul class="mobile-sub-menu @sm:mt-3 @sm:p-5 flex flex-col @sm:gap-4 @sm:rounded-xl @md/lg:rounded-xl hidden bg-[#D4E8F8]/10">';
            }
            
            function end_lvl(&$output, $depth = 0, $args = null) {
              $output .= '</ul>';
            }
            
            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
              $classes = empty($item->classes) ? [] : (array) $item->classes;
              $has_children = in_array('menu-item-has-children', $classes);
              
              $li_class = $classes ? implode(' ', $classes) : 'menu-item';
              if ($depth === 0 && $has_children) {
                $li_class .= ' mobile-submenu-parent';
              }
              
              $output .= '<li class="' . $li_class . ' group/item">';
              
              if ($depth === 0) {
                if ($has_children) {
                  $output .= '<button class="mobile-submenu-toggle w-full text-left flex items-center justify-between text-white menu hover:text-yellow transition-colors">';
                  $output .= esc_html($item->title);
                  $output .= '<svg class="@sm:w-4 @sm:h-4 transition-transform" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.590475 3.29129C0.931048 2.90639 1.53845 2.85438 1.94713 3.17513L5.18351 5.71513L8.41988 3.17513C8.82857 2.85438 9.43596 2.90639 9.77654 3.29129C10.1171 3.67619 10.0619 4.24823 9.6532 4.56898L5.80017 7.59295C5.44295 7.8733 4.92406 7.8733 4.56684 7.59295L0.713807 4.56898C0.305119 4.24823 0.249901 3.67619 0.590475 3.29129Z" fill="white"/>
                  </svg>';
                  $output .= '</button>';
                } else {
                  $output .= '<a href="' . esc_url($item->url) . '" class="menu-link menu text-white hover:text-yellow group-[.current-menu-item]/item:text-yellow transition-colors">';
                  $output .= esc_html($item->title);
                  $output .= '</a>';
                }
              } else {
                $output .= '<a href="' . esc_url($item->url) . '" class="block font-safiro font-medium @sm:text-[16px]/normal @sm:tracking-[0.8px] text-white hover:text-yellow group-[.current-menu-item]/item:text-yellow  transition-colors">';
                $output .= esc_html($item->title);
                $output .= '</a>';
              }
            }
            
            function end_el(&$output, $item, $depth = 0, $args = null) {
              $output .= '</li>';
            }
          }
        ]);
        ?>
      </div>
      <!-- Mobile CTA Menu -->
      <div class="mobile-cta absolute w-full bottom-0 left-0">
        <?php
        wp_nav_menu([
          'theme_location' => 'cta-menu',
          'menu_class' => 'mobile-cta-menu flex flex-col gap-4 list-none m-0 p-0',
          'container' => false,
          'fallback_cb' => false,
          'walker' => new class extends Walker_Nav_Menu {
            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
              $classes = empty($item->classes) ? [] : (array) $item->classes;
              $li_class = $classes ? implode(' ', $classes) : 'menu-item';
              $output .= '<li class="' . $li_class . '">';
              $output .= '<a href="' . esc_url($item->url) . '" class="menu bg-orange flex justify-center w-full @sm:p-5 @sm:gap-2.5">';
              $output .= esc_html($item->title);
              $output .= '<div class="link-arrows flex items-center @sm:-space-x-1 @md/lg:-space-x-1">
                      <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.40017 9.59312C2.01527 9.25255 1.96327 8.64515 2.28402 8.23646L4.82401 5.00009L2.28402 1.76371C1.96327 1.35503 2.01527 0.74763 2.40017 0.407056C2.78507 0.0664816 3.35712 0.1217 3.67787 0.530389L6.70183 4.38342C6.98219 4.74064 6.98219 5.25953 6.70183 5.61675L3.67787 9.46979C3.35712 9.87847 2.78507 9.93369 2.40017 9.59312Z" fill="currentColor" />
                      </svg>
                      <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.61599 9.59312C2.23109 9.25255 2.17909 8.64515 2.49984 8.23646L5.03983 5.00009L2.49984 1.76371C2.17909 1.35503 2.23109 0.74763 2.61599 0.407056C3.00089 0.0664816 3.57294 0.1217 3.89369 0.530389L6.91765 4.38342C7.19801 4.74064 7.19801 5.25953 6.91765 5.61675L3.89369 9.46979C3.57294 9.87847 3.00089 9.93369 2.61599 9.59312Z" fill="currentColor" />
                      </svg>
                    </div>';
              $output .= '</a>';
              $output .= '</li>';
            }
          }
        ]);
        ?>
      </div>
    </nav>
  </div>




  <!-- Desktop Header (md and up) -->
  <div class="desktop-header hidden md:flex relative border-b border-b-light-green w-full group-[&.header-alt]:border-dark-green group-[&.scrolling]:border-dark-green">
    <!-- Logo -->
    <?php if ($logo): ?>
      <a href="<?php echo home_url('/'); ?>" class="logo-link flex items-center @md/lg:p-10 z-20">
        <img 
          src="<?php echo esc_url($logo['url']); ?>" 
          alt="<?php echo esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>"
          class="@md/lg:h-10 w-auto group-[&.scrolling]:opacity-0 group-[&.header-alt]:opacity-0 transition-opacity"
        />
        <img 
          src="<?php echo esc_url($logo_contrasted['url']); ?>" 
          alt="<?php echo esc_attr($logo_contrasted['alt'] ?: get_bloginfo('name')); ?>"
          class="@md/lg:h-10 w-auto absolute @md/lg:top-10 @md/lg:left-10 opacity-0 group-[&.scrolling]:opacity-100 group-[&.header-alt]:opacity-100 transition-opacity"
        />
      </a>
    <?php endif; ?>
    <div class="flex-1 flex flex-col">
    <!-- Top Bar: Search + CTA -->
    <div class="top-bar bg-light-green flex justify-end items-center @md/lg:gap-8 w-full">

      <!-- Search trigger -->
      <button
        type="button"
        class="search-header-btn flex items-center @md/lg:gap-2 text-dark-green hover:opacity-80 transition-opacity"
        js-search-open
        aria-label="Ouvrir la recherche"
      >
        <svg class="@md/lg:w-[15px] @md/lg:h-[15px] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
          <circle cx="10.5" cy="10.5" r="7.5"/>
          <path d="M16 16L21 21"/>
        </svg>
        <span class="menu inline-flex !leading-tight">Recherche</span>
      </button>

      <!-- CTA Menu -->
        <?php
        wp_nav_menu([
          'theme_location' => 'cta-menu',
          'menu_id' => 'cta-menu',
          'menu_class' => 'cta-menu list-none m-0 p-0',
          'container' => false,
          'fallback_cb' => false,
          'walker' => new class extends Walker_Nav_Menu {
            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
              $classes = empty($item->classes) ? [] : (array) $item->classes;
              $li_class = $classes ? implode(' ', $classes) : 'menu-item';
              $output .= '<li class="' . $li_class . '">';
              $output .= '<a href="' . esc_url($item->url) . '" class="cta-link block menu inline-flex !leading-tight bg-orange @md/lg:p-5 hover:bg-yellow transition-colors">';
              $output .= esc_html($item->title);
              $output .= '</a>';
              $output .= '</li>';
            }
          } 
        ]);
        ?>
    </div>

    <!-- Bottom Bar: Services + Primary Menu -->
    <div class="bottom-bar flex justify-between flex-1">
      <!-- Services Menu with separators -->
          <?php
          wp_nav_menu([
            'theme_location' => 'services-menu',
            'menu_id' => 'services-menu',
            'menu_class' => 'services-menu list-none border-r border-l border-light-green flex items-center @md/lg:px-6 group-[&.header-alt]:border-dark-green group-[&.scrolling]:border-dark-green',
            'container' => false,
            'fallback_cb' => false,
            'walker' => new class extends Walker_Nav_Menu {
              function start_lvl(&$output, $depth = 0, $args = null) {
              $output .= '<ul class="sub-menu z-10 absolute flex flex-col @md/lg:gap-2 top-full @md/lg:-left-12 bg-white @sm:rounded-xl @md/lg:rounded-xl hidden @md/lg:p-2 @md/lg:min-w-[250px]" style="box-shadow: 0 0 20px 0 rgba(13, 53, 88, 0.15);">';
              }
              
              function end_lvl(&$output, $depth = 0, $args = null) {
                $output .= '</ul>';
              }
              
              function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                $classes = empty($item->classes) ? [] : (array) $item->classes;
                $has_children = in_array('menu-item-has-children', $classes);
                
                $li_class = $classes ? implode(' ', $classes) : 'menu-item';
                if ($depth === 0) {
                  $li_class .= ' flex items-center gap-2';
                  if ($has_children) {
                    $li_class .= ' relative submenu-parent group/item @md/lg:py-3';
                  }
                } else {
                  $li_class .= ' @md/lg:p-2 hover:bg-yellow [.current-menu-item:not(:hover)&]:bg-light-green transition-colors @md/lg:rounded-[10px]';
                }
                
                $output .= '<li class="' . $li_class . '">';
                
                if ($depth === 0) {
                  $output .= '<a href="' . esc_url($item->url) . '" class="menu-link menu inline-flex !leading-tight  text-white hover:opacity-80 group-[.current-menu-ancestor]/item:opacity-80 transition-all group-[&.header-alt]:text-dark-green group-[&.scrolling]:text-dark-green">';
                  $output .= esc_html($item->title);
                  $output .= '</a>';
                  
                  if ($has_children) {
                    $output .= '<button class="submenu-toggle text-white group-[&.header-alt]:text-dark-green group-[&.scrolling]:text-dark-green" aria-label="Toggle submenu for ' . esc_attr($item->title) . '">';
                    $output .= '<svg class="@md/lg:w-[9.216px] @md/lg:h-[9.633px] transition-transform" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M0.590475 3.29129C0.931048 2.90639 1.53845 2.85438 1.94713 3.17513L5.18351 5.71513L8.41988 3.17513C8.82857 2.85438 9.43596 2.90639 9.77654 3.29129C10.1171 3.67619 10.0619 4.24823 9.6532 4.56898L5.80017 7.59295C5.44295 7.8733 4.92406 7.8733 4.56684 7.59295L0.713807 4.56898C0.305119 4.24823 0.249901 3.67619 0.590475 3.29129Z" />
                    </svg>';
                    $output .= '</button>';
                  }
                } else {
                  $output .= '<a href="' . esc_url($item->url) . '" class="menu inline-flex !leading-tight">';
                  $output .= esc_html($item->title);
                  $output .= '</a>';
                }
              }
              
              function end_el(&$output, $item, $depth = 0, $args = null) {
                $output .= '</li>';
              }
            }
          ]);
          ?>

      <!-- Primary Menu -->
        <?php
        wp_nav_menu([
          'theme_location' => 'primary-menu',
          'menu_id' => 'primary-menu',
          'menu_class' => 'primary-menu flex items-center justify-end ml-auto @md/lg:gap-9 list-none m-0 @md/lg:px-6',
          'container' => false,
          'fallback_cb' => false,
          'walker' => new class extends Walker_Nav_Menu {
            function start_lvl(&$output, $depth = 0, $args = null) {
              $output .= '<ul class="sub-menu z-10 absolute flex flex-col @md/lg:gap-2 top-full @md/lg:-left-6 bg-white @sm:rounded-xl @md/lg:rounded-xl hidden @md/lg:p-2 @md/lg:min-w-[250px]" style="box-shadow: 0 0 20px 0 rgba(13, 53, 88, 0.15);">';
            }
            
            function end_lvl(&$output, $depth = 0, $args = null) {
              $output .= '</ul>';
            }
            
            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
              $classes = empty($item->classes) ? [] : (array) $item->classes;
              $has_children = in_array('menu-item-has-children', $classes);
              
              $li_class = $classes ? implode(' ', $classes) : 'menu-item';
              if ($depth === 0) {
                $li_class .= ' flex items-center gap-2';
                if ($has_children) {
                  $li_class .= ' relative submenu-parent group/item @md/lg:py-3';
                }
              } else {
                $li_class .= ' @md/lg:p-2 hover:bg-yellow [.current-menu-item:not(:hover)&]:bg-light-green transition-colors @md/lg:rounded-[10px]';
              }
              
              $output .= '<li class="' . $li_class . '">';
              
              if ($depth === 0) {
                $output .= '<a href="' . esc_url($item->url) . '" class="menu-link menu inline-flex !leading-tight text-dark-green hover:opacity-80 group-[.current-menu-ancestor]/item:opacity-80 transition-opacity">';
                $output .= esc_html($item->title);
                $output .= '</a>';
                
                if ($has_children) {
                  $output .= '<button class="submenu-toggle" aria-label="Toggle submenu for ' . esc_attr($item->title) . '">';
                  $output .= '<svg class="@md/lg:w-[9.216px] @md/lg:h-[9.633px] transition-transform" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.590475 3.29129C0.931048 2.90639 1.53845 2.85438 1.94713 3.17513L5.18351 5.71513L8.41988 3.17513C8.82857 2.85438 9.43596 2.90639 9.77654 3.29129C10.1171 3.67619 10.0619 4.24823 9.6532 4.56898L5.80017 7.59295C5.44295 7.8733 4.92406 7.8733 4.56684 7.59295L0.713807 4.56898C0.305119 4.24823 0.249901 3.67619 0.590475 3.29129Z" fill="#012E31"/>
                  </svg>';
                  $output .= '</button>';
                }
              } else {
                $output .= '<a href="' . esc_url($item->url) . '" class="menu inline-flex !leading-tight">';
                $output .= esc_html($item->title);
                $output .= '</a>';
              }
            }
            
            function end_el(&$output, $item, $depth = 0, $args = null) {
              $output .= '</li>';
            }
          }
        ]);
        ?>

      </div>
    </div>
  </div>
</header>

<?php get_template_part('src/components/search/markup', 'search'); ?>
