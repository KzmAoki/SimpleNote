<?php get_header(); ?>
  <div class="header-img">
  <?php the_custom_header_markup(); ?>
  <div>
  <main>
    <nav>
      <ul class="top-catnav">
        <?php
        $categories = get_categories('parent=0');
        foreach($categories as $category): ?>
        <li class="parent-cat">
          <div class="parent-cat-name">
            <?php if (function_exists('z_taxonomy_image')) z_taxonomy_image($category->term_id); ?>
            <h2><?php echo $category->name; ?></h2>
          </div>
          <?php 
          $childs = get_categories('child_of='.$category->term_id);
          if($childs):
          ?>
          <ul>
            <?php foreach($childs as $child): ?>
            <li class="child-cat">
              <a href="<?php echo get_category_link($child->term_id); ?>">
                <?php if (function_exists('z_taxonomy_image')) z_taxonomy_image($child->term_id); ?>
                <span><?php echo $child->name; ?><span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </main>

<?php get_footer(); ?>