<?php
namespace Idearia\WP_Redirect_Website_To_Url;
/*
 * Plugin Name: Redirect Website to URL
 * Plugin URI: https://github.com/Idearia/wp-redirect-website-to-url
 * Version: 0.1
 * Description: Redirect all pages and posts in the website frontend to a single hard-coded URL; uses template_redirect as suggested in https://wordpress.stackexchange.com/a/76807/86662
 * Author: Idearia Srl
 * Author URI: https://www.idearia.it
 * Requires at least: 3.0.0
 *
 * Text Domain: wp-redirect-website-to-url
 * Domain Path: /languages/
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @author Idearia Srl
 */

/**
 * Print debug information to debug.log?
 */
define( __NAMESPACE__ . "\\DEBUG", false );

/**
 * Redirection URL
 */
define( __NAMESPACE__ . "\\DESTINATION_URL", "https://www.google.com" );

/**
 * If the redirection URL is a WordPress page or post, specify here its WordPress ID
 */
define( __NAMESPACE__ . "\\DESTINATION_URL_ID", "" );

/**
 * Users with this capability won't be redirected; leave blank to redirect everybody.
 */
define( __NAMESPACE__ . "\\USER_CAPABILITY", "manage_options" );

/**
 * Redirection status: 302 for temporary redirect, 301 for permanent redirect.
 */
define( __NAMESPACE__ . "\\REDIRECT_STATUS_CODE", "302" );


// =========
// = HOOKS =
// =========

add_action( 'template_redirect', __NAMESPACE__ . '\\wp_redirect_website_to_url' );


// ==================
// = HOOK FUNCTIONS =
// ==================

/**
 * Redirect all pages/posts to a URL
 */
function wp_redirect_website_to_url() {

	/* Debug */
	DEBUG && error_log( "wp_redirect_website_to_url: USER_CAPABILITY = " . USER_CAPABILITY );
	DEBUG && error_log( "wp_redirect_website_to_url: DESTINATION_URL = " . DESTINATION_URL );
	DEBUG && error_log( "wp_redirect_website_to_url: current_user_can(USER_CAPABILITY) = " . var_export( current_user_can( USER_CAPABILITY ), true ) );
	if ( DESTINATION_URL_ID ) {
		DEBUG && error_log( "wp_redirect_website_to_url: is_single(DESTINATION_URL_ID) = " . var_export( is_single( DESTINATION_URL_ID ), true ) );
		DEBUG && error_log( "wp_redirect_website_to_url: is_page(DESTINATION_URL_ID) = " . var_export( is_page( DESTINATION_URL_ID ), true ) );
	}

	/* Redirect users based on their capability */
	if ( empty( USER_CAPABILITY ) || ! current_user_can( USER_CAPABILITY ) ) {
		if ( ! is_single( DESTINATION_URL_ID ) && ! is_page( DESTINATION_URL_ID ) ) {
			DEBUG && error_log( "wp_redirect_website_to_url: redirecting user to " . DESTINATION_URL );
			wp_redirect( esc_url_raw( DESTINATION_URL ), REDIRECT_STATUS_CODE );
			exit;
		}
	}

}
