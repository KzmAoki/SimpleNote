<?php get_header(); ?>
<div class="content-section">
  <main class="main container">
    <div aria-label="breadcrumb">
      <ol class="breadcrumb"><?php echo mytheme_breadcrumb(); ?></ol>
    </div>
    <h1>404 NOT FOUND</h1>
    <p>お探しのページはありませんでした。</p>
    <div class="mb-5"><a class="btn-primary p-2 px-3 rounded-1" href="<?php echo esc_url( home_url('/') ); ?>">TOPはこちら</a></div>
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
  </main>
  <?php get_footer(); ?>
</div>