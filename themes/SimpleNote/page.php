<?php get_header(); ?>
<div class="content-section">
  <main class="main">
    <article class="article__wrapper container">
      <div class="post-content col-sm-8">
        <div aria-label="breadcrumb">
          <ol class="breadcrumb"><?php echo mytheme_breadcrumb(); ?></ol>
        </div>
        <?php if(have_posts()): while(have_posts()):the_post(); ?>
          <!-- タイトル -->
          <h1 class="mt-3">
            <?php echo get_the_title(); ?>
          </h1>
          <!-- アイキャッチ -->
          <div class="single_img mt-3">
            <?php the_post_thumbnail('post-thumbnail', array('class' => 'thumbnail-img', 'alt' => the_title_attribute('echo=0'))); ?>
          </div>
          <!-- 本文 -->
          <div class="mt40-pc mt20-sp">
            <?php echo the_content(); ?>
          </div>
        <?php endwhile; endif; ?>
      </div>
    </article>
  </main>
  <?php get_footer(); ?>
</div>