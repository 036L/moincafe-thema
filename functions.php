<?php
/**
 * Moin Cafe Theme Functions
 *
 * @package MoinCafeTheme
 * @version 1.0.0
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマのセットアップ
 */
function moincafe_theme_setup() {
    // 翻訳ファイルの読み込み
    load_theme_textdomain('moincafe-theme', get_template_directory() . '/languages');

    // HTML5サポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // 投稿とコメントのRSSフィードを有効化
    add_theme_support('automatic-feed-links');

    // タイトルタグサポート
    add_theme_support('title-tag');

    // カスタムロゴサポート
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // アイキャッチ画像サポート
    add_theme_support('post-thumbnails');

    // カスタム画像サイズ
    add_image_size('moincafe-thumbnail', 300, 200, true);
    add_image_size('moincafe-medium', 600, 400, true);
    add_image_size('moincafe-large', 1200, 800, true);

    // メニューサポート
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'moincafe-theme'),
        'footer'  => __('Footer Menu', 'moincafe-theme'),
        'members' => __('Members Menu', 'moincafe-theme'),
    ));

    // カスタムヘッダーサポート
    add_theme_support('custom-header', array(
        'default-image'      => get_template_directory_uri() . '/assets/images/header-bg.jpg',
        'width'              => 1200,
        'height'             => 300,
        'flex-width'         => true,
        'flex-height'        => true,
        'uploads'            => true,
        'header-text'        => false,
    ));

    // カスタム背景サポート
    add_theme_support('custom-background', array(
        'default-color' => 'fafafa',
        'default-image' => '',
    ));

    // WooCommerceサポート（将来の拡張用）
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'moincafe_theme_setup');

/**
 * コンテンツの幅を設定
 */
if (!isset($content_width)) {
    $content_width = 1200;
}

/**
 * スタイルとスクリプトの読み込み
 */
