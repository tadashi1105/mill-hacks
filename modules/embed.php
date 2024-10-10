<?php
/**
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

/**
 * Load the Responsive videos plugin
 *
 * @since Mill 1.0.0
 */
add_action( 'after_setup_theme', 'mill_responsive_videos_init', 99 );
function mill_responsive_videos_init() {

	/* If the theme does support 'jetpack-responsive-videos', wrap the videos */
	add_filter( 'wp_video_shortcode', 'mill_responsive_videos_embed_html' );
	add_filter( 'video_embed_html',   'mill_responsive_videos_embed_html' );

	/* Only wrap oEmbeds if video */
	add_filter( 'embed_oembed_html',  'mill_responsive_videos_maybe_wrap_oembed', 10, 2 );
	add_filter( 'embed_handler_html', 'mill_responsive_videos_maybe_wrap_oembed', 10, 2 );

	/* Wrap videos in Buddypress */
	add_filter( 'bp_embed_oembed_html', 'mill_responsive_videos_embed_html' );
}

/**
 * Adds a wrapper to videos and enqueue script
 *
 * @return string
 *
 * @since Mill 1.0.0
 */
function mill_responsive_videos_embed_html( $html ) {
	if ( empty( $html ) || ! is_string( $html ) ) {
		return $html;
	}

	return '<div class="site-c-embed-responsive site-c-embed-responsive--16by9">' . $html . '</div>';
}

/**
 * Check if oEmbed is a `$video_patterns` provider video before wrapping.
 *
 * @return string
 *
 * @since Mill 1.0.0
 */
function mill_responsive_videos_maybe_wrap_oembed( $html, $url = null ) {
	if ( empty( $html ) || ! is_string( $html ) || ! $url ) {
		return $html;
	}

	$video_wrapper = '<div class="site-c-embed-responsive site-c-embed-responsive--16by9">';

	$already_wrapped = strpos( $html, $video_wrapper );

	// If the oEmbed has already been wrapped, return the html.
	if ( false !== $already_wrapped ) {
		return $html;
	}

	/**
	 * oEmbed Video Providers.
	 *
	 * A whitelist of oEmbed video provider Regex patterns to check against before wrapping the output.
	 *
	 * @module theme-tools
	 *
	 * @since 3.8.0
	 *
	 * @param array $video_patterns oEmbed video provider Regex patterns.
	 */
	$video_patterns = apply_filters( 'mill_responsive_videos_oembed_videos', array(
		'https?://((m|www)\.)?youtube\.com/watch',
		'https?://((m|www)\.)?youtube\.com/playlist',
		'https?://youtu\.be/',
		'https?://(.+\.)?vimeo\.com/',
		'https?://(www\.)?dailymotion\.com/',
		'https?://dai.ly/',
		'https?://(www\.)?hulu\.com/watch/',
		'https?://wordpress.tv/',
		'https?://(www\.)?funnyordie\.com/videos/',
		'https?://vine.co/v/',
		'https?://(www\.)?collegehumor\.com/video/',
		'https?://(www\.|embed\.)?ted\.com/talks/'
	) );

	// Merge patterns to run in a single preg_match call.
	$video_patterns = '(' . implode( '|', $video_patterns ) . ')';

	$is_video = preg_match( $video_patterns, $url );

	// If the oEmbed is a video, wrap it in the responsive wrapper.
	if ( false === $already_wrapped && 1 === $is_video ) {
		return mill_responsive_videos_embed_html( $html );
	}

	return $html;
}
