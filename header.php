<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package MoinCafe
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'moincafe'); ?></a>

    <header id="masthead" class="site-header">
        <div class="header-container">
            <!-- ヘッダートップ（お知らせバー） -->
            <?php if (!is_user_logged_in()) : ?>
                <div class="header-notice">
                    <div class="container">
                        <div class="notice-content">
                            <i class="icon-info"></i>
                            <span class="notice-text">会員制コミュニティサイトです。ご利用には会員登録が必要です。</span>
                            <a href="<?php echo wp_registration_url(); ?>" class="notice-link">今すぐ登録</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- メインヘッダー -->
            <div class="header-main">
                <div class="container">
                    <div class="header-content">
                        <!-- サイトブランディング -->
                        <div class="site-branding">
                            <?php
                            $custom_logo_id = get_theme_mod('custom_logo');
                            if ($custom_logo_id) :
                                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                                    <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php bloginfo('name'); ?>" class="custom-logo">
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-link" rel="home">
                                    <h1 class="site-title">
                                        <span class="title-main">Moin!</span>
                                        <span class="title-sub">Cafe</span>
                                    </h1>
                                </a>
                            <?php endif; ?>

                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                            ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div><!-- .site-branding -->

                        <!-- ユーザーメニュー -->
                        <div class="header-user-menu">
                            <?php if (is_user_logged_in()) : ?>
                                <!-- ログイン時のユーザーメニュー -->
                                <div class="user-menu-logged-in">
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <?php echo get_avatar(get_current_user_id(), 40); ?>
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name"><?php echo wp_get_current_user()->display_name; ?></span>
                                            <span class="user-status">オンライン</span>
                                        </div>
                                    </div>
                                    <div class="user-dropdown">
                                        <button class="user-dropdown-toggle" aria-expanded="false">
                                            <i class="icon-chevron-down"></i>
                                        </button>
                                        <div class="user-dropdown-menu">
                                            <a href="<?php echo home_url('/members/profile/'); ?>" class="dropdown-item">
                                                <i class="icon-user"></i>
                                                プロフィール
                                            </a>
                                            <a href="<?php echo home_url('/members/dashboard/'); ?>" class="dropdown-item">
                                                <i class="icon-home"></i>
                                                ダッシュボード
                                            </a>
                                            <?php if (current_user_can('administrator')) : ?>
                                                <a href="<?php echo home_url('/members/admin/'); ?>" class="dropdown-item">
                                                    <i class="icon-settings"></i>
                                                    管理画面
                                                </a>
                                            <?php endif; ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="<?php echo wp_logout_url(home_url()); ?>" class="dropdown-item logout">
                                                <i class="icon-log-out"></i>
                                                ログアウト
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <!-- 非ログイン時のメニュー -->
                                <div class="user-menu-logged-out">
                                    <a href="<?php echo wp_login_url(); ?>" class="btn btn-outline">
                                        <i class="icon-log-in"></i>
                                        ログイン
                                    </a>
                                    <a href="<?php echo wp_registration_url(); ?>" class="btn btn-primary">
                                        <i class="icon-user-plus"></i>
                                        会員登録
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div><!-- .header-user-menu -->

                        <!-- モバイルメニューボタン -->
                        <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="hamburger">
                                <span class="hamburger-line"></span>
                                <span class="hamburger-line"></span>
                                <span class="hamburger-line"></span>
                            </span>
                            <span class="screen-reader-text">メニュー</span>
                        </button>
                    </div><!-- .header-content -->
                </div><!-- .container -->
            </div><!-- .header-main -->

            <!-- ナビゲーションメニュー -->
            <nav id="site-navigation" class="main-navigation">
                <div class="container">
                    <?php if (is_user_logged_in()) : ?>
                        <!-- 会員用ナビゲーション -->
                        <div class="nav-menu-wrapper">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'member-menu',
                                'menu_id'        => 'member-menu',
                                'menu_class'     => 'nav-menu member-menu',
                                'container'      => false,
                                'fallback_cb'    => 'moincafe_member_menu_fallback',
                            ));
                            ?>

                            <!-- 検索フォーム -->
                            <div class="header-search">
                                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                    <label class="search-label">
                                        <span class="screen-reader-text">検索:</span>
                                        <input type="search" class="search-field" placeholder="サイト内検索..." value="<?php echo get_search_query(); ?>" name="s">
                                    </label>
                                    <button type="submit" class="search-submit">
                                        <i class="icon-search"></i>
                                        <span class="screen-reader-text">検索</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- 非会員用ナビゲーション -->
                        <div class="nav-menu-wrapper">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'public-menu',
                                'menu_id'        => 'public-menu',
                                'menu_class'     => 'nav-menu public-menu',
                                'container'      => false,
                                'fallback_cb'    => 'moincafe_public_menu_fallback',
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                </div><!-- .container -->
            </nav><!-- #site-navigation -->
        </div><!-- .header-container -->

        <!-- モバイルメニューオーバーレイ -->
        <div class="mobile-menu-overlay">
            <div class="mobile-menu-content">
                <div class="mobile-menu-header">
                    <h3 class="mobile-menu-title">メニュー</h3>
                    <button class="mobile-menu-close">
                        <i class="icon-x"></i>
                        <span class="screen-reader-text">閉じる</span>
                    </button>
                </div>
                <div class="mobile-menu-body">
                    <?php if (is_user_logged_in()) : ?>
                        <!-- ログイン時のモバイルメニュー -->
                        <div class="mobile-user-info">
                            <div class="mobile-avatar">
                                <?php echo get_avatar(get_current_user_id(), 60); ?>
                            </div>
                            <div class="mobile-user-details">
                                <span class="mobile-user-name"><?php echo wp_get_current_user()->display_name; ?></span>
                                <span class="mobile-user-status">オンライン</span>
                            </div>
                        </div>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'member-menu',
                            'menu_class'     => 'mobile-nav-menu',
                            'container'      => false,
                            'fallback_cb'    => 'moincafe_member_menu_fallback',
                        ));
                        ?>
                        <div class="mobile-menu-actions">
                            <a href="<?php echo wp_logout_url(home_url()); ?>" class="mobile-logout-btn">
                                <i class="icon-log-out"></i>
                                ログアウト
                            </a>
                        </div>
                    <?php else : ?>
                        <!-- 非ログイン時のモバイルメニュー -->
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'public-menu',
                            'menu_class'     => 'mobile-nav-menu',
                            'container'      => false,
                            'fallback_cb'    => 'moincafe_public_menu_fallback',
                        ));
                        ?>
                        <div class="mobile-menu-actions">
                            <a href="<?php echo wp_login_url(); ?>" class="btn btn-outline btn-block">
                                <i class="icon-log-in"></i>
                                ログイン
                            </a>
                            <a href="<?php echo wp_registration_url(); ?>" class="btn btn-primary btn-block">
                                <i class="icon-user-plus"></i>
                                会員登録
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">
        <main id="main" class="site-main">
