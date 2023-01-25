<?php get_header(); ?>
<div class="content-section">
  <main class="main"> 
    <article class="article__wrapper container">
      <div aria-label="breadcrumb">
        <ol class="breadcrumb"><?php echo mytheme_breadcrumb(); ?></ol>
      </div>
      <?php if (isset($_GET['s']) && empty($_GET['s'])) { ?>
        <p>検索条件が入力されていません</p>
      <?php } else { ?>
        <h1>
          <?php the_search_query(); ?>の検索結果 ： <?php echo $wp_query->found_posts; ?>件
        </h1>
        <ul class="row">
          <?php 
          if(have_posts()):
          while(have_posts()): the_post(); 
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
                </div>
              </div>
            </li>
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
              </div>
            </div>
          </li>
        <?php endwhile; endif; wp_reset_postdata();?>
      </ul>
      <!-- カテゴリ全件取得 -->
      <ul class="cat-list">
        <?php wp_list_categories('title_li='); ?>
      </ul>
    </article>
  </main>
  <?php get_footer(); ?>
</div>