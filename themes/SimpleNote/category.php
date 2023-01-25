<?php get_header(); ?>
<div class="content-section">
  <main class="main"> 
    <article class="article__wrapper container">
      <div aria-label="breadcrumb">
        <ol class="breadcrumb"><?php echo mytheme_breadcrumb(); ?></ol>
      </div>
      <p class="cat-name">
        <?php $cat_info = get_category($cat); ?>
        <?php echo wp_specialchars($cat_info->name); ?>
      </p>
      <ul class="row">
        <?php
        if( wp_is_mobile() ){
          $num = 4; //スマホの表示数(全件は-1)
        } else {
          $num = 6; //PCの表示数(全件は-1)
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
        <li class="card col-lg-4 col-md-6">
          <!-- 記事リンク -->
          <div data-href="<?php the_permalink(); ?>">
            <div class="card__image">
              <!-- アイキャッチ -->
              <img src="<?php echo the_post_thumbnail_url( 'large' ); ?>" alt="">
              <div class="card__overlay card__overlay--blue">
                <div class="card__overlay-content">
                  <!-- 更新日 -->
                  <div class="card__title">
                    <!-- タイトル 40字制限 -->
                    <h2 class="">
                      <?php echo wp_trim_words( get_the_title(), 40, '…' ); ?>
                    </h2>
                  </div>
                  <ul class="card__meta">
                    <!-- 更新日 -->
                    <li>
                      <i class="fas fa-sync-alt"></i>
                      <time datetime="<?php the_modified_time('Y-m-d'); ?>">
                        <?php the_modified_time('Y-m-d'); ?>
                      </time>
                    </li>
                    <!-- 投稿日 -->
                    <li>
                      <i class="far fa-calendar"></i>
                      <time datetime="<?php the_time('Y-m-d'); ?>">
                        <?php the_time('Y-m-d'); ?>
                      </time>
                    </li>
                  </ul>
                  <!-- カテゴリ -->
                  <?php
                    $categories = get_the_category();
                    if( $categories ){
                      echo '<ul class="card__meta-cat">';
                      foreach( $categories as $category ){
                        echo '<li><i class="fas fa-tag"></i>';
                        echo '<a class="card__meta-cat-link" href="'. esc_url(get_category_link( $category->term_id )). '">'. $category->name. '</a>';
                        echo '</li>';
                      }
                      echo '</ul>';
                    }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </li>
        <?php endwhile; else: ?>
        <p>まだ記事がありません</p>
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

      <!-- 人気記事 -->
      <p class="popular_posts">人気記事</p>
      <ul class="row">
        <?php
          $args = array(
            'meta_key'=>'cf_popular_posts',
            'orderby'=>'meta_value_num',
            'order' => 'DESC',
            'showposts' => 3,
          );
          $wp_query = new WP_Query( $args );
          if ($wp_query->have_posts()) : while ($wp_query->have_posts()) :
          $wp_query->the_post();
        ?>
          <li class="card col-lg-4">
            <!-- 記事リンク -->
            <div data-href="<?php the_permalink(); ?>">
              <div class="card__image">
                <!-- アイキャッチ -->
                <img src="<?php echo the_post_thumbnail_url( 'large' ); ?>" alt="">
                <div class="card__overlay card__overlay--blue">
                  <div class="card__overlay-content">
                    <!-- 更新日 -->
                    <div class="card__title">
                      <!-- タイトル 40字制限 -->
                      <h2 class="">
                        <?php echo wp_trim_words( get_the_title(), 40, '…' ); ?>
                      </h2>
                    </div>
                    <ul class="card__meta">
                      <li>
                        <i class="fas fa-sync-alt"></i>
                        <time datetime="<?php the_modified_time('Y-m-d'); ?>">
                          <?php the_modified_time('Y-m-d'); ?>
                        </time>
                      </li>
                      <!-- 投稿日 -->
                      <li>
                        <i class="far fa-calendar"></i>
                        <time datetime="<?php the_time('Y-m-d'); ?>">
                          <?php the_time('Y-m-d'); ?>
                        </time>
                      </li>
                    </ul>
                    <!-- カテゴリ -->
                    <?php
                      $categories = get_the_category();
                      if( $categories ){
                        echo '<ul class="card__meta-cat">';
                        foreach( $categories as $category ){
                          echo '<li><i class="fas fa-tag"></i>';
                          echo '<a class="card__meta-cat-link" href="'. esc_url(get_category_link( $category->term_id )). '">'. $category->name. '</a>';
                          echo '</li>';
                        }
                        echo '</ul>';
                      }
                    ?>
                  </div>
                </div>
              </img>
            </a>
          </li>
        <?php endwhile; endif; wp_reset_postdata();?>
      </ul>
    </article>
  </main>
  <?php get_footer(); ?>
</div>