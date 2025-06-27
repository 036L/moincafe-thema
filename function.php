<?php
// テーマの基本機能
function moin_cafe_theme_setup() {
    add_theme_support('title-tag'); // タイトルタグ自動出力
    add_theme_support('post-thumbnails'); // アイキャッチ画像
    register_nav_menus([
        'main-menu' => 'メインメニュー',
    ]);
}
add_action('after_setup_theme', 'my_simple_theme_setup');

// CSSの読み込み
function moin_cafe_theme_scripts() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'moin_cafe_theme_scripts');
