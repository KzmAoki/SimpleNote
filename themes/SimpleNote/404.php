<?php get_header(); ?>

  <main>
    <div class="not-found-page">
      <h1>404 NOT FOUND</h1>
      <p>お探しのページはありませんでした。</p>
      <a class="" href="<?php echo esc_url( home_url('/') ); ?>">TOPはこちら</a>
    </div>
  </main>
  
<?php get_footer(); ?>