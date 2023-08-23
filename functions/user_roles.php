<?php

/**
 * User Roles
 */
// Redirection Plugin Staff Access
add_filter('redirection_role', 'redirection_to_staff');
function redirection_to_staff()
{
    return 'edit_pages';
}