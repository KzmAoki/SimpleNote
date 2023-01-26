<?php get_header(); ?>

  <main class="main post-content-wrapper">
    <article>
      <?php if(have_posts()): while(have_posts()):the_post(); ?>
        <!-- タイトル -->
        <h1 class="article-title">
          <?php echo get_the_title(); ?>
        </h1>
        <!-- 本文 -->
        <div class="post-content">
          <?php echo the_content(); ?>
        </div>
      <?php endwhile; endif; ?>
    </article>
  </main>

<?php get_footer(); ?>