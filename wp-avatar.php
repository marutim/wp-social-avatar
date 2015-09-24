<?php
/**
 * Plugin Name: WP Social Avatar
 * Plugin URI: http://www.sourcexpress.com/wp-social-avatar/
 * Description: This plugin gives the users the option to use their social profile picture as the WordPress Avatar
 * Version: 1.5
 * Author: Maruti Mohanty
 * Author URI: http://www.sourcexpress.com/
*/

/**
 * Enqueue wp-avatar js file to user-edit and profile page only.
 *
 * @param void
 *
 * @return void.
 */
function wp_avatar_enqueue_scripts() {
	global $pagenow;

	if ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow )
		wp_enqueue_script( 'wp-avatar.js', plugin_dir_url( __FILE__ ) . '/js/wp-avatar.js' );
}
add_action( 'admin_enqueue_scripts', 'wp_avatar_enqueue_scripts' );

/**
 * Add sub menu page to the Users or Profile menu.
 *
 * @param void
 *
 * @return string The resulting page.
 */
function wp_avatar_users_menu(){
	add_users_page( 'WP Avatar', 'WP Avatar', 'activate_plugins', 'wp-avatar', 'wp_avatar_admin' );
}
add_action( 'admin_menu', 'wp_avatar_users_menu' );

function wp_avatar_admin() {
	// Get the WP avatar capability value
	$wp_avatar_capability = get_option( 'wp_avatar_capability', 'read' );

	// WP Avatar settings section.
	$html  = '';
	$html .= '<form id="wp-avatar-settings" method="post" action="">';
	$html .= '<h3>WP Avatar Settings</h3>';
	$html .= '<table class="form-table">';
	$html .= '<tr><th><label for="wp-avatar-capabilty">Role Required</label></th>';
	$html .= '<td><select id="wp-avatar-capability" name="wp-avatar-capability">';
	$html .= '<option value="read"' . selected( $wp_avatar_capability, 'read', false ) . '>Subscriber</option>';
	$html .= '<option value="edit_posts"' . selected( $wp_avatar_capability, 'edit_posts', false ) . '>Contributor</option>';
	$html .= '<option value="edit_published_posts"' . selected( $wp_avatar_capability, 'edit_published_posts', false ) . '>Author</option>';
	$html .= '<option value="moderate_comments"' . selected( $wp_avatar_capability, 'moderate_comments', false ) . '>Editor</option>';
	$html .= '<option value="activate_plugins"' . selected( $wp_avatar_capability, 'activate_plugins', false ) . '>Administrator</option>';
	$html .= '</select></td></tr>';
	$html .= '</table>';
	$html .= '<p class="submit"><input type="submit" class="button button-primary" id="submit" value="Save Changes"></p>';
	$html .= '</form>';

	$html .= '<div id="postbox-container" class="postbox-container widefat fixed">';

	$html .= '<div class="postbox">';
	$html .= '<div class="handlediv"><br></div>';
	$html .= '<h3 class="hndle" style="text-align: center;">';
	$html .= '<span>Developer</span>';
	$html .= '</h3>';

	$html .= '<div class="inside">';
	$html .= '<div style="text-align: center; margin: auto">Made with lots of love by <br> <a href="http://www.sourcexpress.com/ target="_blank""><strong>Maruti Mohanty</strong></a></div>';
	$html .= '</div>';
	$html .= '</div>';

	$html .= '<div class="postbox">';
	$html .= '<div class="handlediv"><br></div>';
	$html .= '<h3 class="hndle" style="text-align: center;">';
	$html .= '<span>Support Plugin</span>';
	$html .= '</h3><div class="inside">';
	$html .= '<div style="text-align: center; margin: auto">';
	$html .= '<p>Is this plugin useful for you? If so, please help support its ongoing development and improvement with a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=marutimohanty89@gmail.com&item_name=WP%20Social%20Avatar" target="_blank">donation</a>.</p>';
	$html .= '<p>Or, if you are short on funds, there are other ways you can help out:</p>';
	$html .= '<ul>';
	$html .= '<li>Leave a positive review on the plugin\'s <a href="https://wordpress.org/support/view/plugin-reviews/wp-social-avatar" target="_blank">WordPress listing</a></li>';
	$html .= '<li>Vote "Works" on the plugin\'s <a href="https://wordpress.org/plugins/wp-social-avatar/#compatibility" target="_blank">WordPress listing</a></li>';
	$html .= '<li><a href="http://twitter.com/home?status=I%20love%20this%20WordPress%20plugin!%20http://wordpress.org/plugins/wp-social-avatar/ @sourcexpress" target="_blank">Share your thoughts on Twitter</a></li>';
	$html .= '</ul></div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';

	echo $html;
}

