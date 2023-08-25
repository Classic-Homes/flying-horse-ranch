<?php

/**
 * Remove query strings from static resources
 */
function remove_cssjs_ver($src)
{
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'remove_cssjs_ver', 10, 2);
add_filter('script_loader_src', 'remove_cssjs_ver', 10, 2);


/**
 * Disable External Embeds
 */
function disable_embed()
{
    wp_dequeue_script('wp-embed');
}
add_action('wp_footer', 'disable_embed');


/**
 * Remove file versioning
 */
function _remove_script_version($src)
{
    $parts = explode('?ver', $src);
    return $parts[0];
}
add_filter('script_loader_src', '_remove_script_version', 15, 1);
add_filter('style_loader_src', '_remove_script_version', 15, 1);


/**
 * Disable Self Pingbacks
 */
function disable_pingback(&$links)
{
    foreach ($links as $l => $link)
        if (0 === strpos($link, get_option('home')))
            unset($links[$l]);
}
add_action('pre_ping', 'disable_pingback');

/**
 * Remove unneeded stuff from the header
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10);
remove_action('wp_head', 'parent_post_rel_link', 10);
remove_action('wp_head', 'adjacent_posts_rel_link', 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Hide WordPress Version
remove_action('wp_head', 'wp_generator');

// Remove WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');


/**
 * Comment Changes
 */
// Remove Website Field from Comments
add_filter('comment_form_default_fields', 'website_remove');
function website_remove($fields)
{
    if (isset($fields['url']))
        unset($fields['url']);
    return $fields;
}

// Remove Comment Links
remove_filter('comment_text', 'make_clickable', 9);

// Remove Comment Author Link
add_filter('comment_form_field_url', '__return_false');
