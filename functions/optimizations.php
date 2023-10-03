<?php

/**
 * Disable External Embeds
 */
function disable_embed()
{
    wp_dequeue_script('wp-embed');
}
add_action('wp_footer', 'disable_embed');


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
 * Disable Comments
 */
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
