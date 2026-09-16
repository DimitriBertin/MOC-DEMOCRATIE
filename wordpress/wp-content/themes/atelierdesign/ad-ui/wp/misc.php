<?php

require_once __DIR__ . '/utils/toKebabCase.php';
require_once __DIR__ . '/utils/toTitleCase.php';

function youTubeEmbedFromUrl($url)
{
  // if url doesn't have http:// or https:// add it
  if (!preg_match('/^http(s)?:\/\//', $url)) {
    $url = 'https://' . $url;
  }

  if (strpos($url, 'youtu.be') !== false) {
    preg_match('/http(?:s)?:\/\/(?:www\.)?youtu\.be\/([a-zA-Z0-9_]+)\??/i', $url, $matches);
    $videoId = $matches[1];
    if (strpos($url, '?') !== false) {
      $arguments = substr($url, strpos($url, '?') + 1);
    }
  } elseif (strpos($url, 'youtube.com/watch') !== false) {
    preg_match('/http(?:s)?:\/\/(?:www\.)?youtube\.com\/watch\?v=(.*)\&?/i', $url, $matches);
    $videoId = $matches[1];
    if (strpos($url, '&') !== false) {
      $arguments = substr($url, strpos($url, '&') + 1);
    }
  } elseif (strpos($url, 'youtube.com/shorts')) {
    preg_match('/http(?:s)?:\/\/(?:www\.)?youtube\.com\/shorts\/([a-zA-Z0-9_]+)\??/i', $url, $matches);
    $videoId = $matches[1];
    if (strpos($url, '?') !== false) {
      $arguments = substr($url, strpos($url, '?') + 1);
    }
  } elseif (strpos($url, 'youtube.com/embed')) {
    preg_match('/http(?:s)?:\/\/(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_]+)\??/i', $url, $matches);
    $videoId = $matches[1];
    if (strpos($url, '?') !== false) {
      $arguments = substr($url, strpos($url, '?') + 1);
    }
  } else {
    $videoId = null;
  }

  return 'https://www.youtube.com/embed/' . $videoId . (isset($arguments) && strlen($arguments) > 0 ? '?' . $arguments : '');
}

function vimeoEmbedFromUrl($url, $autoplay = false, $loop = false)
{
  // if url doesn't have http:// or https:// add it
  if (!preg_match('/^http(s)?:\/\//', $url)) {
    $url = 'https://' . $url;
  }

  // if after vimeo.com/ there is a number string, it's a video id
  if (preg_match('/vimeo.com\/(\d+)\??/i', $url, $matches)) {
    $videoId = $matches[1];
    if (strpos($url, '?') !== false) {
      $arguments = substr($url, strpos($url, '?') + 1);
    }
  } elseif (preg_match('/vimeo.com(.*?)\/videos\/(\d+)\??/i', $url, $matches)) {
    $videoId = $matches[2];
    if (strpos($url, '?') !== false) {
      $arguments = substr($url, strpos($url, '?') + 1);
    }
  } else {
    $videoId = null;
  }

  if ($autoplay) {
    $arguments = preg_replace('/autoplay=(0|1)/', '', $arguments);
    if (strpos($url, '?') !== false) {
      $arguments .= '&autoplay=1';
    } else {
      $arguments = '?autoplay=1';
    }
  }

  if ($loop) {
    $arguments = preg_replace('/loop=(0|1)/', '', $arguments);
    if (strpos($url, '?') !== false) {
      $arguments .= '&loop=1';
    } else {
      $arguments = '?loop=1';
    }
  }

  return 'https://player.vimeo.com/video/' . $videoId . (isset($arguments) && strlen($arguments) > 0 ? '?' . $arguments : '');
}
