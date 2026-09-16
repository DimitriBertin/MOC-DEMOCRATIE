<?php

/**
 * Disable all plugins updates
 */

function gg_disable_plugin_updates($value)
{
  unset($value->response);
  return $value;
}
add_filter('site_transient_update_plugins', 'gg_disable_plugin_updates');
