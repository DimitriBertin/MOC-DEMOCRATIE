<?php
/**
 * Footer Component
 */

$newsletter = get_field('footer_newsletter', 'acf-options-global-fields') ?: [];
$address = get_field('footer_address', 'acf-options-global-fields') ?: [];
$socials = get_field('footer_socials', 'acf-options-global-fields') ?: [];
$logo = get_field('logo', 'acf-options-global-fields') ?: null;

// Reusable Footer Menu Walker (for services, primary, secondary, cta menus)
class Footer_Menu_Walker extends Walker_Nav_Menu {
  private $menu_type;
  
  public function __construct($menu_type = 'default') {
    $this->menu_type = $menu_type;
  }
  
  function start_lvl(&$output, $depth = 0, $args = null) {
    $output .= '<ul class="sub-menu hidden flex-col @sm:my-2 md/lg:my-2 @sm:ml-4 @md/lg:ml-4 @sm:gap-1.5 @md/lg:gap-1.5">';
  }
  
  function end_lvl(&$output, $depth = 0, $args = null) {
    $output .= '</ul>';
  }
  
  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $classes = empty($item->classes) ? [] : (array) $item->classes;
    $has_children = in_array('menu-item-has-children', $classes);
    
    $li_class = $classes ? implode(' ', $classes) : 'menu-item';
    
    // Different classes based on depth and menu type
    if ($depth === 0) {
      if ($this->menu_type === 'moc') {
        $li_class .= ' footer-menu-item';
        if ($has_children) {
          $li_class .= ' relative footer-submenu-parent cursor-pointer';
        }
      } else {
        $li_class .= ' footer-menu-item';
        if ($has_children) {
          $li_class .= ' relative footer-submenu-parent cursor-pointer';
        }
      }
    } else {
      $li_class .= ' footer-submenu-item';
    }
    
    $output .= '<li class="' . $li_class . ' group/item">';
    
    if ($depth === 0) {
      if ($this->menu_type === 'moc') {
        // MOC menu style - inline layout
        $output .= '<a href="' . esc_url($item->url) . '" class="menu inline-flex text-white transition-colors group-hover/item:text-yellow group-[.current-menu-item]/item:text-yellow">';
        $output .= esc_html($item->title);
        if ($has_children) {
          $output .= '<button class="footer-submenu-toggle @sm:ml-2 @md/lg:ml-2 text-white group-hover/item:text-yellow touch-manipulation" aria-label="Toggle submenu for ' . esc_attr($item->title) . '" type="button">';
          $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none" class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 transition-transform duration-200"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.508443 2.85574C0.849017 2.47084 1.45641 2.41884 1.8651 2.73959L5.10148 5.27958L8.33785 2.73959C8.74654 2.41884 9.35393 2.47084 9.69451 2.85574C10.0351 3.24064 9.97986 3.81268 9.57117 4.13343L5.71814 7.1574C5.36092 7.43776 4.84203 7.43776 4.48481 7.1574L0.631776 4.13343C0.223087 3.81268 0.16787 3.24064 0.508443 2.85574Z"  fill="currentColor"/></svg>';
          $output .= '</button>';
        }
        $output .= '</a>';
      } else {
        // Regular footer menu style
        $output .= '<a href="' . esc_url($item->url) . '" class="menu inline-flex text-white transition-colors hover:text-yellow group-[.current-menu-item]/item:text-yellow">';
        $output .= esc_html($item->title);
        $output .= '</a>';
      }
    } else {
      // Submenu items
      $output .= '<a href="' . esc_url($item->url) . '" class="font-safiro font-medium @sm:text-[16px]/normal @sm:tracking-[0.8px] @md/lg:text-[16px]/normal @md/lg:tracking-[0.8px] text-white transition-colors hover:text-yellow group-[.current-menu-item]/item:text-yellow">';
      $output .= esc_html($item->title);
      $output .= '</a>';
    }
  }
  
  function end_el(&$output, $item, $depth = 0, $args = null) {
    $output .= '</li>';
  }
}
?>