function moincafe_theme_scripts() {
    // メインスタイルシート
    wp_enqueue_style(
        'moincafe-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // レスポンシブスタイル
    wp_enqueue_style(
        'moincafe-responsive',
        get_template_directory_uri() . '/assets/css/responsive.css',
        array('moincafe-style'),
        wp_get_theme()->get('Version')
    );

    // 会員専用スタイル
    if (is_user_logged_in()) {
        wp_enqueue_style(
            'moincafe-members',
            get_template_directory_uri() . '/assets/css/members.css',
            array('moincafe-style'),
            wp_get_theme()->get('Version')
        );
    }

    // コンポーネントスタイル
    wp_enqueue_style(
        'moincafe-components',
        get_template_directory_uri() . '/assets/css/components.css',
        array('moincafe-style'),
        wp_get_theme()->get('Version')
    );

    // メインJavaScript
    wp_enqueue_script(
        'moincafe-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );

    // 会員専用JavaScript
    if (is_user_logged_in()) {
        wp_enqueue_script(
            'moincafe-members',
            get_template_directory_uri() . '/assets/js/members.js',
            array('jquery', 'moincafe-main'),
            wp_get_theme()->get('Version'),
            true
        );
    }

    // フォーラムページ用JavaScript
    if (function_exists('is_bbpress') && is_bbpress()) {
        wp_enqueue_script(
            'moincafe-forum',
            get_template_directory_uri() . '/assets/js/forum.js',
            array('jquery', 'moincafe-main'),
            wp_get_theme()->get('Version'),
            true
        );
    }

    // イベントページ用JavaScript
    if (function_exists('tribe_is_event') && tribe_is_event()) {
        wp_enqueue_script(
            'moincafe-events',
            get_template_directory_uri() . '/assets/js/events.js',
            array('jquery', 'moincafe-main'),
            wp_get_theme()->get('Version'),
            true
        );
    }

    // Ajax用の変数を渡す
    wp_localize_script('moincafe-main', 'moincafeAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('moincafe_nonce'),
        'strings' => array(
            'loading'    => __('Loading...', 'moincafe-theme'),
            'error'      => __('An error occurred', 'moincafe-theme'),
            'success'    => __('Success!', 'moincafe-theme'),
            'confirm'    => __('Are you sure?', 'moincafe-theme'),
        )
    ));

    // コメント返信スクリプト
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'moincafe_theme_scripts');

/**
 * 管理画面用スタイル
 */
function moincafe_admin_styles() {
    wp_enqueue_style(
        'moincafe-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('admin_enqueue_scripts', 'moincafe_admin_styles');

/**
 * ウィジェットエリアの登録
 */
function moincafe_widgets_init() {
    // サイドバー
    register_sidebar(array(
        'name'          => __('Main Sidebar', 'moincafe-theme'),
        'id'            => 'sidebar-main',
        'description'   => __('Appears on posts and pages', 'moincafe-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // 会員専用サイドバー
    register_sidebar(array(
        'name'          => __('Members Sidebar', 'moincafe-theme'),
        'id'            => 'sidebar-members',
        'description'   => __('Appears on member pages', 'moincafe-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // フッターウィジェット
    for ($i = 1; $i <= 3; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Widget Area %d', 'moincafe-theme'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Footer widget area %d', 'moincafe-theme'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }
}
add_action('widgets_init', 'moincafe_widgets_init');

/**
 * カスタム投稿タイプの登録
 */
function moincafe_register_post_types() {
    // 会員紹介投稿タイプ
    register_post_type('member_intro', array(
        'labels' => array(
            'name'          => __('Member Introductions', 'moincafe-theme'),
            'singular_name' => __('Member Introduction', 'moincafe-theme'),
            'add_new'       => __('Add New', 'moincafe-theme'),
            'add_new_item'  => __('Add New Introduction', 'moincafe-theme'),
            'edit_item'     => __('Edit Introduction', 'moincafe-theme'),
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'capability_type' => 'post',
        'capabilities' => array(
            'publish_posts' => 'publish_member_intros',
            'edit_posts'    => 'edit_member_intros',
            'edit_others_posts' => 'edit_others_member_intros',
            'delete_posts'  => 'delete_member_intros',
            'delete_others_posts' => 'delete_others_member_intros',
            'read_private_posts' => 'read_private_member_intros',
        ),
        'supports'      => array('title', 'editor', 'thumbnail', 'author'),
        'menu_icon'     => 'dashicons-groups',
    ));
}
add_action('init', 'moincafe_register_post_types');

/**
 * カスタムフィールドの登録
 */
function moincafe_register_meta_fields() {
    // ユーザーメタフィールド
    register_meta('user', 'moincafe_nickname', array(
        'type'         => 'string',
        'description'  => 'moincafe nickname',
        'single'       => true,
        'show_in_rest' => true,
    ));

    register_meta('user', 'moincafe_bio', array(
        'type'         => 'string',
        'description'  => 'moincafe bio',
        'single'       => true,
        'show_in_rest' => true,
    ));

    register_meta('user', 'moincafe_avatar', array(
        'type'         => 'string',
        'description'  => 'moincafe avatar URL',
        'single'       => true,
        'show_in_rest' => true,
    ));

    register_meta('user', 'moincafe_join_date', array(
        'type'         => 'string',
        'description'  => 'moincafe join date',
        'single'       => true,
        'show_in_rest' => false,
    ));

    register_meta('user', 'moincafe_last_login', array(
        'type'         => 'string',
        'description'  => 'Last login date',
        'single'       => true,
        'show_in_rest' => false,
    ));

    register_meta('user', 'moincafe_notifications', array(
        'type'         => 'string',
        'description'  => 'Notification settings',
        'single'       => true,
        'show_in_rest' => false,
    ));
}
add_action('init', 'moincafe_register_meta_fields');

/**
 * 会員登録時の処理
 */
function moincafe_user_registration_hook($user_id) {
    // 参加日を記録
    update_user_meta($user_id, 'moincafe_join_date', current_time('mysql'));

    // デフォルトの通知設定
    $default_notifications = array(
        'new_posts'    => 'yes',
        'new_events'   => 'yes',
        'forum_replies' => 'yes',
        'email_digest' => 'weekly',
    );
    update_user_meta($user_id, 'moincafe_notifications', serialize($default_notifications));

    // デフォルトの権限を設定
    $user = new WP_User($user_id);
    $user->set_role('subscriber');
}
add_action('user_register', 'moincafe_user_registration_hook');

/**
 * ログイン時の処理
 */
function moincafe_user_login_hook($user_login, $user) {
    // 最終ログイン日時を更新
    update_user_meta($user->ID, 'moincafe_last_login', current_time('mysql'));
}
add_action('wp_login', 'moincafe_user_login_hook', 10, 2);

/**
 * カスタムユーザー権限の追加
 */
function moincafe_add_custom_capabilities() {
    // 管理者に会員管理権限を追加
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('manage_moincafe_members');
        $role->add_cap('publish_member_intros');
        $role->add_cap('edit_member_intros');
        $role->add_cap('edit_others_member_intros');
        $role->add_cap('delete_member_intros');
        $role->add_cap('delete_others_member_intros');
        $role->add_cap('read_private_member_intros');
    }

    // エディターに一部権限を追加
    $role = get_role('editor');
    if ($role) {
        $role->add_cap('publish_member_intros');
        $role->add_cap('edit_member_intros');
        $role->add_cap('edit_others_member_intros');
    }
}
add_action('init', 'moincafe_add_custom_capabilities');

/**
 * 非会員のアクセス制限
 */
function moincafe_restrict_access() {
    // 会員専用ページの配列
    $restricted_pages = array('members', 'dashboard', 'profile', 'forum', 'events');

    // 現在のページが制限対象かチェック
    $current_page = get_query_var('pagename');
    if (!$current_page) {
        $current_page = get_query_var('name');
    }

    // 非ログインユーザーで制限ページにアクセスした場合
    if (!is_user_logged_in() && in_array($current_page, $restricted_pages)) {
        // ログインページにリダイレクト
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }

    // bbPressフォーラムへのアクセス制限
    if (function_exists('is_bbpress') && is_bbpress() && !is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
}
add_action('template_redirect', 'moincafe_restrict_access');

/**
 * セキュリティ強化
 */
function moincafe_security_enhancements() {
    // WordPressバージョン情報を隠す
    remove_action('wp_head', 'wp_generator');

    // RSD リンクを削除
    remove_action('wp_head', 'rsd_link');

    // wlwmanifest リンクを削除
    remove_action('wp_head', 'wlwmanifest_link');

    // 短縮URLを削除
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // REST API ヘッダーを削除
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('template_redirect', 'rest_output_link_header', 11);
}
add_action('init', 'moincafe_security_enhancements');

/**
 * ログインページのカスタマイズ
 */
function moincafe_login_style() {
    wp_enqueue_style('moincafe-login', get_template_directory_uri() . '/assets/css/login.css');
}
add_action('login_enqueue_scripts', 'moincafe_login_style');

/**
 * ログインページのロゴリンクを変更
 */
function moincafe_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'moincafe_login_logo_url');

/**
 * ログインページのロゴタイトルを変更
 */
function moincafe_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'moincafe_login_logo_title');

/**
 * カスタムメニューウォーカー
 */
class moincafe_Walker_Nav_Menu extends Walker_Nav_Menu {

    // メニュー項目の開始タグ
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= '<li' . $id . $class_names .'>';

        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * インクルードファイルの読み込み
 */
$include_files = array(
    'inc/custom-post-types.php',
    'inc/custom-fields.php',
    'inc/user-functions.php',
    'inc/security.php',
    'inc/helpers.php',
);

foreach ($include_files as $file) {
    $file_path = get_template_directory() . '/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

/**
 * 開発環境での設定
 */
if (defined('WP_DEBUG') && WP_DEBUG) {
    // デバッグ情報の表示
    function moincafe_debug_info() {
        if (current_user_can('manage_options')) {
            echo '<div style="position: fixed; bottom: 10px; right: 10px; background: #fff; padding: 10px; border: 1px solid #ccc; font-size: 12px; z-index: 9999;">';
            echo 'Queries: ' . get_num_queries() . '<br>';
            echo 'Load Time: ' . timer_stop() . 's<br>';
            echo 'Memory: ' . size_format(memory_get_peak_usage(true));
            echo '</div>';
        }
    }
    add_action('wp_footer', 'moincafe_debug_info');
}

/**
 * Ajax ハンドラーの例
 */
function moincafe_ajax_example() {
    // ノンス検証
    if (!wp_verify_nonce($_POST['nonce'], 'moincafe_nonce')) {
        wp_die('Security check failed');
    }

    // 処理内容をここに記述
    $response = array(
        'success' => true,
        'message' => __('Ajax request successful', 'moincafe-theme'),
    );

    wp_send_json($response);
}
add_action('wp_ajax_moincafe_example', 'moincafe_ajax_example');
add_action('wp_ajax_nopriv_moincafe_example', 'moincafe_ajax_example');

/**
 * OGP メタタグの追加
 */
function moincafe_add_ogp_tags() {
    if (is_front_page()) {
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
    } elseif (is_single() || is_page()) {
        global $post;
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 20)) . '">' . "\n";
    }

    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";

    if (has_post_thumbnail()) {
        echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'large')) . '">' . "\n";
    }
}
add_action('wp_head', 'moincafe_add_ogp_tags');

// テーマアクティベーション時の処理
function moincafe_theme_activation() {
    // フラッシュルールの更新
    flush_rewrite_rules();

    // デフォルトオプションの設定
    $default_options = array(
        'moincafe_allow_registration' => 1,
        'moincafe_moderate_registration' => 0,
        'moincafe_email_notifications' => 1,
    );

    foreach ($default_options as $option => $value) {
        if (get_option($option) === false) {
            add_option($option, $value);
        }
    }
}
register_activation_hook(__FILE__, 'moincafe_theme_activation');
