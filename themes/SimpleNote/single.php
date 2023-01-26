<?php get_header(); ?>
  <main class="main post-content-wrapper">
    <article>
      <?php if(have_posts()): while(have_posts()):the_post(); ?>
        <!-- 更新日 -->
        <div class="update">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4m-4 4a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
          </svg>
          <time datetime="<?php the_modified_time('Y-m-d'); ?>">
            <?php the_modified_time('Y-m-d'); ?>
          </time>
        </div>
        <!-- カテゴリ -->
        <ul class="card-meta-cat">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm13 3m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
          </svg>
          <?php
            $categories = get_the_category();
            if( $categories ){
              foreach( $categories as $category ){
                echo '<li><a class="card__meta-cat-link" href="'. esc_url(get_category_link( $category->term_id )). '">'. $category->name. '</a></li>';
              }
            }
          ?>
        </ul>
        <!-- タイトル -->
        <h1 class="article-title">
          <?php echo get_the_title(); ?>
        </h1>
        <!-- 本文 -->
        <div class="post-content">
          <?php echo the_content(); ?>
        </div>
        <!-- カテゴリ -->
        <ul class="card-meta-cat">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm13 3m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
          </svg>
          <?php
            $categories = get_the_category();
            if( $categories ){
              foreach( $categories as $category ){
                echo '<li><a class="card__meta-cat-link" href="'. esc_url(get_category_link( $category->term_id )). '">'. $category->name. '</a></li>';
              }
            }
          ?>
        </ul>
      <?php endwhile; endif; ?>
    </article>
  </main>
  <?php get_footer(); ?>
</div>