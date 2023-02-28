<?php

function get_thumb()
{
    global $post;
    $output = '<picture class="o-frame">';
    if (has_post_thumbnail()) {
        $output .= '<img src="' . get_the_post_thumbnail_url($post->ID, 'full') . '" loading="lazy" decoding="async" alt="" width="100%" height="100%" />';
    } else {
        $output .= '
            <source srcset="' . get_template_directory_uri() . '/img/thumb.avif" type="image/avif" />
                    <source srcset="' . get_template_directory_uri() . '/img/thumb.webp" type="image/webp" />
                    <img src="' . get_template_directory_uri() . '/img/thumb.png" loading="lazy" decoding="async" alt="" width="100%" height="100%" />';
    }
    $output .= '</picture>';
    return $output;
}
function get_ogp_type()
{
    is_single() ? $og_type = 'article' : $og_type = 'website';
    return $og_type;
}
/* shortcode */
function shortcode_url()
{
    return home_url();
}
add_shortcode('url', 'shortcode_url');

function shortcode_templateurl()
{
    return get_template_directory_uri();
}
add_shortcode('template_url', 'shortcode_templateurl');

function get_post_tags()
{
    global $post;
    $output = "";
    $tags = wp_get_object_terms($post->ID, 'post_tag');
    if ($tags && ! is_wp_error($tags)) {
        $output = '<div class="o-cluster">';
        foreach ($tags as $tagname) {
            $output .= '<a href="' . get_term_link($tagname) . '" rel="tag" class="c-tag-link">' . $tagname->name . '</a>';
        }
        $output .= '</div>';
    }
    if ($output) {
        return $output;
    }
}
function get_side_tags()
{
    global $post;
    $output = "";
    $tags = wp_get_object_terms($post->ID, 'post_tag');
    if ($tags && ! is_wp_error($tags)) {
        $output = '<h2 class="c-side-heading">タグ一覧</h2><div class="o-cluster o-cluster:sideTag">';
        foreach ($tags as $tagname) {
            $output .= '<a href="' . get_term_link($tagname) . '" rel="tag" class="c-tag-link">' . $tagname->name . '</a>';
        }
        $output .= '</div>';
    }
    if ($output) {
        return $output;
    }
}
function my_human_time_diff($from, $to = '')
{
    if (empty($to)) {
        $to = time();
    }
    $diff = (int) abs($to - $from);
    // 条件: 3600秒 = 1時間以下なら (元のまま)
    if ($diff <= 3600) {
        $mins = round($diff / 60);
        if ($mins <= 1) {
            $mins = 1;
        }
        $since = sprintf(_n('%s min', '%s mins', $mins), $mins);
    }
    // 条件: 86400秒 = 24時間以下かつ、3600秒 = 1時間以上なら (元のまま)
    elseif (($diff <= 86400) && ($diff > 3600)) {
        $hours = round($diff / 3600);
        if ($hours <= 1) {
            $hours = 1;
        }
        $since = sprintf(_n('%s hour', '%s hours', $hours), $hours);
    }
    // 条件: 604800秒 = 7日以下かつ、86400秒 = 24時間以上なら (条件追加)
    elseif (($diff <= 604800) && ($diff > 86400)) {
        $days = round($diff / 86400);
        if ($days <= 1) {
            $days = 1;
        }
        $since = sprintf(_n('%s day', '%s days', $days), $days);
    }
    // 条件: 2678400秒 = 31日以下かつ、2678400秒 = 7日以上なら (条件追加)
    elseif (($diff <= 2678400) && ($diff > 604800)) {
        $weeks = round($diff / 604800);
        if ($weeks <= 1) {
            $weeks = 1;
        }
        $since = sprintf(_n('%s週間', '%s週間', $weeks), $weeks);
    }
    // 条件: 31536000秒 = 365日以下かつ、2678400秒 = 31日以上なら (条件追加)
    elseif (($diff <= 31536000) && ($diff > 2678400)) {
        $months = round($diff / 2678400);
        if ($months <= 1) {
            $months = 1;
        }
        $since = sprintf(_n('約%sヶ月', '約%sヶ月', $months), $months);
    }
    // 条件: 31536000秒 = 365日以上なら (条件追加)
    elseif ($diff >= 31536000) {
        $years = round($diff / 31536000);
        if ($years <= 1) {
            $years = 1;
        }
        $since = sprintf(_n('約%s年', '約%s年', $years), $years);
    }
    return $since;
}

function get_vars($parent = '', $child = '')
{
    $json = file_get_contents(get_template_directory() . '/lib/setting.json');
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $arr = json_decode($json, true);
    $output = $arr[$parent][$child];
    if ($output) {
        return $output;
    }
}
function found_result_count()
{
    global $wp_query;
    $output = '';
    $paged = get_query_var('paged') - 1;
    $posts_per_page = get_query_var('posts_per_page');
    $count = $total = $wp_query->post_count;
    $from = 0;
    if (0 < $posts_per_page) {
        $total = $wp_query->found_posts;
        if (0 < $paged) {
            $from = $paged * $posts_per_page;
        }
    }
    $output = $total . '件中 / ';
    $output .= (1 < $count ? ($from + 1 . '件 ~ ') : '');
    $output .= $from + $count . '件目を表示';
    if ($output) {
        return $output;
    }
}
