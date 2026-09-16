<?php

/**
 * Template Name: Contact
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header'); ?>
<main id="template-contact">
  <?php $fields = get_fields(); ?>
  <article class="article">
    <?php if (!empty($fields['hero'])) { 
      get_template_part('src/components/hero/markup', 'hero', $fields['hero']); 
    } ?>
    
    <?php if (!empty($fields['contact_title']) || !empty($fields['contact_information']) || !empty($fields['map'])) { 
      get_template_part('src/components/contact-section/markup', null, [
        'title' => $fields['contact_title'],
        'information' => $fields['contact_information'],
        'map' => $fields['map'],
      ]); 
    } ?>
    
    <?php if (!empty($fields['contact_groups'])) { 
      get_template_part('src/components/contact-others/markup', null, [
        'contact_groups' => $fields['contact_groups'],
      ]); 
    } ?>
    
    <?php if (!empty($fields['jobs_title'])) { 
      get_template_part('src/components/jobs-section/markup', null, [
        'title' => $fields['jobs_title'],
      ]); 
    } ?>
  </article>
</main>
<?php get_template_part('src/components/footer/markup', 'footer'); ?>
<?php get_footer(); ?>
