<?php 
$section_color = $args['section_color'];

global $adwp;
// form_id
$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}
?>
<div class="form <?= $isFullWidth ? ((isset($args['isNested']) && $args['isNested'] == true) ? 'md:pr-[7%]' : '') : 'px-content' ?> autoscale-children">

  <?php
    echo do_shortcode("[formidable id='".$args['form_id']."']");
  ?>
</div>