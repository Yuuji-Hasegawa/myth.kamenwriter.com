<?php

function get_popular()
{
    $output = '';
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 4,
        'orderby' => 'meta_value',
        'order' => 'DESC',
        'meta_key' => 'post_views_count',
        'no_found_rows' => true
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {
        $output = '<h2 class="c-heading">Popular<span class="c-heading__font-small">人気のお話</span></h2><div class="o-switcher o-switcher:twoQuart">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $output .= '<a href="' . get_the_permalink() . '" class="o-stack o-stack:memoItem">' . get_thumb() . '<span class="c-clip-title">' . get_the_title() . '</span><time class="c-time" datetime="' . get_the_time('Y-m-d') . '">' . my_human_time_diff(get_post_time('U', true)) . '前</time></a>';
        }
        $output .= '</div>';
        wp_reset_postdata();
    }
    if ($output) {
        return $output;
    }
}
function get_side_loop()
{
    $output = '';
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {
        $output = '<h2 class="c-side-heading">最近収集したお話</h2><div class="o-stack o-stack:sidebar">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $output .= '<a href="' . get_the_permalink() . '" class="o-grid o-grid:sidebar"><span>' . get_thumb() . '</span><dl class="o-stack o-stack:sideInner"><dt class="c-sideInner-title">' . get_the_title() . '</dt><dd class="c-sideInner-content"><time class="c-time" datetime="' . get_the_time('Y-m-d') . '">' . my_human_time_diff(get_post_time('U', true)) . '前</time></dd></dl></a>';
        }
        $output .= '</div>';
        wp_reset_postdata();
    }
    if ($output) {
        return $output;
    }
}
function get_reviewing_loop()
{
    $output = '';
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key'     => 'memo_status',
                'compare' => 'NOT EXISTS'
                )
            ),
        'no_found_rows' => true
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {
        $output = '<h2 class="c-side-heading">作業中のお話</h2><div class="o-stack o-stack:sidebar">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $output .= '<a href="' . get_the_permalink() . '" class="o-grid o-grid:sidebar"><span>' . get_thumb() . '</span><dl class="o-stack o-stack:sideInner"><dt class="c-sideInner-title">' . get_the_title() . '</dt><dd class="c-sideInner-content"><time class="c-time" datetime="' . get_the_time('Y-m-d') . '">' . my_human_time_diff(get_post_time('U', true)) . '前</time></dd></dl></a>';
        }
        $output .= '</div>';
        wp_reset_postdata();
    }
    if ($output) {
        return $output;
    }
}
