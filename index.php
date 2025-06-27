<?php
/**
 * The main template file
 *
 * @package MoinCafe
 */

get_header(); ?>

<div class="site-content">
    <div class="container">

        <?php if (!is_user_logged_in()) : ?>
            <!-- 非ログイン時のウェルカムセクション -->
            <section class="welcome-section">
                <div class="welcome-content">
                    <div class="welcome-text">
                        <h1 class="welcome-title">
                            <span class="greeting">Moin!</span>
                            <span class="subtitle">Moin! Cafeへようこそ</span>
                        </h1>
                        <p class="welcome-description">
                            Moin! Cafeは、ドイツゆかりの
                            <br class="mobile-hidden">
                            日本語コミュニティです。
                            <br>
                            ママやプレママ、パパも
                            <br class="mobile-hidden">
                            日頃の悩みを打ち明けながらより良い日々を創造しませんか？
                        </p>
                        <div class="welcome-actions">
                            <a href="<?php echo wp_registration_url(); ?>" class="btn btn-primary">
                                <i class="icon-user-plus"></i>
                                会員登録
                            </a>
                            <a href="<?php echo wp_login_url(); ?>" class="btn btn-secondary">
                                <i class="icon-log-in"></i>
                                ログイン
                            </a>
                        </div>
                    </div>
                    <div class="welcome-visual">
                        <div class="cafe-illustration">
                            <div class="coffee-cup">
                                <div class="steam"></div>
                                <div class="steam"></div>
                                <div class="steam"></div>
                            </div>
                            <div class="moincafe-icons">
                                <div class="icon-bubble"></div>
                                <div class="icon-bubble"></div>
                                <div class="icon-bubble"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 機能紹介セクション -->
            <section class="features-section">
                <div class="section-header">
                    <h2 class="section-title">コミュニティの特徴</h2>
                    <p class="section-subtitle">Moin! Cafeで楽しめること</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icon-message-circle"></i>
                        </div>
                        <h3 class="feature-title">温かな交流</h3>
                        <p class="feature-description">
                            異文化や子育てでの悩み相談
                            <br>
                            定期開催のオンライン交流会
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icon-calendar"></i>
                        </div>
                        <h3 class="feature-title">楽しいイベント</h3>
                        <p class="feature-description">
                            定期的に催される
                            <br>
                            オンライン・オフラインイベント
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icon-shield"></i>
                        </div>
                        <h3 class="feature-title">日本語メイン</h3>
                        <p class="feature-description">
                            異文化の壁にぶつかる日本人を救い、救われたい
                        </p>
                    </div>
                </div>
            </section>

        <?php else : ?>
            <!-- ログイン時のダッシュボード風表示 -->
            <section class="member-dashboard">
                <div class="dashboard-header">
                    <h1 class="dashboard-title">
                        <span class="greeting">おかえりなさい、</span>
                        <span class="member-name"><?php echo wp_get_current_user()->display_name; ?>さん</span>
                    </h1>
                    <p class="dashboard-subtitle">今日も素敵な一日をお過ごしください</p>
                </div>

                <div class="dashboard-grid">
                    <!-- クイックアクション -->
                    <div class="dashboard-card quick-actions">
                        <h3 class="card-title">
                            <i class="icon-zap"></i>
                            クイックアクション
                        </h3>
                        <div class="action-buttons">
                            <a href="<?php echo home_url('/members/forum/'); ?>" class="action-btn">
                                <i class="icon-message-square"></i>
                                掲示板へ
                            </a>
                            <a href="<?php echo home_url('/members/events/'); ?>" class="action-btn">
                                <i class="icon-calendar"></i>
                                イベント一覧
                            </a>
                            <a href="<?php echo home_url('/members/profile/'); ?>" class="action-btn">
                                <i class="icon-user"></i>
                                プロフィール
                            </a>
                        </div>
                    </div>

                    <!-- 最新の掲示板投稿 -->
                    <div class="dashboard-card recent-posts">
                        <h3 class="card-title">
                            <i class="icon-message-circle"></i>
                            最新の投稿
                        </h3>
                        <div class="posts-list">
                            <?php
                            // bbPressの最新トピックを取得
                            if (function_exists('bbp_has_topics')) {
                                $topics = bbp_has_topics(array(
                                    'posts_per_page' => 3,
                                    'show_stickies' => false
                                ));

                                if ($topics) {
                                    while (bbp_topics()) {
                                        bbp_the_topic();
                                        ?>
                                        <div class="post-item">
                                            <div class="post-meta">
                                                <span class="post-author"><?php bbp_author_display_name(); ?></span>
                                                <span class="post-time"><?php bbp_topic_freshness_link(); ?></span>
                                            </div>
                                            <h4 class="post-title">
                                                <a href="<?php bbp_topic_permalink(); ?>">
                                                    <?php bbp_topic_title(); ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<p class="no-content">まだ投稿がありません</p>';
                                }
                            } else {
                                echo '<p class="no-content">掲示板機能を準備中です</p>';
                            }
                            ?>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo home_url('/members/forum/'); ?>" class="view-all-link">
                                すべて見る
                                <i class="icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 今後のイベント -->
                    <div class="dashboard-card upcoming-events">
                        <h3 class="card-title">
                            <i class="icon-calendar"></i>
                            今後のイベント
                        </h3>
                        <div class="events-list">
                            <?php
                            // The Events Calendarの今後のイベントを取得
                            if (class_exists('Tribe__Events__Main')) {
                                $events = tribe_get_events(array(
                                    'posts_per_page' => 3,
                                    'start_date' => 'now'
                                ));

                                if ($events) {
                                    foreach ($events as $event) {
                                        ?>
                                        <div class="event-item">
                                            <div class="event-date">
                                                <span class="month"><?php echo tribe_get_start_date($event->ID, false, 'M'); ?></span>
                                                <span class="day"><?php echo tribe_get_start_date($event->ID, false, 'j'); ?></span>
                                            </div>
                                            <div class="event-info">
                                                <h4 class="event-title">
                                                    <a href="<?php echo get_permalink($event->ID); ?>">
                                                        <?php echo get_the_title($event->ID); ?>
                                                    </a>
                                                </h4>
                                                <div class="event-meta">
                                                    <span class="event-time">
                                                        <i class="icon-clock"></i>
                                                        <?php echo tribe_get_start_date($event->ID, false, 'H:i'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<p class="no-content">予定されているイベントはありません</p>';
                                }
                            } else {
                                echo '<p class="no-content">イベント機能を準備中です</p>';
                            }
                            ?>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo home_url('/members/events/'); ?>" class="view-all-link">
                                すべて見る
                                <i class="icon-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- コミュニティ統計 -->
                    <div class="dashboard-card community-stats">
                        <h3 class="card-title">
                            <i class="icon-users"></i>
                            コミュニティ
                        </h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count_users()['total_users']; ?></div>
                                <div class="stat-label">メンバー</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">
                                    <?php
                                    if (function_exists('bbp_get_statistics')) {
                                        $stats = bbp_get_statistics();
                                        echo isset($stats['topic_count']) ? $stats['topic_count'] : '0';
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </div>
                                <div class="stat-label">トピック</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">
                                    <?php
                                    if (class_exists('Tribe__Events__Main')) {
                                        $event_count = wp_count_posts('tribe_events');
                                        echo $event_count->publish;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </div>
                                <div class="stat-label">イベント</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php endif; ?>

        <!-- お知らせセクション（共通） -->
        <?php
        $announcements = get_posts(array(
            'post_type' => 'post',
            'category_name' => 'announcement',
            'posts_per_page' => 3,
            'meta_query' => array(
                array(
                    'key' => '_announcement_important',
                    'compare' => 'EXISTS'
                )
            )
        ));

        if ($announcements) : ?>
            <section class="announcements-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="icon-bell"></i>
                        お知らせ
                    </h2>
                </div>
                <div class="announcements-list">
                    <?php foreach ($announcements as $announcement) : ?>
                        <div class="announcement-item">
                            <div class="announcement-meta">
                                <span class="announcement-date">
                                    <?php echo get_the_date('Y.m.d', $announcement); ?>
                                </span>
                                <?php if (get_post_meta($announcement->ID, '_announcement_important', true)) : ?>
                                    <span class="announcement-badge important">重要</span>
                                <?php endif; ?>
                            </div>
                            <h3 class="announcement-title">
                                <a href="<?php echo get_permalink($announcement); ?>">
                                    <?php echo get_the_title($announcement); ?>
                                </a>
                            </h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
