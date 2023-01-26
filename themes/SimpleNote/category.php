<?php get_header(); ?>

  <main class="main">
    <div class="cat-img">
      <?php
        if (function_exists('z_taxonomy_image')) 
        z_taxonomy_image($child->term_id); 
      ?>
    </div>
    <h1 class="cat-title">
      <?php $cat_info = get_category($cat); ?>
      <?php echo wp_specialchars($cat_info->name); ?>
    </h1>
    <p class="cat-desc">
      <?php if(!is_paged()):?>
      <?php if(category_description()):?>
      <?php echo category_description(); ?>
      <?php endif;?>
      <?php endif;?>
    </p>
    <ul class="articles-wrapper">
      <?php
      if( wp_is_mobile() ){
        $num = 4; //スマホの表示数(全件は-1)
      } else {
        $num = 10; //PCの表示数(全件は-1)
      }
      $paged = get_query_var('paged') ? get_query_var('paged') : 1;
      $args = [
        'post_type' => 'post', // 投稿タイプのスラッグ(通常投稿なので'post')
        'paged' => $paged, // ページネーションがある場合に必要
        'posts_per_page' => $num, // 表示件数
        'category_name' => $cat_info->slug,
      ];
      $the_query = new WP_Query( $args );
      if ( $the_query->have_posts() ) :
      while ( $the_query->have_posts() ) : $the_query->the_post();
      ?>

      <article>
        <li class="article-card">
          <div data-href="<?php the_permalink(); ?>">
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
            <!-- タイトル 40字制限 -->
            <h2 class="card-title">
              <?php echo wp_trim_words( get_the_title(), 40, '…' ); ?>
            </h2>
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
          </div>
        </li>
      </article>
      <?php endwhile; else: ?>
      <p class="not-found-article">まだ記事がありません</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
      <!-- 記事ループ処理終了 -->
    </ul>
    <!-- ペジネーション -->
    <div class="pagination">
      <?php
        global $the_query;
        $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
          'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format' => '',
          'current' => max( 1, get_query_var('paged') ),
          'total' => $the_query->max_num_pages,
          'type' => 'list',
        ) );
      ?>
    </div>
  </main>
<?php get_footer(); ?>