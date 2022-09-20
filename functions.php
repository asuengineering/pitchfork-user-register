<?php
/**
 * Pitchfork child theme functions
 *
 * @package pitchfork-child
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_stylesheet_directory() . '/inc/enqueue-assets.php';
require get_stylesheet_directory() . '/inc/gravityforms.php';


// Shortcode to display current logged in user.
// Also updates the user's details from the ASU Search API while they are here.
function pitchfork_show_loggedin_user( $atts ) {

	global $user_login;
    $current_user = wp_get_current_user();

	$userwelcome = '';
	$userwelcome .= '<div class="user-profile"><img class="user_avatar" src="' . esc_url( get_avatar_url( $current_user->ID, ['size' => '150'] ) ) . '" />';
	$userwelcome .= '<p><strong>' . $current_user->display_name . '</strong><br/>ASURite: ' . $current_user->user_login . '</p></div>';

	if ( $user_login ) {
		return $userwelcome;
	} else {
		return '<a class="btn btn-maroon mb-4" href="/wp-login.php">Register as a user</a>';
	}
	
}
add_shortcode( 'show_loggedin_user', 'pitchfork_show_loggedin_user' );

// Redirect non-logged in users away from the request form page.
// Targets a page with the slug of "request".
add_action( 'template_redirect', 'pitchfork_redirect_away_from_request_form' );
function pitchfork_redirect_away_from_request_form() {
	if ( is_page('request') && ! is_user_logged_in() ) {
		wp_redirect( home_url(), 301 ); 
  		exit;
    }
}

// Redirect users who just logged in back to the request form.
// Request form should be a page with the slug of "request"
add_filter('login_redirect', 'pitchfork_custom_login_redirect');
function pitchfork_custom_login_redirect() {
	return '/request';
}
	
	