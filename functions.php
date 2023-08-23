<?php
//
// Recommended way to include parent theme styles.
//  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
//
// Your code goes below
//
// Optimizations and Disabled Elements
require_once(__DIR__ . '/functions/optimizations.php');

// User Roles and Capabilities
require_once(__DIR__ . '/functions/user_roles.php');

// Cloudflare Turnstile Integration
require_once(__DIR__ . '/functions/turnstile.php');

// Divi Theme Specific Functions
require_once(__DIR__ . '/functions/divi_tweaks.php');
