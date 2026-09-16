<?php
global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$color = 'accordion-' . $args['color'];
$style = 'accordion-' . $args['style'];

?>
<?php if (isset($args['title']) && !empty($args['title'])): ?>
  <div class="accordion-wrapper <?= $isFullWidth ? '' : 'px-content' ?> autoscale-children aos animate-fadeinup">
    <div class="group/accordion <?= $color; ?> <?= $style; ?>">
      <div class="accordion-title" onclick="this.parentElement.classList.toggle('is-active')">
        <span class="accordion-title-text">
          <?= $args['title'] ?? ''; ?>
        </span>
        <svg class="accordion-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960">
          <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z" />
        </svg>
      </div>
      <div class="accordion-content spacing-reset">
        <div class="accordion-content-wrapper inline-flexible">
          <?php $adwp->render_flexible_layout($args['content'], [
            'parentBlock' => '_accordion',
            'isNested' => true,
          ]); ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>