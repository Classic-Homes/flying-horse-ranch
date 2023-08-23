<?php

/**
 *	Cloudflare Turnstile Integration for Login Page
 */

// Add CF Turnstile JavaScript on login page
function wpp_login_script() {
    wp_register_script('login-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', false, NULL);
    wp_enqueue_script('login-turnstile');
}
add_action('login_enqueue_scripts', 'wpp_login_script');
// Add CF Turnstile on login page
function add_turnstile_on_login_page() {
    echo '<div class="cf-turnstile" data-sitekey="0x4AAAAAAAJKnnX9mlY_pFPC"></div>';
}
add_action('login_form','add_turnstile_on_login_page');
// Validating CF Turnstile on login page
function turnstile_login_check($user, $password) {
$postdata =$_POST['cf-turnstile-response'];
   //add secret key
    $secret = '0x4AAAAAAAJKnrugQdI7inl0BJwI0jghelE';
    $headers = array(
        'body' => [
            'secret' => $secret,
            'response' => $postdata
        ]
    );
    $verify = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $headers);
    $verify = wp_remote_retrieve_body($verify);
    $response = json_decode($verify);
    if($response->success) {
        $results['success'] = $response->success;
    } else {
        $results['success'] = false;
    }
    if(empty($postdata)){ 
        wp_die(__("<b>ERROR: </b><b>Please click the challenge checkbox.</b><p><a href='javascript:history.back()'>« Back</a></p>")); 
    } elseif(!$results['success']){ 
        wp_die(__("<b>Sorry, spam detected!</b>")); 
    } else {
        return $user;
    }   
}
add_action('wp_authenticate_user', 'turnstile_login_check', 10, 2);