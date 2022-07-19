<?php

function set_bread_json()
{
    global $post;
    $array[] = array(
        "@type" => "ListItem",
        "position" => 1,
        "item" => array(
            "@id" => esc_url(home_url('/')),
            "name" => esc_attr("トップページ")
        )
    );
    if (is_404()) {
        $notfound[] = array(
            "@type" => "ListItem",
            "position" => 2,
            "item" => array(
                "@id" => esc_url(get_pagenum_link()),
                "name" => esc_html('ページが見つかりません。') . ' - ' . esc_html(get_bloginfo('name'))
            )
        );
        $array = array_merge($array, $notfound);
    } elseif (is_search()) {
        $search[] = array(
            "@type" => "ListItem",
            "position" => 2,
            "item" => array(
                "@id" => esc_url(home_url('/?s=') . get_search_query()),
                "name" => esc_html('"' . get_search_query() . '"の検索結果')
            )
        );
        $array = array_merge($array, $search);
    } elseif (is_archive()) {
        if (is_tag()) {
            $parent[] = array(
                "@type" => "ListItem",
                "position" => 2,
                "item" => array(
                    "@id" => esc_url(home_url('/tale')),
                    "name" => esc_attr('お話')
                )
            );
            $child[] = array(
                "@type" => "ListItem",
                "position" => 3,
                "item" => array(
                    "@id" => esc_url(get_term_link(get_queried_object_id($post->ID))),
                    "name" => esc_attr(single_term_title('#', false))
                )
            );
            $array = array_merge($array, $parent, $child);
        } else {
            $parent[] = array(
                "@type" => "ListItem",
                "position" => 2,
                "item" => array(
                    "@id" => esc_url(home_url('/tale')),
                    "name" => esc_attr('お話')
                )
            );
            $array = array_merge($array, $parent);
        }
    } elseif (is_single()) {
        if (is_attachment()) {
            $attachment[] = array(
                "@type" => "ListItem",
                "position" => 2,
                "item" => array(
                    "@id" => esc_url(get_permalink($post->ID)),
                    "name" => esc_attr('添付ファイルのページ')
                )
            );
            $array = array_merge($array, $attachment);
        } else {
            $parent[] = array(
                "@type" => "ListItem",
                "position" => 2,
                "item" => array(
                    "@id" => esc_url(home_url('/tale')),
                    "name" => esc_attr('お話')
                )
            );
            $single[] = array(
                "@type" => "ListItem",
                "position" => 3,
                "item" => array(
                    "@id" => esc_url(get_permalink($post->ID)),
                    "name" => esc_html(get_the_title($post->ID))
                )
            );
            $array = array_merge($array, $parent, $single);
        }
    }
    if ($array) {
        $bread_array = array(
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $array
        );
        return $bread_array;
    }
}
function set_content_json()
{
    global $post;
    $img_url = '';
    $img_w = '';
    $img_h = '';
    $cat_name = '';
    if (has_post_thumbnail()) {
        $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        $img_url = $img_src[0];
        $img_w = $img_src[1];
        $img_h = $img_src[2];
    } else {
        $img_url = get_template_directory_uri() .'/img/ogp.png';
        $img_w = '1200';
        $img_h = '630';
    }
    $cat_name = 'お話';
    $array = array(
        "@context" => "http://schema.org",
        "@type" => "NewsArticle",
        "mainEntityOfPage" => array(
            "@type" => "WebPage",
            "@id" => esc_url(get_permalink($post->ID))
        ),
        "name" => esc_html(get_the_title($post->ID)),
        "headline" => esc_html(get_the_title($post->ID)),
        "image" => array(
            array(
                "@type" => "ImageObject",
                "url" => esc_url($img_url),
                "width" => $img_w,
                "height" => $img_h
            )
        ),
        "articleSection" => esc_html($cat_name),
        "datePublished" => get_the_time('c'),
        "dateModified" => get_the_modified_time('c'),
        "author" => array(
            "@type" => "Person",
            "name" => esc_attr('長谷川 雄治'),
            "sameAs" => ["https://www.facebook.com/yuuji.hasegawa","https://twitter.com/kamenwriter01","https://www.instagram.com/kamenwriter/","https://note.com/kamenwriter"]
        ),
        "publisher" => array(
            "@type" => "Organization",
            "name" => esc_attr('仮面ライター'),
            "logo" => array(
                "@type" => "ImageObject",
                "url" => esc_url(get_template_directory_uri() . '/img/svg/logo.svg'),
                "width" => 25,
                "height" => 32
            ),
            "sameAs" => ["https://kamenwriter.com", "https://www.facebook.com/kamenwriter01","https://twitter.com/kamenwriter02"]
        ),
        "description" => my_description()
    );
    if ($array) {
        return $array;
    }
}
