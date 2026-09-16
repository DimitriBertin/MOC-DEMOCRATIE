<?php
global $adwp, $adui_tokens;

$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$defaultSizingMode = $responsiveSizingModes[0] ?? null;
$gapTokens = $defaultSizingMode ? ($adui_tokens['responsiveSizing'][$defaultSizingMode]['gap'] ?? []) : [];
$gapNames = array_keys($gapTokens);
$gapNamesArray = array_values($gapNames);

if (!function_exists('adui_group_get_gap_name')) {
  function adui_group_get_gap_name($gapValue, $gapNamesArray)
  {
    if (is_string($gapValue) && in_array($gapValue, $gapNamesArray, true)) {
      return $gapValue;
    }

    if (is_numeric($gapValue)) {
      $index = (int)$gapValue;
      if (isset($gapNamesArray[$index])) {
        return $gapNamesArray[$index];
      }
    }

    return $gapNamesArray[0] ?? 'none';
  }
}

$layoutSettings = $args['layout_settings'] ?? [];
$direction = $layoutSettings['direction'] ?? 'row';
$wrap = $layoutSettings['wrap'] ?? 'wrap';
$justifySetting = $layoutSettings['justify'] ?? 'start';
$alignSetting = $layoutSettings['alignItems'] ?? 'stretch';
$gapXValue = $layoutSettings['gapX'] ?? 0;
$gapYValue = $layoutSettings['gapY'] ?? 0;
$additionalClasses = trim($layoutSettings['additionalClasses'] ?? '');
$isFullWidth = !empty($layoutSettings['isFullWidth']);

$directionClass = match ($direction) {
  'row-reverse' => 'flex-row-reverse',
  'column' => 'flex-col',
  'column-reverse' => 'flex-col-reverse',
  'row-desktop' => 'flex-col md:flex-row',
  default => 'flex-row',
};

$wrapClass = match ($wrap) {
  'nowrap' => 'flex-nowrap',
  'wrap-reverse' => 'flex-wrap-reverse',
  'nowrap-desktop' => 'flex-wrap md:flex-nowrap',
  default => 'flex-wrap',
};

$justifyClass = match ($justifySetting) {
  'center' => 'justify-center',
  'end' => 'justify-end',
  'between' => 'justify-between',
  default => 'justify-start',
};

$alignClass = match ($alignSetting) {
  'start' => 'items-start',
  'center' => 'items-center',
  'end' => 'items-end',
  'baseline' => 'items-baseline',
  default => 'items-stretch',
};

$gapXName = adui_group_get_gap_name($gapXValue, $gapNamesArray);
$gapYName = adui_group_get_gap_name($gapYValue, $gapNamesArray);

$gapClasses = [];
if (!empty($layoutSettings['gapClass'])) {
  $gapClasses[] = trim($layoutSettings['gapClass']);
} else {
  if ($gapXName) {
    $gapClasses[] = "gap-x-{$gapXName}";
  }
  if ($gapYName) {
    $gapClasses[] = "gap-y-{$gapYName}";
  }
}

$wrapperClasses = array_filter([
  'group-wrapper',
  'flex',
  'aos-disable-children aos animate-fadeinup',
  $directionClass,
  $wrapClass,
  $justifyClass,
  $alignClass,
  implode(' ', $gapClasses),
  $isFullWidth ? '' : 'mx-content',
  $additionalClasses,
]);

?>
<?php if (isset($args['_group']) && is_array($args['_group']) && count($args['_group']) > 0): ?>
  <div class="<?= esc_attr(trim(implode(' ', $wrapperClasses))); ?> aos animate-fadeinup aos-disable-children">
    <?php $adwp->render_flexible_layout($args['_group'], [
      'isNested' => true,
      'layout_settings' => [
        'isFullWidth' => false,
        'alignment' => 'left',
      ],
    ]); ?>
  </div>
<?php endif; ?>