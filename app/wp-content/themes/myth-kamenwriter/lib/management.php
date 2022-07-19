<?php

function my_filter_rest_endpoints($endpoints)
{
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
}

add_filter('rest_endpoints', 'my_filter_rest_endpoints', 10, 1);

// バージョン更新を非表示にする
if (!current_user_can('administrator')) {
    add_filter('pre_site_transient_update_core', '__return_zero');
}
// APIによるバージョンチェックの通信をさせない
if (!current_user_can('administrator')) {
    remove_action('wp_version_check', 'wp_version_check');
    remove_action('admin_init', '_maybe_update_core');
}

// フッターWordPressリンクを非表示に
function custom_admin_footer()
{
    echo '<a href="mailto:info@bbns.jp">管理者へのお問い合わせ</a>';
}
    add_filter('admin_footer_text', 'custom_admin_footer');

// 管理バーの項目を非表示
if (!current_user_can('administrator')) {
    function remove_admin_bar_menu($wp_admin_bar)
    {
        $wp_admin_bar->remove_menu('wp-logo'); // WordPressシンボルマーク
        $wp_admin_bar->remove_menu('my-account'); // マイアカウント
    }
    add_action('admin_bar_menu', 'remove_admin_bar_menu', 70);
}

// 管理バーのヘルプメニューを非表示にする
function my_admin_head()
{
    echo '<style type="text/css">#contextual-help-link-wrap{display:none;}</style>';
}
add_action('admin_head', 'my_admin_head');

    // 管理バーにログアウトを追加
function add_new_item_in_admin_bar()
{
    global $wp_admin_bar;
    $wp_admin_bar->add_menu(array(
     'id' => 'new_item_in_admin_bar',
     'title' => __('ログアウト'),
     'href' => wp_logout_url()
    ));
}
add_action('wp_before_admin_bar_render', 'add_new_item_in_admin_bar');

    // ダッシュボードウィジェット非表示
function example_remove_dashboard_widgets()
{
    if (!current_user_can('level_7')) { //level10以下のユーザーの場合ウィジェットをunsetする
        global $wp_meta_boxes;
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
    }
}
    add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets');

// 投稿画面の項目を非表示にする
function remove_default_post_screen_metaboxes()
{
    if (!current_user_can('level_7')) { // level10以下のユーザーの場合メニューをremoveする
        remove_meta_box('postcustom', 'post', 'normal'); // カスタムフィールド
        remove_meta_box('postexcerpt', 'post', 'normal'); // 抜粋
        remove_meta_box('commentstatusdiv', 'post', 'normal'); // ディスカッション
        remove_meta_box('commentsdiv', 'post', 'normal'); // コメント
        remove_meta_box('trackbacksdiv', 'post', 'normal'); // トラックバック
        remove_meta_box('authordiv', 'post', 'normal'); // 作成者
        remove_meta_box('slugdiv', 'post', 'normal'); // スラッグ
        remove_meta_box('revisionsdiv', 'post', 'normal'); // リビジョン
    }
}
add_action('admin_menu', 'remove_default_post_screen_metaboxes');

    //入力画面 現在の状況　のWordPress表示を消す
function my_admin_print_styles()
{
    if (!current_user_can('level_7')) {
        echo '<style type="text/css">.versions p,#wp-version-message{display:none;}</style>';
    }
}
add_action('admin_print_styles', 'my_admin_print_styles', 21);

function remove_menus()
{
    if (!current_user_can('level_7')) { //level10以下のユーザーの場合メニューをunsetする
        remove_menu_page('wpcf7'); //Contact Form 7
        global $menu;
        unset($menu[2]); // ダッシュボード
        //unset($menu[4]); // メニューの線1
        unset($menu[5]); // 投稿
        unset($menu[10]); // メディア
        unset($menu[15]); // リンク
        unset($menu[20]); // ページ
        unset($menu[25]); // コメント
        //unset($menu[59]); // メニューの線2
        unset($menu[60]); // テーマ
        unset($menu[65]); // プラグイン
        //unset($menu[70]); // プロフィール
        unset($menu[75]); // ツール
        unset($menu[80]); // 設定
        unset($menu[90]); // メニューの線3
    }
}
add_action('admin_menu', 'remove_menus');

function get_last_modified_date()
{
    $date = array(
      get_the_modified_time("Y"),
      get_the_modified_time("m"),
      get_the_modified_time("d")
    );
    $time = array(
      get_the_modified_time("H"),
      get_the_modified_time("i"),
      get_the_modified_time("s"),
    );
    $time_str = implode("-", $date) . "T" . implode(":", $time) . "+09:00";
    return date("r", strtotime($time_str));
}
function add_last_modified()
{
    if (is_single()) {
        header(sprintf("Last-Modified: %s", get_last_modified_date()));
    }
}
add_filter('the_content', 'wpautop_filter', 9);
function wpautop_filter($content)
{
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
    return $content;
}
add_filter('wpcf7_autop_or_not', '__return_false');

function no_self_ping(&$links)
{
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'no_self_ping');
remove_action('wp_head', 'feed_links', 2); //サイト全体のフィード
remove_action('wp_head', 'feed_links_extra', 3); //その他のフィード
remove_action('wp_head', 'rsd_link'); //Really Simple Discoveryリンク
remove_action('wp_head', 'wlwmanifest_link'); //Windows Live Writerリンク
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); //前後の記事リンク
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); //ショートリンク
remove_action('wp_head', 'wp_generator'); //WPバージョン
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rest_output_link_wp_head');
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'rel_canonical');
remove_filter('wp_robots', 'wp_robots_max_image_preview_large');
add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2);
function remove_dns_prefetch($hints, $relation_type)
{
    if ('dns-prefetch' === $relation_type) {
        return array_diff(wp_dependencies_unique_hosts(), $hints);
    }
    return $hints;
}
add_filter('wp_sitemaps_enabled', '__return_false');

function replace_preview_post_link($url)
{
    $replace_url = str_replace(home_url(), site_url(), $url);
    return $replace_url;
}
add_filter('preview_post_link', 'replace_preview_post_link');

function fix_preview_link_on_draft()
{
    echo '<script type="text/javascript">
	jQuery(document).ready(function () {
		const checkPreviewInterval = setInterval(checkPreview, 1000);
		function checkPreview() {
			const editorPreviewButton = jQuery(".edit-post-header-preview__button-external");
			if (editorPreviewButton.length && editorPreviewButton.attr("href") !== "' . get_preview_post_link() . '" ) {
				editorPreviewButton.attr("href", "' . get_preview_post_link() . '");
				editorPreviewButton.off();
				editorPreviewButton.click(false);
				editorPreviewButton.on("click", function(e) {
					const editorPostSaveDraft = jQuery(".editor-post-save-draft");
					if(editorPostSaveDraft.length > 0) {
						editorPostSaveDraft.click();
					}
					const intervalId = setInterval(function() {
						// find out when the post is saved
						let saved = document.querySelector(".is-saved");
						if(saved) {
							clearInterval(intervalId);
							const win = window.open("' . get_preview_post_link() . '", "_blank");
							if (win) {
								win.focus();
							}
						}
					}, 50);
				});
			}
		}
	});
	</script>';
}
add_action('admin_footer-edit.php', 'fix_preview_link_on_draft'); // Fired on the page with the posts table
add_action('admin_footer-post.php', 'fix_preview_link_on_draft'); // Fired on post edit page
add_action('admin_footer-post-new.php', 'fix_preview_link_on_draft');
