<?php get_header();
if (is_front_page()) {
    echo get_popular();
    echo '<h2 class="c-heading">New Arrival<span class="c-heading__font-small">新しく収集したお話</span></h2>';
}
?>
<?php if (have_posts()):?>
<?php if (is_search()) {
    echo '<p class="c-search-result">' . found_result_count() . '</p>';
}?>
<div class="o-switcher o-switcher:twoQuart">
    <?php while (have_posts()): the_post();?>
    <a href="<?php the_permalink();?>"
        class="o-stack o-stack:memoItem">
        <?php echo get_thumb();?>
        <span class="c-clip-title"><?php the_title();?></span>
        <time class="c-time"
            datetime="<?php the_time('Y-m-d')?>">
            <?php echo my_human_time_diff(get_post_time('U', true));?>前
        </time>
    </a>
    <?php endwhile;?>
</div>
<?php else:?>
<?php
    if (is_search()) {
        echo '<p>"' . get_search_query() . '"でヒットするお話は見つかりませんでした。</p>';
        echo '<a href="' . home_url() . '" class="c-btn c-btn:goHome">トップページへ戻る</a>';
    }
endif;
echo get_pagination();get_footer();