<footer class="site-footer theme-dark-green bg-layout-main">
  <div class="container @sm:py-12 @md/lg:py-12">
    <div class="autoscale">
    <?php if (!empty($newsletter['cta'])): ?>
    <!-- Newsletter Section -->
    <div class="newsletter-section flex w-full items-center mm-sm:flex-col mm-sm:text-center @sm:gap-4 @md/lg:gap-10 @sm:pb-[78px] @md/lg:pb-14 justify-between  md:border-b md:border-[#D7DB31]/60">
      
      <div class="newsletter-title heading-xl heading-primary">
        <?php echo esc_html($newsletter['title'] ?? 'Restez informé'); ?>
      </div>
      
      <div class="newsletter-text paragraph-lg paragraph-primary">
        <?php echo esc_html($newsletter['text'] ?? 'lorem ipsum dolor amet consectuor'); ?>
      </div>
      
      
        <a class="button-flat button-primary mm-sm:w-full mm-sm:justify-center" href="<?php echo esc_url($newsletter['cta']['url']); ?>" 
           target="<?php echo $newsletter['cta']['target'] ?: '_self'; ?>">
          <div class="button-title"><?php echo esc_html($newsletter['cta']['title']); ?></div>
        </a>
      
      
    </div>
    <?php endif; ?>
    <!-- Main Content Section -->
    <div class="footer-content flex mm-sm:flex-col w-full border-b border-[#D7DB31]/60 @sm:pb-14 @md/lg:py-14 @md:gap-[60px] @lg:gap-[76px]">
      <!-- MOC Logo -->
      <?php if ($logo): ?>
        <a href="<?php echo home_url('/'); ?>" class="logo-link">
          <img 
            src="<?php echo esc_url($logo['url']); ?>" 
            alt="<?php echo esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>"
            class="@md/lg:h-10 md:w-auto mm-sm:w-full mm-sm:h-auto"
          />
        </a>
      <?php endif; ?>

      <!-- MOC Menu Column -->
      <div class="flex flex-col flex-1 mm-sm:border-t mm-sm:border-[#D7DB31]/60  @sm:pt-8 @md/lg:pt-0  @sm:mt-16 @md/lg:mt-0">
        <div class="heading-sm heading-primary @sm:mb-5 @md/lg:mb-5">
          MOC
        </div>
        
        <ul class="footer-menu list-none flex flex-col  @md/lg:gap-1.5">
          <?php
          wp_nav_menu([
            'theme_location' => 'services-menu',
            'container' => false,
            'items_wrap' => '%3$s',
            'fallback_cb' => false,
            'walker' => new Footer_Menu_Walker('moc')
          ]);
          
          wp_nav_menu([
            'theme_location' => 'primary-menu',
            'container' => false,
            'items_wrap' => '%3$s',
            'fallback_cb' => false,
            'walker' => new Footer_Menu_Walker('moc')
          ]);
          ?>
        </div>
      </ul>

      <!-- Menu Column & Address Section -->
        
        <!-- Main Menu Column -->
        <div class="flex flex-col flex-1 mm-sm:border-t mm-sm:border-[#D7DB31]/60 @sm:pt-8 @md/lg:pt-0  @sm:mt-8 @md/lg:mt-0">
          <div class="heading-sm heading-primary @sm:mb-5 @md/lg:mb-5">
            Menu
          </div>
          
          <ul class="footer-menu list-none flex flex-col  @md/lg:gap-1.5">
            <?php
            wp_nav_menu([
              'theme_location' => 'secondary-menu',
              'container' => false,
              'items_wrap' => '%3$s',
              'fallback_cb' => false,
              'walker' => new Footer_Menu_Walker('default')
            ]);
            
            wp_nav_menu([
              'theme_location' => 'cta-menu',
              'container' => false,
              'items_wrap' => '%3$s',
              'fallback_cb' => false,
              'walker' => new Footer_Menu_Walker('default')
            ]);
            ?>
          </ul>
        </div>
        <!-- Address & Social Section -->
        <div class="flex flex-col  flex-1  mm-sm:border-t mm-sm:border-[#D7DB31]/60 @sm:pt-8 @md/lg:pt-0  @sm:mt-8 @md/lg:mt-0 md:border-l md:border-[#D7DB31]/60 @md/lg:pl-14">
          
          <!-- Address Section -->
          <div class="address-section">
            <div class="heading-sm heading-primary @sm:mb-5 @md/lg:mb-5">
              <?php echo esc_html($address['title'] ?? 'Adresse & Contact'); ?>
            </div>
            <div class="paragraph-sm paragraph-primary"> 
              <?php 
              if (!empty($address['content'])) {
                echo wp_kses_post($address['content']);
              }
              ?>
            </div>
          </div>

          <!-- Social Networks Section -->
          <div class="social-section flex flex-col items-stretch justify-start  @sm:mt-8 @md/lg:mt-8">
            <div class="heading-sm heading-primary  @sm:mb-5 @md/lg:mb-5">
              <?php echo esc_html($socials['title'] ?? 'Suivez-nous'); ?>
            </div>
            
            <div class="social-media flex items-start  @sm:gap-2 @md/lg:gap-2">
              <?php if (!empty($socials['facebook'])): ?>
                <a href="<?php echo esc_url($socials['facebook']); ?>" target="_blank" rel="noopener" class="social-link group/social">
                  <svg class="@sm:w-9 @sm:h-9 @md/lg:w-9 @md/lg:h-9" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.25" cx="18.3501" cy="18" r="18" transform="rotate(90 18.3501 18)" fill="white" class="group-hover/social:opacity-40 transition-opacity"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.3501 23V17.8235H15.3501V15.8824H17.3501V14.8399C17.3501 12.8664 18.3432 12 19.9667 12C20.7442 12 21.1554 12.0582 21.3501 12.0848V13.9412H20.2428C19.5537 13.9412 19.3501 14.3087 19.3501 15.0528V15.8824H21.3328L21.0587 17.8235H19.3501V23H17.3501Z" fill="white" class="group-hover/social:fill-yellow transition-colors"/>
                  </svg>
                </a>
              <?php endif; ?>

              <?php if (!empty($socials['instagram'])): ?>
                <a href="<?php echo esc_url($socials['instagram']); ?>" target="_blank" rel="noopener" class="social-link group/social">
                  <svg class="@sm:w-9 @sm:h-9 @md/lg:w-9 @md/lg:h-9" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.25" cx="17.8286" cy="17.4785" r="17.4785" transform="rotate(90 17.8286 17.4785)" fill="white" class="group-hover/social:opacity-40 transition-opacity"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M23 15.2998C22.9999 13.4774 21.5226 12.0001 19.7002 12H15.2998C13.4774 12.0001 12.0001 13.4774 12 15.2998V19.7002C12.0001 21.5226 13.4774 22.9999 15.2998 23H19.7002C21.5226 22.9999 22.9999 21.5226 23 19.7002V15.2998ZM14.75 17.5C14.75 15.9834 15.9834 14.75 17.5 14.75C19.0166 14.75 20.25 15.9834 20.25 17.5C20.25 19.0166 19.0166 20.25 17.5 20.25C15.9834 20.25 14.75 19.0166 14.75 17.5ZM20.25 14.2002C20.25 13.9253 20.5249 13.6505 20.7998 13.6504C21.0748 13.6504 21.3496 13.9252 21.3496 14.2002C21.3495 14.4751 21.0747 14.75 20.7998 14.75C20.5249 14.7499 20.2501 14.4751 20.25 14.2002ZM15.85 17.5C15.85 16.5887 16.5887 15.85 17.5 15.85C18.4113 15.85 19.15 16.5887 19.15 17.5C19.15 18.4113 18.4113 19.15 17.5 19.15C16.5887 19.15 15.85 18.4113 15.85 17.5Z" fill="white" class="group-hover/social:fill-yellow transition-colors"/>
                  </svg>
                </a>
              <?php endif; ?>

              <?php if (!empty($socials['youtube'])): ?>
                <a href="<?php echo esc_url($socials['youtube']); ?>" target="_blank" rel="noopener" class="social-link group/social">
                  <svg class="@sm:w-9 @sm:h-9 @md/lg:w-9 @md/lg:h-9" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.25" cx="17.7856" cy="17.4785" r="17.4785" transform="rotate(90 17.7856 17.4785)" fill="white" class="group-hover/social:opacity-40 transition-opacity"/>
                    <path d="M17.806 13H17.8783C18.5462 13.0024 21.9305 13.0264 22.843 13.2683C23.1189 13.3421 23.3702 13.4859 23.572 13.6855C23.7738 13.885 23.9188 14.1333 23.9928 14.4054C24.0748 14.7097 24.1325 15.1125 24.1715 15.5281L24.1797 15.6114L24.1975 15.8196L24.204 15.9028C24.2568 16.6348 24.2633 17.3202 24.2642 17.47V17.53C24.2633 17.6854 24.256 18.4173 24.1975 19.1796L24.191 19.2637L24.1837 19.347C24.1431 19.8051 24.083 20.2599 23.9928 20.5946C23.9188 20.8667 23.7738 21.115 23.572 21.3145C23.3702 21.5141 23.1189 21.6579 22.843 21.7317C21.9004 21.9816 18.3179 21.9992 17.8214 22H17.7061C17.455 22 16.4165 21.9952 15.3277 21.9584L15.1896 21.9536L15.1189 21.9504L14.98 21.9447L14.841 21.9391C13.9391 21.8999 13.0802 21.8366 12.6845 21.7309C12.4087 21.6572 12.1574 21.5135 11.9557 21.3141C11.7539 21.1147 11.6088 20.8666 11.5347 20.5946C11.4445 20.2607 11.3844 19.8051 11.3438 19.347L11.3373 19.2629L11.3308 19.1796C11.2905 18.637 11.2682 18.0933 11.2642 17.5492L11.2642 17.4508C11.2658 17.2786 11.2723 16.6836 11.3162 16.027L11.3219 15.9445L11.3243 15.9028L11.3308 15.8196L11.3487 15.6114L11.3568 15.5281C11.3958 15.1125 11.4535 14.7089 11.5356 14.4054C11.6095 14.1333 11.7546 13.885 11.9563 13.6855C12.1581 13.4859 12.4095 13.3421 12.6853 13.2683C13.081 13.1642 13.9399 13.1001 14.8418 13.0601L14.98 13.0545L15.1197 13.0496L15.1896 13.0472L15.3285 13.0416C16.1019 13.0171 16.8755 13.0035 17.6492 13.0008L17.806 13ZM16.4645 15.5705V19.4287L19.8423 17.5004L16.4645 15.5705Z" fill="white" class="group-hover/social:fill-yellow transition-colors"/>
                  </svg>
                </a>
              <?php endif; ?>

              <?php if (!empty($socials['linkedin'])): ?>
                <a href="<?php echo esc_url($socials['linkedin']); ?>" target="_blank" rel="noopener" class="social-link group/social">
                  <svg class="@sm:w-9 @sm:h-9 @md/lg:w-9 @md/lg:h-9" viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.25" cx="17.7427" cy="17.4785" r="17.4785" transform="rotate(90 17.7427 17.4785)" fill="white" class="group-hover/social:opacity-40 transition-opacity"/>
                    <path d="M12.2212 11.8595C12.2212 11.3848 12.6157 11 13.1024 11H23.3399C23.8267 11 24.2212 11.3848 24.2212 11.8595V22.1405C24.2212 22.6152 23.8267 23 23.3399 23H13.1024C12.6157 23 12.2212 22.6152 12.2212 22.1405V11.8595ZM15.9284 21.0455V15.6267H14.1277V21.0455H15.9284ZM15.0284 14.8865C15.6562 14.8865 16.0469 14.471 16.0469 13.9505C16.0357 13.4188 15.6569 13.0145 15.0404 13.0145C14.4239 13.0145 14.0212 13.4195 14.0212 13.9505C14.0212 14.471 14.4119 14.8865 15.0164 14.8865H15.0284ZM18.7094 21.0455V18.0192C18.7094 17.8573 18.7214 17.6953 18.7694 17.5798C18.8992 17.2565 19.1954 16.9212 19.6934 16.9212C20.3452 16.9212 20.6054 17.4178 20.6054 18.1468V21.0455H22.4062V17.9375C22.4062 16.2725 21.5182 15.4985 20.3332 15.4985C19.3777 15.4985 18.9494 16.0235 18.7094 16.3932V16.412H18.6974L18.7094 16.3932V15.6267H16.9094C16.9319 16.1353 16.9094 21.0455 16.9094 21.0455H18.7094Z" fill="white" class="group-hover/social:fill-yellow transition-colors"/>
                  </svg>
                </a>
              <?php endif; ?>

              <?php if (!empty($socials['soundcloud'])): ?>
                <a href="<?php echo esc_url($socials['soundcloud']); ?>" target="_blank" rel="noopener" class="social-link group/social">
                  <svg class="@sm:w-9 @sm:h-9 @md/lg:w-9 @md/lg:h-9 " viewBox="0 0 36 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.25" cx="17.6997" cy="17.4785" r="17.4785" transform="rotate(90 17.6997 17.4785)" fill="white" class="group-hover/social:opacity-40 transition-opacity"/>
                    <path d="M24.6479 16.4976C24.336 16.4976 24.04 16.5608 23.7715 16.6713C23.5978 14.6146 21.8806 13 19.7844 13C19.137 13 18.533 13.1619 17.9922 13.4342V21.1085H24.644C24.644 21.1085 24.6479 21.1085 24.6519 21.1085C25.9269 21.1085 26.9573 20.0742 26.9573 18.803C26.9573 17.5319 25.923 16.4976 24.6519 16.4976H24.6479Z" fill="white" class="group-hover/social:fill-yellow transition-colors"/>
                    <mask id="mask0_soundcloud" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="8" y="13" width="10" height="9">
                      <path d="M17.7157 13.5801C17.1394 13.9314 16.6578 14.417 16.3183 15.0052C15.7893 14.5709 15.1142 14.3104 14.38 14.3104C12.9272 14.3104 11.7114 15.3249 11.3995 16.6829C11.1192 16.5605 10.8113 16.4934 10.4876 16.4934C9.20856 16.4974 8.17822 17.5277 8.17822 18.8028C8.17822 20.0779 9.19277 21.0885 10.4521 21.1082H10.4797C10.4797 21.1082 10.4836 21.1082 10.4876 21.1082C10.4915 21.1082 10.4915 21.1082 10.4955 21.1082H17.7197V13.5801H17.7157Z" fill="white"/>
                    </mask>
                    <g mask="url(#mask0_soundcloud)">
                      <path d="M17.0684 12.9883V22.1152" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M15.8408 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M14.6089 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M13.3813 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M12.1494 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M10.9219 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M9.68994 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                      <path d="M8.4624 13V22.1269" stroke="white" stroke-width="0.592146" stroke-miterlimit="10" class="group-hover/social:stroke-yellow transition-colors"/>
                    </g>
                  </svg>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Section -->
      <!-- Copyright & Policy Links -->
      <div class="footer-bottom flex @sm:mt-8 @md/lg:mt-7 w-full items-center @sm:gap-3 @md/lg:gap-10 justify-between flex-wrap max-lg:max-w-full">
        
        <div class="paragraph-sm paragraph-primary">
          © MOC/CIEP <?php echo date('Y'); ?>. All Right Reserved.
        </div>
        
        <div class="policy-links flex items-center @md/lg:gap-5">
          <?php
          wp_nav_menu([
            'theme_location' => 'policy-menu',
            'menu_class' => 'policy-menu flex items-center  @sm:gap-5 @md/lg:gap-5 list-none m-0 p-0',
            'container' => false,
            'fallback_cb' => false,
            'walker' => new class extends Walker_Nav_Menu {
              function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                $output .= '<li class="menu-item">';
                $output .= '<a href="' . esc_url($item->url) . '" class="paragraph-sm paragraph-primary hover:text-yellow transition-colors">';
                $output .= esc_html($item->title);
                $output .= '</a>';
                $output .= '</li>';
              }
            }
          ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Footer submenu toggle functionality - Click-only for both Mobile & Desktop
  const footerSubmenuToggles = document.querySelectorAll('.footer-menu .footer-submenu-parent');
  
  // Handle toggle button clicks only - no hover, no parent link prevention
  footerSubmenuToggles.forEach(toggle => {
    // Support both click and touch events
    ['click', 'touchend'].forEach(eventType => {
      toggle.addEventListener(eventType, function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Prevent double firing on devices that support both touch and click
        if (eventType === 'click' && e.pointerType === 'touch') {
          return;
        }
        
        toggleFooterSubmenu(this);
      });
    });
  });

  // Prevent submenu from closing when clicking on submenu item links
  const footerSubmenuItemLinks = document.querySelectorAll('.footer-submenu-item a');
  footerSubmenuItemLinks.forEach(link => {
    ['click', 'touchend'].forEach(eventType => {
      link.addEventListener(eventType, function(e) {
        e.stopPropagation();
      });
    });
  });
  
  function toggleFooterSubmenu(toggle) {
    const parentLi = toggle.closest('.footer-submenu-parent');
    const submenu = parentLi?.querySelector('.sub-menu');
    const icon = toggle.querySelector('svg');
    
    if (submenu && icon) {
      const isHidden = submenu.classList.contains('hidden');
      
      // Close other open submenus in the same section
      const sameSection = toggle.closest('.footer-menu');
      if (sameSection) {
        const otherOpenSubmenus = sameSection.querySelectorAll('.sub-menu:not(.hidden)');
        otherOpenSubmenus.forEach(otherSubmenu => {
          if (otherSubmenu !== submenu) {
            otherSubmenu.classList.add('hidden');
            otherSubmenu.classList.remove('flex');
            const otherIcon = otherSubmenu.closest('.footer-submenu-parent')?.querySelector('.footer-submenu-toggle svg');
            if (otherIcon) {
              otherIcon.style.transform = 'rotate(0deg)';
            }
          }
        });
      }
      
      if (isHidden) {
        // Show submenu
        submenu.classList.remove('hidden');
        submenu.classList.add('flex');
        icon.style.transform = 'rotate(180deg)';
        
        // Add active state
        parentLi.classList.add('footer-submenu-open');
      } else {
        // Hide submenu
        submenu.classList.add('hidden');
        submenu.classList.remove('flex');
        icon.style.transform = 'rotate(0deg)';
        
        // Remove active state
        parentLi.classList.remove('footer-submenu-open');
      }
    }
  }
  
  // Close submenus when clicking/touching outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.footer-menu .footer-submenu-parent a')) {
      closeAllFooterSubmenus();
    }
  });
  
  document.addEventListener('touchend', function(e) {
    if (!e.target.closest('.footer-menu .footer-submenu-parent a')) {
      closeAllFooterSubmenus();
    }
  });
  
  function closeAllFooterSubmenus() {
    const openSubmenus = document.querySelectorAll('.footer-menu .sub-menu:not(.hidden)');
    openSubmenus.forEach(submenu => {
      submenu.classList.add('hidden');
      submenu.classList.remove('flex');
      const parentLi = submenu.closest('.footer-submenu-parent');
      const toggle = parentLi?.querySelector('.footer-submenu-toggle svg');
      if (toggle) {
        toggle.style.transform = 'rotate(0deg)';
      }
      if (parentLi) {
        parentLi.classList.remove('footer-submenu-open');
      }
    });
  }
});
</script>
