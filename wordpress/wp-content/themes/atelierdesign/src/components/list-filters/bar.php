<?php
/**
 * List Filters - Barre de filtres
 *
 * Fragment re-rendu tel quel par l'AJAX (voir filter-functions.php).
 *
 * $args :
 *   - context : ['type','id','per_page']
 *   - active  : [param => valeur]
 *   - title   : (optionnel) titre affiche
 */

$data    = is_array($args) ? $args : [];
$context = ad_list_context($data['context'] ?? []);
$active  = is_array($data['active'] ?? null) ? $data['active'] : [];
$title   = $data['title'] ?? ad_list_default_title($context);

$groups      = ad_list_filter_groups($context, $active);
$has_active  = !empty($active);
?>

<div class="list-filters__bar">

  <!-- Titre + dropdowns -->
  <div class="header-top flex flex-col items-start @sm:gap-6 @md/lg:gap-8 @sm:mb-5 @md/lg:mb-12">

    <h1 class="archive-title text-yellow text-display autoscale">
      <?php echo esc_html($title); ?>
    </h1>

    <?php if (!empty($groups)): ?>
      <div class="filters-wrapper flex items-center @sm:gap-2 @md/lg:gap-4 mm-sm:flex-wrap">

        <span class="filter-label menu autoscale !leading-tight mm-sm:w-full md:text-right">
          Filtrer par
        </span>

        <div class="filter-dropdowns flex flex-wrap @sm:gap-1 @md/lg:gap-4">

          <?php foreach ($groups as $group): ?>
            <?php $is_group_active = !empty($active[$group['param']]); ?>

            <div class="filter-dropdown-wrapper relative group/wrapper <?php echo $is_group_active ? 'active' : ''; ?>">

              <button type="button"
                      class="filter-dropdown button-outline autoscale button-primary @sm:gap-2 @md/lg:gap-2 @sm:px-2.5 @md/lg:px-5 @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow hover:border-yellow hover:text-dark-green aria-expanded:bg-yellow aria-expanded:border-yellow"
                      data-filter="<?php echo esc_attr($group['param']); ?>"
                      aria-expanded="false"
                      aria-haspopup="true">
                <span class="button-title font-semibold"><?php echo esc_html($group['label']); ?></span>
                <svg class="mm-sm:group-is-[.active]/wrapper:hidden @sm:w-2 @sm:h-[14px] @md/lg:w-2 @md/lg:h-[14px] rotate-90 transition-transform" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7.70859 7.70469C8.09922 7.31406 8.09922 6.67969 7.70859 6.28906L1.70859 0.289062C1.31797 -0.101562 0.683594 -0.101562 0.292969 0.289063C-0.0976562 0.679688 -0.0976562 1.31406 0.292969 1.70469L5.58672 6.99844L0.296094 12.2922C-0.094531 12.6828 -0.094531 13.3172 0.296094 13.7078C0.686719 14.0984 1.32109 14.0984 1.71172 13.7078L7.71172 7.70781L7.70859 7.70469Z" fill="#012E31"/>
                </svg>
                <span class="hidden mm-sm:group-is-[.active]/wrapper:block @sm:w-2 @sm:h-2 group-is-[.active]/wrapper:<?php echo esc_attr($group['color']); ?> rounded-full"></span>
              </button>

              <div class="filter-dropdown-menu @sm:space-y-4 @md/lg:space-y-4 absolute top-full left-0 @sm:mt-2.5 @md/lg:mt-2.5 @sm:p-5 @md/lg:p-5 bg-yellow @sm:rounded-xl @md/lg:rounded-xl z-[50] @sm:min-w-[300px] @md/lg:min-w-[300px] max-h-[300px] overflow-y-auto hidden">
                <?php foreach ($group['options'] as $option): ?>
                  <?php $is_active = isset($active[$group['param']]) && (string) $active[$group['param']] === (string) $option['value']; ?>
                  <button type="button"
                          class="filter-option menu autoscale w-full text-left text-green-semi-light hover:text-dark-green [.active&]:text-dark-green <?php echo $is_active ? 'active' : ''; ?>"
                          data-param="<?php echo esc_attr($group['param']); ?>"
                          data-value="<?php echo esc_attr($option['value']); ?>"
                          aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>">
                    <span><?php echo esc_html($option['label']); ?></span>
                  </button>
                <?php endforeach; ?>
              </div>

            </div>
          <?php endforeach; ?>

        </div>
      </div>
    <?php endif; ?>

  </div>

  <!-- Filtres actifs + reset -->
  <div class="active-filters flex items-center @sm:gap-6 @md/lg:gap-[42px] @sm:flex-wrap @md/lg:flex-nowrap" <?php echo $has_active ? '' : 'style="display: none;"'; ?>>

    <div class="filter-tags flex items-start flex-wrap @sm:gap-2 @md/lg:gap-2 badge-wrapper">
      <?php foreach ($active as $param => $value): ?>
        <?php
        $label = ad_list_filter_option_label($groups, $param, $value);
        $color = ad_list_filter_color($groups, $param);
        ?>
        <div class="filter-tag badge-surface border-0 autoscale flex items-center @sm:gap-2.5 @md/lg:gap-2.5 <?php echo esc_attr($color); ?>"
             data-filter="<?php echo esc_attr($param); ?>">
          <span><?php echo esc_html($label); ?></span>
          <button type="button" class="remove-tag @sm:w-[9px] @md/lg:w-[9px] @sm:h-[8px] @md/lg:h-[8px] relative"
                  aria-label="Retirer le filtre <?php echo esc_attr($label); ?>"
                  data-param="<?php echo esc_attr($param); ?>">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 @sm:w-[11px] @sm:h-[1px] @md/lg:w-[11px] @md/lg:h-[1px] bg-dark-green rotate-45"></span>
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 @sm:w-[11px] @sm:h-[1px] @md/lg:w-[11px] @md/lg:h-[1px] bg-dark-green -rotate-45"></span>
          </button>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($has_active): ?>
      <button type="button" class="reset-filters button-underline button-primary border-b p-0 border-dark-green hover:border-yellow hover:text-yellow">
        <span class="button-title font-semibold autoscale">
          Reset all
        </span>
      </button>
    <?php endif; ?>

  </div>

</div>
