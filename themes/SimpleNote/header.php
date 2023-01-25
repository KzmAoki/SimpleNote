<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body>
  <header>
    <h1  class="site-title">
      <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
    </h1>
    <!-- 検索バー -->
    <div class="search_form">
      <?php get_search_form(); ?>
    </div>
  </header>
  <!-- <div class="">
    <?php
      // wp_nav_menu( array(
      //   'theme_location' => 'header-navigation'
      // ) );
    ?>
  </div> -->
