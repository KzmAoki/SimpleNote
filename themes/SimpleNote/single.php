<?php get_header(); ?>
<div class="content-section">
  <main class="main">
    <article class="article__wrapper container">
      <div class="post-content col-sm-8">
        <div aria-label="breadcrumb">
          <ol class="breadcrumb"><?php echo mytheme_breadcrumb(); ?></ol>
        </div>
        <?php if(have_posts()): while(have_posts()):the_post(); ?>
          <!-- カテゴリ -->
          <?php
            $categories = get_the_category();
            if( $categories ){
              echo '<ul class="card__meta-cat">';
              foreach( $categories as $category ){
                echo '<li><i class="fas fa-tag"></i>';
                echo '<a href="'. esc_url(get_category_link( $category->term_id )). '">'. $category->name. '</a>';
                echo '</li>';
              }
              echo '</ul>';
            }
          ?>
          <!-- タイトル -->
          <h1 class="article-title mt-3">
            <?php echo get_the_title(); ?>
          </h1>
          <ul class="card__meta">
            <!-- 更新日 -->
            <li class="col">
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
          <!-- アイキャッチ -->
          <div class="single_img mt-3">
            <?php the_post_thumbnail('post-thumbnail', array('class' => 'thumbnail-img', 'alt' => the_title_attribute('echo=0'))); ?>
          </div>
          <!-- 本文 -->
          <div class="mt40-pc mt20-sp mb-4 post-content-body">
            <?php echo the_content(); ?>
          </div>
          <!-- カテゴリ -->
          <?php
            $categories = get_the_category();
            if( $categories ){
              echo '<ul class="card__meta-cat mb-4">';
              foreach( $categories as $category ){
                echo '<li><i class="fas fa-tag"></i>';
                echo '<a href="'. esc_url(get_category_link( $category->term_id )). '">'. $category->name. '</a>';
                echo '</li>';
              }
              echo '</ul>';
            }
          ?>
          <!-- SNS共有リンク -->
          <div>
            <?php
              // 現在のページURLを取得してURLエンコード
              $url_encode = urlencode( get_permalink() );
              // 現在のページのタイトルを取得してURLエンコード
              $title_encode = urlencode( get_the_title() );
            ?>
            <ul class="sns-list">
              <!-- Twitterの共有リンク -->
                <li class="sns-twitter">
                    <a class="sns-link" target="_blank" href="<?php echo esc_url( 'https://twitter.com/share?url=' . $url_encode . '&text=' . $title_encode ); ?>"><span>Twitter</span></a>
                </li>
                <!-- LINEの共有リンク -->
                <li class="sns-line">
                    <a class="sns-link" target="_blank" href="<?php echo esc_url( 'https://line.me/R/msg/text/?' . $title_encode . '%0A' . $url_encode ); ?>"><span>LINE</span></a>
                </li>
            </ul>
          </div>
        <?php endwhile; endif; ?>
      </div>
      <!-- 関連記事 -->
      <p class="related_posts">関連記事</p>
      <div>
        <?php
          $categories = get_the_category($post->ID);
          if ( $categories ) :
          $category_ID = array();
          foreach($categories as $category):
          array_push( $category_ID, $category -> cat_ID);
          endforeach ;
          $args = array(
          'post__not_in' => array($post -> ID),
          'posts_per_page'=> 3,
          'category__in' => $category_ID,
          );
          $related_posts = new WP_Query($args);
          if( $related_posts-> have_posts() ):
        ?>
          <ul class="row">
            <?php
              while ($related_posts -> have_posts()) :
              $related_posts -> the_post();
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
            <?php endwhile; ?> 
          </ul>
        <?php else : ?>
          <p>関連記事はありません</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
        <?php endif; ?>  
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