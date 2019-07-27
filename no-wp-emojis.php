<?php
/**
 * No WP Emojis
 *
 * @package     DmB
 * @author      Dmitri Bostan
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: No WP Emojis
 * Plugin URI:  https://github.com/dmbostan/no-wp-emojis
 * Description: A helper plugin to remove emojis related assets and hooks in WordPress.
 * Version:     1.0.0
 * Author:      Dmitri Bostan
 * Author URI:  https://github,com/dmbostan
 * Text Domain: no-wp-emojis
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


/**
 * Disable the emoji's
 */
function no_wp_emojis() {

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', 'no_wp_emojis_in_tinymce' );
	add_filter( 'wp_resource_hints', 'no_wp_emojis_in_dns_prefetch', 10, 2 );
}
add_action( 'init', 'no_wp_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function no_wp_emojis_in_tinymce( $tinymce_plugins ) {

	if ( is_array( $tinymce_plugins ) ) {
		$plugins = array_diff( $tinymce_plugins, array( 'wpemoji' ) );
	} else {
		$plugins = array();
	}

	return $plugins;
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function no_wp_emojis_in_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}