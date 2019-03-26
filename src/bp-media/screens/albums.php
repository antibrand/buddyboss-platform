<?php
/**
 * Media: Single album screen handler
 *
 * @package BuddyBoss\Media\Screens
 * @since BuddyBoss 1.0.0
 */

/**
 * Load an individual album screen.
 *
 * @since BuddyBoss 1.0.0
 *
 * @return false|null False on failure.
 */
function media_screen_single_album() {

	// Bail if not viewing a single album.
	if ( ! bp_is_media_component() || ! bp_is_current_action( 'albums' ) || ! (int) bp_action_variable( 0 ) ) {
		return false;
	}

	$album_id = (int) bp_action_variable( 0 );

	if ( empty( $album_id ) ) {
		if ( is_user_logged_in() ) {
			bp_core_add_message( __( 'The album you tried to access is no longer available', 'buddyboss' ), 'error' );
		}

		bp_core_redirect( trailingslashit( bp_displayed_user_domain() . bp_get_media_slug() . '/albums' ) );
	}

	// No access.
//	if ( ( ! messages_check_thread_access( $thread_id ) || ! bp_is_my_profile() ) && ! bp_current_user_can( 'bp_moderate' ) ) {
//		// If not logged in, prompt for login.
//		if ( ! is_user_logged_in() ) {
//			bp_core_no_access();
//			return;
//
//		// Redirect away.
//		} else {
//			bp_core_add_message( __( 'You do not have access to that conversation.', 'buddyboss' ), 'error' );
//			bp_core_redirect( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() ) );
//		}
//	}

	// Load up BuddyPress one time.
	$bp = buddypress();

	// Decrease the unread count in the nav before it's rendered.
	$count    = 0;
//	$count    = bp_get_total_unread_messages_count();
	$class    = ( 0 === $count ) ? 'no-count' : 'count';
	$nav_name = sprintf( __( 'Album <span class="%s">%s</span>', 'buddyboss' ), esc_attr( $class ), bp_core_number_format( $count ) );

	// Edit the Navigation name.
	$bp->members->nav->edit_nav( array(
		'name' => $nav_name,
	), $bp->media->slug );

	/**
	 * Fires right before the loading of the single album view screen template file.
	 *
	 * @since BuddyBoss 1.0.0
	 */
	do_action( 'media_screen_single_album' );

	/**
	 * Filters the template to load for the Single Album view screen.
	 *
	 * @since BuddyBoss 1.0.0
	 *
	 * @param string $template Path to the album template to load.
	 */
	bp_core_load_template( apply_filters( 'media_template_single_album', 'members/single/home' ) );
}
add_action( 'bp_screens', 'media_screen_single_album' );