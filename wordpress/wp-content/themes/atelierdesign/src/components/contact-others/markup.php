<section class="other-contacts theme-dark-green bg-layout-main py-section">
  <div class="container">
    <h2 class="heading-2xl heading-primary @sm:mb-8 @md/lg:mb-12 autoscale">
      Autres contacts
    </h2>

    <div class="flex flex-col @sm:gap-10 @md/lg:gap-10">
      <!-- Contact Groups -->
      <?php if (!empty($args['contact_groups'])): ?>
        <?php foreach ($args['contact_groups'] as $group_index => $group): ?>
          <?php if (!empty($group['items'])): ?>
            <div class="contact-group-wrapper theme-light-green-70 bg-layout-main @sm:rounded-xl @md/lg:rounded-xl @sm:px-5 @sm:py-10 @md/lg:p-10 flex flex-col md:flex-row @sm:gap-8 @md/lg:gap-22">
              <!-- Title and Dropdown -->
              <div class="group-header md:basis-2/5">
                <?php if (!empty($group['title'])): ?>
                  <h3 class="heading-lg heading-primary @sm:mb-2.5 @md/lg:mb-2.5 autoscale">
                    <?php echo esc_html($group['title']); ?>
                  </h3>
                <?php endif; ?>

                <div class="group-dropdown relative">
                  <button
                    class="group-toggle-btn flex items-center justify-between autoscale w-full button-flat button-primary bg-yellow border-yellow hover:text-dark-green @sm:rounded-xl @md/lg:rounded-xl @sm:px-5 @md/lg:px-5"
                    data-dropdown="group-options-<?php echo $group_index; ?>"
                    aria-expanded="false">
                    <span class="selected-item button-title font-semibold">
                      <?php echo esc_html($group['items'][0]['name']); ?>
                    </span>
                    <svg class="dropdown-arrow transition-transform duration-200 ease-in-out @sm:w-4 @sm:h-4 @md/lg:w-4 @md/lg:h-4" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M11.7086 9.70488C12.0992 9.31426 12.0992 8.67988 11.7086 8.28926L5.70859 2.28926C5.31797 1.89863 4.68359 1.89863 4.29297 2.28926C3.90234 2.67988 3.90234 3.31426 4.29297 3.70488L9.58672 8.99863L4.29609 14.2924C3.90547 14.683 3.90547 15.3174 4.29609 15.708C4.68672 16.0986 5.32109 16.0986 5.71172 15.708L11.7117 9.70801L11.7086 9.70488Z" fill="#012E31"/>
                    </svg>
                  </button>
                  
                  <!-- Dropdown Options -->
                  <div id="group-options-<?php echo $group_index; ?>" class="dropdown-menu absolute top-full left-0 right-0 @sm:space-y-4 @md/lg:space-y-4 @sm:mt-2.5 @md/lg:mt-2.5 @sm:p-5 @md/lg:p-5 bg-yellow @sm:rounded-xl @md/lg:rounded-xl z-[50] @sm:min-w-[300px] @md/lg:min-w-[300px] max-h-[300px] overflow-y-auto hidden">
                    <?php foreach ($group['items'] as $item_index => $item): ?>
                      <button
                        class="group-item-option menu autoscale w-full text-left text-green-semi-light hover:text-dark-green"
                        data-group-index="<?php echo $group_index; ?>"
                        data-item-index="<?php echo $item_index; ?>">
                        <?php echo esc_html($item['name']); ?>
                      </button>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>

              <!-- Item Details -->
              <div class="group-details md:basis-3/5">
                <?php foreach ($group['items'] as $item_index => $item): ?>
                  <?php if (!empty($item['informations'])): ?>
                    <div class="item-details theme-white bg-layout-main @sm:rounded-xl @md/lg:rounded-xl @sm:p-5 @md/lg:p-6 transform transition-all duration-300 ease-out <?php echo $item_index === 0 ? '' : 'hidden'; ?>" data-group-details="<?php echo $group_index; ?>-<?php echo $item_index; ?>">
                      <div class="flex flex-col @sm:gap-5 @md/lg:gap-3">
                        <?php foreach ($item['informations'] as $info): ?>
                          <div class="info-row flex mm-sm:flex-col @sm:gap-1.5 md:flex-row @md/lg:gap-6 autoscale">
                            <div class="info-title paragraph-md paragraph-primary font-semibold mm-sm:w-max md:basis-2/5">
                              <?php echo esc_html($info['title']); ?>
                            </div>
                            <div class="info-value paragraph-md paragraph-primary font-light mm-sm:w-max md:basis-3/5">
                              <?php echo wp_kses_post($info['value']); ?>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
