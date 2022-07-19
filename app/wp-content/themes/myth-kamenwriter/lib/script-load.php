<?php

add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');

function dequeue_plugins_style()
{
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('global-styles');
    }
}
add_action('wp_enqueue_scripts', 'dequeue_plugins_style', 9999);
function my_script_styles()
{
    wp_register_script('themeApp', get_template_directory_uri() . '/js/lib.js', array(), '1.0.0', true);
    wp_enqueue_script('themeApp');
    wp_register_style('themeCss', false);
    wp_enqueue_style('themeCss');
    $css = file_get_contents(get_template_directory_uri() . '/css/style.css', true);
    $css = str_replace('url("../fonts', 'url("' . get_template_directory_uri() .'/fonts', $css);
    wp_add_inline_style('themeCss', $css);
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'my_script_styles');
add_filter('script_loader_tag', 'add_async', 10, 2);

function add_async($tag, $handle)
{
    if ($handle !== 'themeApp') {
        return $tag;
    }

    $jsonLD = '<script type="application/ld+json">';
    if (is_single()) {
        $jsonLD .= '[' . json_encode(set_bread_json()) . ',' . json_encode(set_content_json()) . ']';
    } else {
        $jsonLD .= json_encode(set_bread_json());
    }
    $jsonLD .= '</script>';
    $homeurl = home_url();
    $pwascripts = <<< EOF
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('{$homeurl}/sw.js').then(function(registration) {
            console.log('serviceWorker registed.', registration.scope);
            }, function(err) {
            console.log('serviceWorker error.', err);
            });
        });
    }
    </script>
    EOF;
    $replace = '<script> var path ="' . get_template_directory_uri() . '";</script>' . $tag . $pwascripts . $jsonLD;
    return str_replace(' src=', ' defer src=', $replace);
}
