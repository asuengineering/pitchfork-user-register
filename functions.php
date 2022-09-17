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
// require get_stylesheet_directory() . '/inc/custom-post-types.php';
// require get_stylesheet_directory() . '/inc/acf-register.php';


// Populate Gravity Forms with list of sites in the multisite.
add_filter( 'gform_pre_render', 'pitchfork_build_site_list_select' );
add_filter( 'gform_pre_validation', 'pitchfork_build_site_list_select' );
add_filter( 'gform_pre_submission_filter', 'pitchfork_build_site_list_select' );
add_filter( 'gform_admin_pre_render', 'pitchfork_build_site_list_select' );
function pitchfork_build_site_list_select( $form ) {
 
    foreach ( $form['fields'] as &$field ) {
 
        if ( strpos( $field->cssClass, 'list-multisite-properties' ) === false ) {
            continue;
        }
		
		$choices = array();

		$args = array(
			'number' => 400,
			'orderby' => 'domain',
			'public' => 1,
			'archived' => 0,
			'mature' => 0,
			'spam' => 0,
			'deleted' => 0
		);

		$subsites = get_sites($args);
		// do_action('qm/debug', $subsites);

		// Filter the resulting list to only exclude domains that are not mapped.
		// The thinking there is to exclusively manually add people to websites that are in development. 

		foreach ($subsites as $subsite) {
			$fullsite = $subsite->domain . $subsite->path;
			$choices[] = array( 'text' => $fullsite, 'value' => $subsite->blog_id );
		}
		
        // update 'Select a Post' to whatever you'd like the instructive option to be
        $field->placeholder = 'Select a site.';
        $field->choices = $choices;
 
    }
 
    return $form;
}



// Once the entry is approved, add the user to the requested site.
add_action( 'gform_approvals_entry_approved', 'pitchfork_approved_for_user_assignment', 10, 3 );
function pitchfork_approved_for_user_assignment( $entry, $form ) {

	// Iterate through the "fields" array in $form to find the correct field for the desired site ID.
	// Handles cases when the field id that contains the site ID changes across different forms.
	$site_id_field = '';
	foreach ( $form['fields'] as $field ) {
		if ( $field['adminLabel'] == 'requested_site' ) {
			$site_id_field = $field['id'];
		}
	}

	// Get the value from the site id field above.
	$siteid = $entry[$site_id_field];

	// Add the user to the correct site. Hard coded to admin role currently.
	add_user_to_blog( $siteid, $entry['created_by'], 'administrator' );

}


// Shortcode to display current logged in user.
// Also updates the user's details from the ASU Search API while they are here.
function pitchfork_show_loggedin_user( $atts ) {

	global $user_login;
    $current_user = wp_get_current_user();

	$userwelcome = '';
	$userwelcome .= '<div class="user-profile"><img class="user_avatar" src="' . esc_url( get_avatar_url( $current_user->ID ) ) . '" />';
	$userwelcome .= '<p><strong>' . $current_user->display_name . '</strong><br/>ASURite: ' . $current_user->user_login . '</p></div>';

	if ( $user_login ) {
		return $userwelcome;
	} else {
		return '<a class="btn btn-maroon mb-4" href="/wp-login.php">Register as a user</a>';
	}
	
}
add_shortcode( 'show_loggedin_user', 'pitchfork_show_loggedin_user' );
