<?php global $adwp; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <?php
    $meta_title = get_field('meta_title');
    $meta_description = get_field('meta_description');

    $og = get_field('og_image');

    $title = $meta_title ?: $default_title;
    $description = $meta_description ?: $default_desc;
  
  // Add Open Graph image meta tag
  if (has_post_thumbnail()) {
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
    $og_image = $og_image ?: $thumbnail_url;
  }
  
  ?>

  <title><?php echo $title; ?> | MOC</title>
  <meta name="description" content="<?php echo esc_attr($description); ?>" />

      <!-- Open Graph -->

  <meta property="og:title" content="<?= $title ?> | MOC" />
  <meta property="og:description" content="<?= $description ?>" />
  <?php if($og_image): ?> <meta property="og:image" content="<?php echo esc_url($og_image); ?>" /><?php endif; ?>
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $title ?> | MOC">
  <meta name="twitter:description" content="<?= $description ?>">
  <?php if($og_image): ?><meta name="twitter:image" content="<?php echo esc_url($og_image); ?>"><?php endif; ?>

  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/public/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri() ?>/public/favicon.svg" />
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri() ?>/public/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri() ?>/public/apple-touch-icon.png" />
  <link rel="manifest" href="<?php echo get_template_directory_uri() ?>/public/site.webmanifest" />

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TM3RKTLK');</script>
<!-- End Google Tag Manager -->

  <?php $adwp->head(); ?>
</head>

<body <?php body_class("no-transition"); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TM3RKTLK"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->