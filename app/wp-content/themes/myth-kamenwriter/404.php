<?php get_header(); $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";?>
<div class="c-content-wrap">
    <p>
        お探しのページは見つかりませんでした。
        <br />
        お探しのページ (URL: <?php echo esc_url($protocol. $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); ?>)は、
    </p>
    <ul class="c-list c-list:404">
        <li>すでに削除されている</li>
        <li>公開期間が終わっている</li>
        <li>アクセスしたアドレスが異なっている</li>
    </ul>
    <p>
        などの理由で見つかりませんでした。
        <br />
        引き続き、このサイトをご利用いただく場合は、
    </p>
    <ul class="c-list c-list:404">
        <li><a href="<?php echo home_url();?>">トップページ</a>から探す</li>
        <li>検索し直す</li>
    </ul>
    <p>など、ご利用ください。</p>
</div>
<?php get_footer();