// Saving the WP Avatar settings details.
if ( isset( $_POST['wp-avatar-capability'] ) )
	update_option( 'wp_avatar_capability', $_POST['wp-avatar-capability'] );

/**
 * Adds the WP Avatar section in the user profile page.
 *
 * @param object $profileuser Contains the details of the current profile user
 *
 * @return string $html WP Avatar section in the user profile page
 */
function wp_avatar_add_extra_profile_fields( $profileuser ) {
	// Get the WP avatar capability value
	$wp_avatar_capability = get_option( 'wp_avatar_capability', 'read' );

	if ( ! current_user_can( $wp_avatar_capability ) )
		return;

	// Getting the usermeta
	$wp_avatar_profile = get_user_meta( $profileuser->ID, 'wp_avatar_profile', true );
	$wp_fb_profile     = get_user_meta( $profileuser->ID, 'wp_fb_profile', true );
	$wp_gplus_profile  = get_user_meta( $profileuser->ID, 'wp_gplus_profile', true );

	// WP Avatar section html in the user profile page.
	$html  = '';
	$html .= '<h3>' . apply_filters( 'wp_social_avatar_heading', 'WP Avatar Options' ) . '</h3>';
	$html .= '<table class="form-table">';
	$html .= '<tr><th><label for="facebook-profile">Facebook User ID(numeric)</label></th>';
	$html .= '<td><input type="text" name="fb-profile" id="fb-profile" value="' . $wp_fb_profile . '" class="regular-text" />&nbsp;&nbsp;';
	$html .= '<span><a href="http://findmyfacebookid.com/" target="_blank">Find your facebook id here</a></span></td>';
	$html .= '<tr><th><label for="use-fb-profile">Use Facebook Profile as Avatar</label></th>';
	$html .= '<td><input type="checkbox" name="wp-avatar-profile" value="wp-facebook" ' . checked( $wp_avatar_profile, 'wp-facebook', false ) . '></td></tr>';
	$html .= '<tr><th><label for="gplus-profile">Google+ id</label></th>';
	$html .= '<td><input type="text" name="gplus-profile" id="gplus-profile" value="' . $wp_gplus_profile . '" class="regular-text" /></td></tr>';
	$html .= '<tr><th><label for="use-gplus-profile">Use Google+ Profile as Avatar</label></th>';
	$html .= '<td><input type="checkbox" name="wp-avatar-profile" value="wp-gplus"' . checked( $wp_avatar_profile, 'wp-gplus', false ) . '></td></tr>';
	$html .= '<tr><th><label for="gplus-clear-cache">Clear Google+ Cache</label></th>';
	$html .= '<td><input type="button" name="wp-gplus-clear" value="Clear Cache" user="' . $profileuser->ID . '"><span id="msg"></span></td></tr>';
	$html .= '</table>';

	echo $html;
}
add_action( 'show_user_profile', 'wp_avatar_add_extra_profile_fields' );
add_action( 'edit_user_profile', 'wp_avatar_add_extra_profile_fields' );

/**
 * Saving the WP Avatar details in the wp usermeta table.
 *
 * @param int $user_id id of the current user.
 *
 * @return void
 */
