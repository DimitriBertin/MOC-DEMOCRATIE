<?php

/**
 * Template Name: Home
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header'); ?>
<main id="template-home">
  <?php 
  $fields = get_fields(); 
  $featured_themes = $fields['thematiques_featured'];
  ?>
  <article class="article">


    <section class="py-section @sm:pt-[92px] @md/lg:pt-[182px] @xl:pt-[182px] grid md:grid-cols-2 @sm:gap-[18px] @md/lg:gap-[18px] theme-light-green-70 bg-layout-main px-container">
      <!-- FEATURED NUMERO  -->
      <div class="col-span-1 flex flex-col ">
        <div class="flex-grid flex-grid-cols-1 md:h-full home-featured-numero">
          <?php
            $latest = get_posts([
              'post_type'      => 'numero',
              'post_status'    => 'publish',
              'posts_per_page' => 1,
              'orderby'        => 'date',
              'order'          => 'DESC',
            ]);
            
            $latest_numero = $latest[0] ?? null;
            get_template_part('src/components/_numero-card/markup', null, [ 'post_id'  => $latest_numero->ID ]);
          ?>
        </div>
      </div>

      <!-- FEATURED THEMATIQUE -->
      <div class="col-span-1 overflow-hidden">
        <!-- THEMATIQUE CARDS -->
        <div class="grid md:grid-cols-2 @sm:gap-[18px] @md/lg:gap-[18px]">
          <?php foreach($featured_themes as $theme):
            $pid = $theme->ID;
            ?>
            <a href="<?= get_permalink($pid) ?>" class="theme-card group col-span-1 @sm:h-[288px] @md/lg:h-[288px] bg-dark-green overflow-hidden @sm:rounded-xl @md/lg:rounded-xl relative">
              <div class="theme-card-content flex flex-col @sm:gap-[12px] @md/lg:gap-[12px] @sm:px-[20px] @sm:py-[20px] @md/lg:px-[20px] @md/lg:py-[20px] relative z-[1] justify-end h-full autoscale-children">
                <div class="badge-wrapper flex justify-start">
                  <div class="badge-surface">
                    <?php echo esc_html(get_the_title($pid)); ?>
                  </div>
                </div>
                <h3 class="post-title autoscale text-white font-bold @sm:text-[18px] @md/lg:text-[18px] @sm:leading-[20px] @md/lg:leading-[20px] @sm:pr-4 @lg/md:pr-4 group-hover:opacity-80 transition-opacity duration-200">
                  <?= get_field('title', $pid) ?>
                </h3>
              </div>
              <div class="absolute z-0 inset-0">
                <?php
                  echo get_the_post_thumbnail($pid, 'medium_large', [
                    'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
                    'loading' => 'lazy',
                    'alt' => esc_attr(get_the_title($pid)),
                  ]);
                ?>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="py-section theme-light-green-70 bg-layout-main px-container">
      <?php 
        $review = $fields['press-review'];
        $thematic = $review['thematiques_other'];
        $text = $review['text'] ?? 'Pour recevoir democratie';
        $link = $review['link'];
      ?>
      <div class="flex-grid md:flex-grid-cols-3 @sm:flex-grid-gap-[12px] @md/lg:flex-grid-gap-[12px] justify-end">
        <?php foreach($thematic as $article):
          $pid = $article->ID;
          $thematique = get_field('thematiques', $pid);
        ?>
        <a  href="<?= get_permalink($pid) ?>" class="bg-white flex-grid-col-span-1 overflow-hidden @sm:rounded-xl @md/lg:rounded-xl relative flex-col justify-between @sm:px-[26px] @md/lg:px-[26px] @sm:py-[34px] @md/lg:py-[34px] flex autoscale-children @sm:gap-[12px] @md/lg:gap-[12px]">
          <svg class="@@:w-[51px] @@:h-[52px]" viewBox="0 0 51 52" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M48.3873 51.2011H5.44775C5.234 51.2011 5.0607 51.0248 5.0607 50.8075C5.0607 50.59 5.234 50.4136 5.44775 50.4136H48.3873C49.0092 50.4136 49.5162 49.8988 49.5162 49.2659V0.787568H10.8958V15.0865C10.8958 15.304 10.7223 15.4803 10.5088 15.4803C10.295 15.4802 10.1217 15.304 10.1217 15.0865V0.393651C10.1217 0.176339 10.295 0 10.5088 0H49.9034C50.1169 0 50.2904 0.176339 50.2904 0.393839V49.2685C50.2904 50.335 49.4365 51.2039 48.3875 51.2039L48.3873 51.2011Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.774345 15.4779V45.6568C0.774345 48.2795 2.87085 50.4127 5.44825 50.4127C8.0257 50.4127 10.1222 48.2794 10.1222 45.6568V15.4775H0.774155L0.774345 15.4779ZM5.44825 51.2006C2.44464 51.2006 0 48.7135 0 45.6568V15.084C0 14.8667 0.173299 14.6902 0.387049 14.6902H10.5093C10.7229 14.6902 10.8962 14.8665 10.8962 15.084V45.6568C10.8962 48.7133 8.45177 51.2006 5.44791 51.2006H5.44825Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3504 18.4983H28.3038V6.33519H16.3504V18.4983ZM28.6909 19.286H15.9633C15.7497 19.286 15.5763 19.1098 15.5763 18.8921V5.9412C15.5763 5.7237 15.7496 5.54736 15.9633 5.54736H28.6909C28.9047 5.54736 29.078 5.72389 29.078 5.9412V18.8921C29.078 19.1096 28.9045 19.286 28.6909 19.286Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 6.33522H33.4177C33.2041 6.33522 33.0306 6.15889 33.0306 5.94139C33.0306 5.72389 33.2039 5.54755 33.4177 5.54755H46.1453C46.359 5.54755 46.5323 5.72407 46.5323 5.94139C46.5323 6.15889 46.3588 6.33522 46.1453 6.33522Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 12.8104H33.4177C33.2041 12.8104 33.0306 12.6341 33.0306 12.4166C33.0306 12.1991 33.2039 12.023 33.4177 12.023L46.1453 12.0228C46.359 12.0228 46.5323 12.1991 46.5323 12.4166C46.5323 12.6341 46.3588 12.8104 46.1453 12.8104Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 19.2857H33.4177C33.2041 19.2857 33.0306 19.1093 33.0306 18.8918C33.0306 18.6741 33.2039 18.498 33.4177 18.498H46.1453C46.359 18.498 46.5323 18.6743 46.5323 18.8918C46.5323 19.1091 46.3588 19.2857 46.1453 19.2857Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 25.4994H15.9624C15.7488 25.4994 15.5753 25.323 15.5753 25.1055C15.5753 24.888 15.7486 24.7117 15.9624 24.7117L46.1453 24.7115C46.359 24.7115 46.5323 24.888 46.5323 25.1053C46.5323 25.3228 46.3589 25.4992 46.1453 25.4992V25.4994Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 32.2317H15.9624C15.7488 32.2317 15.5753 32.0554 15.5753 31.8379C15.5753 31.6204 15.7486 31.444 15.9624 31.444H46.1453C46.359 31.444 46.5323 31.6204 46.5323 31.8379C46.5323 32.0554 46.3589 32.2317 46.1453 32.2317Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 38.9641H15.9624C15.7488 38.9641 15.5753 38.7879 15.5753 38.5704C15.5753 38.3529 15.7486 38.1766 15.9624 38.1766H46.1453C46.359 38.1766 46.5323 38.3529 46.5323 38.5704C46.5323 38.7877 46.3589 38.9643 46.1453 38.9641Z" fill="black"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1453 45.6959H15.9624C15.7488 45.6959 15.5753 45.5196 15.5753 45.3021C15.5753 45.0846 15.7486 44.9083 15.9624 44.9083H46.1453C46.359 44.9083 46.5323 45.0848 46.5323 45.3021C46.5323 45.5196 46.3589 45.6959 46.1453 45.6959Z" fill="black"/>
          </svg>
          <div class="flex flex-col @sm:gap-[12px] @md/lg:gap-[12px]">
            <div class="flex flex-wrap @sm:gap-[2px] items-center">
              <?php if($thematique): 
                foreach($thematique as $theme):
              ?>
                <p class="@sm:text-[12px] @md/lg:text-[12px] uppercase text-[--color-dark-green] font-bold"><?= get_the_title($theme->ID); ?></p>
                <span class="@sm:text-[12px] @md/lg:text-[12px] uppercase text-[--color-dark-green]">|</span>
              <?php endforeach; 
              
              endif;?>
              <p class="@sm:text-[11px] @md/lg:text-[11px] @@:tracking-[1px] text-[#666666]"><?= get_the_date('', $pid)?></p>
            </div>
            <h2 class="heading-sm heading-primary"><?= get_the_title($pid) ?></h2>
          </div>
        </a>
        <?php endforeach; ?>
        <div class="bg-[--color-mid-green] flex-grid-col-span-1 theme-dark-green  overflow-hidden @sm:rounded-xl @md/lg:rounded-xl relative flex flex-col justify-between @sm:px-[26px] @md/lg:px-[26px] @sm:py-[34px] @md/lg:py-[34px] autoscale-children @sm:gap-[12px] @md/lg:gap-[12px] items-start">
          <h2 class="heading-xl heading-primary"><?= $text ?></h2>
          <?php if($link && isset($link['url'])): ?>
          <!-- BUTTON -->
            <a class="button-flat button-primary mm-sm:w-full mm-sm:justify-center" href="<?= $link['url'] ?>" target="<?= $link['target'] ?? '_self' ?>">
              <div class="button-title"><?= $link['title'] ?></div>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
    <!-- SLIDER THEMATIQUE -->
  </article>
</main>
<?php get_template_part('src/components/footer/markup', 'footer'); ?>
<?php get_footer(); ?>
