<?php
$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}
?>
<?php if (!empty($args['content'])): ?>
  <div class="wysiwyg <?= $isFullWidth ? ((isset($args['isNested']) && $args['isNested'] == true) ? 'md:pr-[7%]' : '') : 'px-content' ?> autoscale-children">
    <?php
    $content = $args['content'] ?? '';

    // just <p> tags should be converted to <p class="paragraph-md">
    $content = preg_replace('/<p>/', '<p class="paragraph-md">', $content);

    // <h1-6> <p> <div> <ul> <ol> append "aos animate-fadeinup" 
    $content = preg_replace('/<(h[1-6]|p|li) class=\"(.*?)\"/', '<$1 class="$2 aos animate-fadeinup"', $content);

    echo $content;
    ?>
  </div>
<?php endif; ?>