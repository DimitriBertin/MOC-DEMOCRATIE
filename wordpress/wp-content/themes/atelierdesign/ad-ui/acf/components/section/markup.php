<?php

global $adwp;

// Split $args['color'] based on the '/' and get the theme-* and the bg-layout-* classes from it
$colorParts = explode('/', $args['color']);
$themeClass = "theme-" . mb_strtolower($colorParts[0], 'UTF-8');
$layoutClass = "bg-layout-" . mb_strtolower($colorParts[1], 'UTF-8');

?>
<section class="py-section <?= $themeClass; ?> <?= $layoutClass; ?>">
  <div class="container inline-flexible">
    <?php $adwp->render_flexible_layout($args['section'], [
      'parentColor' => $args['color'],
    ]); ?>
  </div>
</section>