function wp_avatar_save_extra_profile_fields( $user_id ) {
	// Saving the WP Avatar details.
	update_user_meta( $user_id, 'wp_fb_profile', trim( $_POST['fb-profile'] ) );
	update_user_meta( $user_id, 'wp_gplus_profile', trim( $_POST['gplus-profile'] ) );
	update_user_meta( $user_id, 'wp_avatar_profile', $_POST['wp-avatar-profile'] );
}
add_action( 'personal_options_update', 'wp_avatar_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'wp_avatar_save_extra_profile_fields' );

/**
 * Replaces the default engravatar with the Facebook profile picture.
 *
 * @param string $avatar The default avatar
 *
 * @param int $id_or_email The user id
 *
 * @param int $size The size of the avatar
 *
 * @param string $default The url of the Wordpress default avatar
 *
 * @param string $alt Alternate text for the avatar.
 *
 * @return string $avatar The modified avatar
 */
function wp_fb_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	// Getting the user id.
	if ( is_int( $id_or_email ) )
		$user_id = $id_or_email;

	if ( is_object( $id_or_email ) )
		$user_id = $id_or_email->user_id;

	if ( is_string( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
		if ( $user )
			$user_id = $user->ID;
		else
			$user_id = $id_or_email;
	}

	// Getting the user details
	$wp_avatar_profile    = get_user_meta( $user_id, 'wp_avatar_profile', true );
	$wp_fb_profile        = get_user_meta( $user_id, 'wp_fb_profile', true );
	$wp_avatar_capability = get_option( 'wp_avatar_capability', 'read' );

	if ( user_can( $user_id, $wp_avatar_capability ) ) {
		if ( 'wp-facebook' == $wp_avatar_profile && ! empty( $wp_fb_profile ) ) {

			$fb     = 'https://graph.facebook.com/' . $wp_fb_profile . '/picture?width='. $size . '&height=' . $size;
			$avatar = "<img alt='facebook-profile-picture' src='{$fb}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

			return $avatar;
		} else {
			return $avatar;
		}
	} else {
		return $avatar;
	}
}
add_filter( 'get_avatar', 'wp_fb_avatar', 10, 5 );

/**
 * Replaces the default engravatar with the Twitter profile picture
 *
 * @param string $avatar The default avatar
 *
 * @param int $id_or_email The user id
 *
 * @param int $size The size of the avatar
 *
 * @param string $default The url of the Wordpress default avatar
 *
 * @param string $alt Alternate text for the avatar.
 *
 * @return string $avatar The modified avatar
 */
function wp_gplus_avatar( $avatar, $id_or_email, $size, $default, $alt ){
	// Getting the user id.
	if ( is_int( $id_or_email ) )
		$user_id = $id_or_email;

	if ( is_object( $id_or_email ) )
		$user_id = $id_or_email->user_id;

	if ( is_string( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
		if ( $user )
			$user_id = $user->ID;
		else
			$user_id = $id_or_email;
	}

	// Getting the user details
	$wp_avatar_profile    = get_user_meta( $user_id, 'wp_avatar_profile', true );
	$wp_gplus_profile     = get_user_meta( $user_id, 'wp_gplus_profile', true );
	$wp_avatar_capability = get_option( 'wp_avatar_capability', 'read' );

	if ( user_can( $user_id, $wp_avatar_capability ) ) {
		if ( 'wp-gplus' == $wp_avatar_profile && ! empty( $wp_gplus_profile ) ) {
			if ( false === ( $gplus = get_transient( "wp_social_avatar_gplus_{$user_id}" ) ) ) {
				$url = 'https://www.googleapis.com/plus/v1/people/' . $wp_gplus_profile . '?fields=image&key=AIzaSyBrLkua-XeZh637G1T1J8DoNHK3Oqw81ao';
				// Fetching the Gplus profile image.
				$results = wp_remote_get( $url, array( 'timeout' => -1 ) );

				// Checking for WP Errors
				if ( ! is_wp_error( $results ) ) {
					if ( 200 == $results['response']['code'] ) {
						$gplusdetails = json_decode( $results['body'] );
						$gplus        = $gplusdetails->image->url;

						// Setting Gplus url for 48 Hours
						set_transient( "wp_social_avatar_gplus_{$user_id}", $gplus, 48 * HOUR_IN_SECONDS );

						// Replacing it with the required size
						$gplus = str_replace( 'sz=50', "sz={$size}", $gplus );

						$avatar = "<img alt='gplus-profile-picture' src='{$gplus}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
					}
				}
			} else {
				// Replacing Gplus url with the required size
				$gplus = str_replace( 'sz=50', "sz={$size}", $gplus );

				$avatar = "<img alt='gplus-profile-picture' src='{$gplus}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			}
			return $avatar;
		} else {
			return $avatar;
		}
	} else {
		return $avatar;
	}
}
add_filter( 'get_avatar', 'wp_gplus_avatar', 10, 5 );

/**
 * Deletes the transient for a Google Plus for the respective user
 *
 * @param void
 *
 * @return boolean $delete_transient True if the transients gets deleted
 */
function wp_social_avatar_gplus_clear_cache() {
	// Fetch the current user id
	$user_id = sanitize_text_field( $_POST['user_id'] );

	// Delete transient for the particular user
	$delete_transient = delete_transient( "wp_social_avatar_gplus_{$user_id}" );

	echo $delete_transient;
	die();
}
add_action( 'wp_ajax_wp_social_avatar_gplus_clear_cache', 'wp_social_avatar_gplus_clear_cache' );
add_action( 'wp_ajax_nopriv_wp_social_avatar_gplus_clear_cache', 'wp_social_avatar_gplus_clear_cache' );
