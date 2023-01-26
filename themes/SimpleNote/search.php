<?php get_header(); ?>

  <main class="main"> 
    <?php if (isset($_GET['s']) && empty($_GET['s'])) { ?>
      <p class="not-keyword">検索条件が入力されていません</p>
    <?php } else { ?>
      <ul class="articles-wrapper">
        <h1 class="search-result">
          <?php the_search_query(); ?>の検索結果 ： <?php echo $wp_query->found_posts; ?>件
        </h1>
        <?php 
        if(have_posts()):
        while(have_posts()): the_post(); 
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
        <p>検索条件に一致する記事がありませんでした。</p>
        <?php endif; ?>
      </ul>
      <!-- ペジネーション -->
      <div class="pagination">
        <?php
          $big = 999999999; // need an unlikely integer
          echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '',
            'type' => 'list',
          ) );
        ?>
      </div>
    <?php } ?>
  </main>

  <?php get_footer(); ?>