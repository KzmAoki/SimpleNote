<?php
  // 自動アップデート
  // 本体のメジャーアップデート
  add_filter( 'allow_major_auto_core_updates', '__return_true' );
  // 本体のマイナーアップデート
  add_filter( 'allow_minor_auto_core_updates', '__return_true' );
  // プラグイン
  add_filter( 'auto_update_plugin', '__return_true' );

  // バージョン情報の非表示化
  remove_action('wp_head','wp_generator');
  function remove_wp_version( $src ) {
      if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
          $src = remove_query_arg( 'ver', $src );
      return $src;
  }
  add_filter( 'style_loader_src', 'remove_wp_version', 9999 );
  add_filter( 'script_loader_src', 'remove_wp_version', 9999 );

  // 外観/メニュー追加
  function custom_theme_setup() {
    add_theme_support( 'custom-header' );
    // add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' ); // 投稿、コメントのRSSフィードのリンク有効化
    add_theme_support( 'title-tag' ); // titleタグ自動生成
    add_theme_support( 'html5', array( // HTML5による出力
      'seach-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ) ); 
    add_theme_support( 'menus' );
  }
  add_action( 'after_setup_theme', 'custom_theme_setup' );
  
  // メニューの箱の場所を定義
  function twpp_setup_theme() {
    register_nav_menus( array(
      'header-navigation' => 'Header Navigation',
      'footer-navigation' => 'Footer Navigation',
    ) );  }
  
  add_action( 'after_setup_theme', 'twpp_setup_theme' );

  //ウィジェット有効化
    // register_sidebar(array(
    //   'name' => 'メニューバー',
    //   'id' => 'sidebar',
    //   'before_widget' => '<div id="%1$s" class="widget %2$s">',
    //   'after_widget' => '</div>',
    //   'before_title' => '<div class="widget-title">',
    //   'after_title' => '</div>',
    // ));

  // JavaScript,CSS読み込み
  function my_scripts_method() {
    wp_deregister_script('jquery'); //jQery読み込み
    wp_enqueue_script( 'jquery', '//code.jquery.com/jquery-3.6.1.min.js', "", "1.0.1", true); //true:</head>の前 false:</body>の前
    wp_enqueue_script(
      'main-js',
      get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0.1', true
    );
    wp_enqueue_style( // CSS読み込み
      'style-css',
      get_template_directory_uri() . '/css/style.css', array(), '1.0.1'
    );
  }
  add_action('wp_enqueue_scripts', 'my_scripts_method');



  // 検索条件が未入力時にsearch.phpにリダイレクト
  function set_redirect_template() {
    if (isset($_GET['s']) && empty($_GET['s'])) {
      include(TEMPLATEPATH . '/search.php');
      exit;
    }
  }
  add_action('template_redirect', 'set_redirect_template');

  // 検索結果の1ページあたりの最大表示数を調整
  function my_pre_get_posts($query) {
    if ( is_admin() || !$query->is_main_query() ) {
      return;
    }
    if ( $query->is_search() ) {
      $query->set( 'posts_per_page', 10 );
    }
  }
  add_action('pre_get_posts', 'my_pre_get_posts');

  // 検索条件のページタイプを指定(通常投稿のみ)
  function SearchFilter($query) {
    if ( $query->is_search() ) {
      $query -> set( 'post_type', 'post' );
    }
    return $query;
  }
  add_filter( 'pre_get_posts', 'SearchFilter' );



  // ユーザープロフィール項目追加
  function my_user_meta($wb) {
    //項目の追加例
    $wb['twitter'] = 'Twitter';
    $wb['github'] = 'GitHub';
    $wb['career'] = '職業';

    return $wb;
  }
  add_filter('user_contactmethods', 'my_user_meta',10,1);



  //SVGをアップロード
  function add_file_types_to_uploads($file_types){

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );

    return $file_types;
  }
  add_action('upload_mimes', 'add_file_types_to_uploads');



  //カテゴリー説明文でHTMLタグを使う
  remove_filter( 'pre_term_description', 'wp_filter_kses' );
  //カテゴリー説明文から自動で付与されるpタグを除去
  remove_filter('term_description', 'wpautop');

  
  
  // index ショートコード作成
  class Toc_Shortcode {
    
    private $add_script = false;
    private $atts = array();
    
    public function __construct() {
        add_shortcode( 'toc', array( $this, 'shortcode_content' ) );
        add_action( 'wp_footer', array( $this, 'add_script' ), 999999 );
        add_filter( 'the_content', array( $this, 'change_content' ), 9 );
    }
    
    function change_content( $content ) {
        return "<div id=\"toc_content\">{$content}</div>";
    }
    
    public function shortcode_content( $atts ) {
        global $post;
      
        if ( ! isset( $post ) )
            return '';
      
        $this->atts = shortcode_atts( array(
            'id' => '',
            'class' => 'toc',
            'title' => '目次',
            'toggle' => true,
            'showcount' => 2,
            'depth' => 0,
            'toplevel' => 2,
        ), $atts );
        
        $content = $post->post_content;
        
        $headers = array();
        preg_match_all( '/<([hH][1-6]).*?>(.*?)<\/[hH][1-6].*?>/u', $content, $headers );
        $header_count = count( $headers[0] );
        $counter = 0;
        $counters = array( 0, 0, 0, 0, 0, 0 );
        $current_depth = 0;
        $prev_depth = 0;
        $top_level = intval( $this->atts['toplevel'] );
        if ( $top_level < 1 ) $top_level = 1;
        if ( $top_level > 6 ) $top_level = 6;
        $this->atts['toplevel'] = $top_level;
        
        // 表示する階層数
        $max_depth = ( ( $this->atts['depth'] == 0 ) ? 6 : intval( $this->atts['depth'] ) );
        
        $toc_list = '';
        for ( $i = 0; $i < $header_count; $i++ ) {
            $depth = 0;
            switch ( strtolower( $headers[1][$i] ) ) {
                case 'h1': $depth = 1 - $top_level + 1; break;
                case 'h2': $depth = 2 - $top_level + 1; break;
                case 'h3': $depth = 3 - $top_level + 1; break;
                case 'h4': $depth = 4 - $top_level + 1; break;
                case 'h5': $depth = 5 - $top_level + 1; break;
                case 'h6': $depth = 6 - $top_level + 1; break;
            }
            if ( $depth >= 1 && $depth <= $max_depth ) {
                if ( $current_depth == $depth ) {
                    $toc_list .= '</li>';
                }
                while ( $current_depth > $depth ) {
                    $toc_list .= '</li></ul>';
                    $current_depth--;
                    $counters[$current_depth] = 0;
                }
                if ( $current_depth != $prev_depth ) {
                    $toc_list .= '</li>';
                }
                if ( $current_depth < $depth ) {
                    $class = $current_depth == 0 ? ' class="toc-list"' : '';
                    $style = $current_depth == 0 && $this->atts['close'] ? ' style="display: none;"' : '';
                    $toc_list .= "<ul{$class}{$style}>";
                    $current_depth++;
                }
                $counters[$current_depth - 1]++;
                $number = $counters[0];
                for ( $j = 1; $j < $current_depth; $j++ ) {
                    $number .= '-' . $counters[$j];
                }
                $counter++;
                $toc_list .= '<li><a href="#toc' . ($i + 1) . '"><span class="contentstable-number">' . $number . '</span> ' . $headers[2][$i] . '</a>';
                $prev_depth = $depth;
            }
        }
        while ( $current_depth >= 1 ) {
            $toc_list .= '</li></ul>';
            $current_depth--;
        }
        
        $html = '';
        if ( $counter >= $this->atts['showcount'] ) {
            $this->add_script = true;
            
            $html .= '<div' . ( $this->atts['id'] != '' ? ' id="' . $this->atts['id'] . '"' : '' ) . ' class="' . $this->atts['class'] . '">';
            $html .= '<p class="toc-title">' . $this->atts['title'] . $toggle . '</p>';
            $html .= $toc_list;
            $html .= '</div>' . "\n";
        }
        
        return $html;
    }
    
    public function add_script() {
        if ( ! $this->add_script ) {
            return false;
        }
          
?>
<script type="text/javascript">
let xoToc = () => {
  const entryContent = document.getElementById('toc_content');
  if (!entryContent) {
    return false;
  }
  
  /**
   * ヘッダータグに ID を付与
   */
  const headers = entryContent.querySelectorAll('h1, h2, h3, h4, h5, h6');
  for (let i = 0; i < headers.length; i++) {
    headers[i].setAttribute('id', 'toc' + (i + 1));
  }
};
xoToc();
</script>
<?php
      }
      
  }
    
  new Toc_Shortcode();
?>