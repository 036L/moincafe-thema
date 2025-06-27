<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package MoinCafe
 */

?>

        </main><!-- #main -->
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            
            <!-- フッタートップ（会員向け情報） -->
            <?php if (is_user_logged_in()) : ?>
                <div class="footer-member-info">
                    <div class="container">
                        <div class="member-info-grid">
                            <div class="member-stats">
                                <h4 class="member-info-title">
                                    <i class="icon-activity"></i>
                                    あなたの活動
                                </h4>
                                <div class="stats-list">
                                    <div class="stat-item">
                                        <span class="stat-label">参加日:</span>
                                        <span class="stat-value">
                                            <?php 
                                            $user = wp_get_current_user();
                                            echo date('Y年m月d日', strtotime($user->user_registered));
                                            ?>
                                        </span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">投稿数:</span>
                                        <span class="stat-value">
                                            <?php 
                                            if (function_exists('bbp_get_user_topic_count')) {
                                                echo bbp_get_user_topic_count();
                                            } else {
                                                echo '0';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="quick-links">
                                <h4 class="member-info-title">
                                    <i class="icon-zap"></i>
                                    クイックリンク
                                </h4>
                                <ul class="quick-links-list">
                                    <li><a href="<?php echo home_url('/members/forum/'); ?>">掲示板</a></li>
                                    <li><a href="<?php echo home_url('/members/events/'); ?>">イベント</a></li>
                                    <li><a href="<?php echo home_url('/members/profile/'); ?>">プロフィール</a></li>
                                    <li><a href="<?php echo home_url('/contact/'); ?>">お問い合わせ</a></li>
                                </ul>
                            </div>
                            
                            <div class="community-status">
                                <h4 class="member-info-title">
                                    <i class="icon-users"></i>
                                    コミュニティ状況
                                </h4>
                                <div class="status-info">
                                    <div class="status-item">
                                        <span class="status-number"><?php echo count_users()['total_users']; ?></span>
                                        <span class="status-label">総メンバー数</span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-number">
                                            <?php
                                            $online_users = get_transient('moincafe_online_users');
                                            echo is_array($online_users) ? count($online_users) : '1';
                                            ?>
                                        </span>
                                        <span class="status-label">オンライン</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- メインフッター -->
            <div class="footer-main">
                <div class="container">
                    <div class="footer-grid">
                        
                        <!-- サイト情報 -->
                        <div class="footer-section site-info-section">
                            <div class="footer-branding">
                                <h3 class="footer-title">
                                    <span class="title-main">Moin!</span>
                                    <span class="title-sub">Cafe</span>
                                </h3>
                                <p class="footer-description">
                                    温かな交流を大切にする<br>
                                    会員制コミュニティです。<br>
                                    素敵な仲間たちと一緒に、<br>
                                    ゆったりとした時間を過ごしませんか？
                                </p>
                            </div>
                            
                            <!-- ソーシャルメディア -->
                            <div class="social-links">
                                <h4 class="social-title">フォローしてね</h4>
                                <div class="social-icons">
                                    <?php
                                    // カスタマイザーで設定されたソーシャルリンクを表示
                                    $social_links = array(
                                        'twitter' => get_theme_mod('social_twitter'),
                                        'facebook' => get_theme_mod('social_facebook'),
                                        'instagram' => get_theme_mod('social_instagram'),
                                        'youtube' => get_theme_mod('social_youtube'),
                                    );
                                    
                                    foreach ($social_links as $platform => $url) {
                                        if (!empty($url)) {
                                            echo '<a href="' . esc_url($url) . '" class="social-link social-' . $platform . '" target="_blank" rel="noopener noreferrer">';
                                            echo '<i class="icon-' . $platform . '"></i>';
                                            echo '<span class="screen-reader-text">' . ucfirst($platform) . '</span>';
                                            echo '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- ナビゲーション -->
                        <div class="footer-section navigation-section">
                            <h4 class="footer-section-title">ナビゲーション</h4>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer-menu',
                                'menu_class'     => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => 'moincafe_footer_menu_fallback',
                            ));
                            ?>
                        </div>

                        <!-- お知らせ・更新情報 -->
                        <div class="footer-section updates-section">
                            <h4 class="footer-section-title">
                                <i class="icon-bell"></i>
                                最新のお知らせ
                            </h4>
                            <div class="footer-updates">
                                <?php
                                $recent_announcements = get_posts(array(
                                    'post_type' => 'post',
                                    'category_name' => 'announcement',
                                    'posts_per_page' => 3,
                                    'post_status' => 'publish'
                                ));
                                
                                if ($recent_announcements) {
                                    echo '<ul class="footer-updates-list">';
                                    foreach ($recent_announcements as $announcement) {
                                        echo '<li class="footer-update-item">';
                                        echo '<a href="' . get_permalink($announcement) . '">';
                                        echo '<span class="update-date">' . get_the_date('m/d', $announcement) . '</span>';
                                        echo '<span class="update-title">' . get_the_title($announcement) . '</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p class="no-updates">現在お知らせはありません</p>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- お問い合わせ -->
                        <div class="footer-section contact-section">
                            <h4 class="footer-section-title">
                                <i class="icon-mail"></i>
                                お問い合わせ
                            </h4>
                            <div class="contact-info">
                                <p class="contact-description">
                                    ご質問やご要望がございましたら<br>
                                    お気軽にお問い合わせください。
                                </p>
                                <a href="<?php echo home_url('/contact/'); ?>" class="contact-button">
                                    <i class="icon-send"></i>
                                    お問い合わせフォーム
                                </a>
                                
                                <!-- 営業時間（管理者向け情報） -->
                                <div class="support-hours">
                                    <h5 class="support-title">サポート対応時間</h5>
                                    <p class="support-time">
                                        平日 9:00 - 18:00<br>
                                        <small>（土日祝日を除く）</small>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div><!-- .footer-grid -->
                </div><!-- .container -->
            </div><!-- .footer-main -->

            <!-- フッターボトム -->
            <div class="footer-bottom">
                <div class="container">
                    <div class="footer-bottom-content">
                        <div class="copyright">
                            <p>&copy; <?php echo date('Y'); ?> Moin! Cafe. All rights reserved.</p>
                        </div>
                        
                        <div class="footer-legal-links">
                            <a href="<?php echo home_url('/privacy-policy/'); ?>">プライバシーポリシー</a>
                            <span class="separator">|</span>
                            <a href="<?php echo home_url('/terms-of-service/'); ?>">利用規約</a>
                            <?php if (is_user_logged_in() && current_user_can('administrator')) : ?>
                                <span class="separator">|</span>
                                <a href="<?php echo admin_url(); ?>">WordPress管理画面</a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="footer-version">
                            <small>
                                WordPress <?php bloginfo('version'); ?> | 
                                Theme: MoinCafe v<?php echo wp_get_theme()->get('Version'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div><!-- .footer-bottom -->

        </div><!-- .footer-container -->
    </footer><!-- #colophon -->

    <!-- トップに戻るボタン -->
    <button id="back-to-top" class="back-to-top" aria-label="ページトップに戻る">
        <i class="icon-arrow-up"></i>
    </button>

    <!-- 会員向けフローティングアクション -->
    <?php if (is_user_logged_in()) : ?>
        <div class="floating-actions">
            <div class="floating-menu">
                <button class="floating-toggle" aria-label="クイックメニューを開く">
                    <i class="icon-plus"></i>
                </button>
                <div class="floating-menu-items">
                    <a href="<?php echo home_url('/members/forum/'); ?>" class="floating-item" title="新しい投稿">
                        <i class="icon-edit"></i>
                    </a>
                    <a href="<?php echo home_url('/members/events/'); ?>" class="floating-item" title="イベント確認">
                        <i class="icon-calendar"></i>
                    </a>
                    <a href="<?php echo home_url('/members/profile/'); ?>" class="floating-item" title="プロフィール">
                        <i class="icon-user"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- 通知システム -->
    <div id="notification-container" class="notification-container">
        <!-- 動的に通知が追加される -->
    </div>

</div><!-- #page -->

<?php wp_footer(); ?>

<script>
// トップに戻るボタンの制御
document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.getElementById('back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // フローティングメニューの制御
    const floatingToggle = document.querySelector('.floating-toggle');
    const floatingMenu = document.querySelector('.floating-menu');
    
    if (floatingToggle && floatingMenu) {
        floatingToggle.addEventListener('click', function() {
            floatingMenu.classList.toggle('active');
        });
        
        // 外部クリックで閉じる
        document.addEventListener('click', function(e) {
            if (!floatingMenu.contains(e.target)) {
                floatingMenu.classList.remove('active');
            }
        });
    }
});
</script>

</body>
</html>
