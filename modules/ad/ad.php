<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

require_once plugin_dir_path( __FILE__ ) . 'class-ad.php';
require_once plugin_dir_path( __FILE__ ) . 'set-ad.php';
require_once plugin_dir_path( __FILE__ ) . 'class-widget-ad.php';

/**
 * Insert ad for the_content
 *
 * @since Mill 1.0.0
 */
add_filter( 'the_content', 'mill_insert_ad_for_the_content' );
function mill_insert_ad_for_the_content( $content ) {
	if ( is_single() ) {
		$instance = Mill_Ad::get_instance();
		$mbcad = 'main-before-content';
		$macad = 'main-after-content';
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			$mbcad = 'amp-before-content';
			$macad = 'amp-after-content';
		}
		$mbcad = join( $instance->get( $mbcad ) );
		$macad = join( $instance->get( $macad ) );
		$content = $mbcad . $content . $macad;
	}

	return $content;
}

/**
 * Insert ad to before_main
 *
 * @since Mill 1.0.0
 */
add_action( 'get_template_part', 'mill_insert_ad_to_before_main', 11 );
function mill_insert_ad_to_before_main( $slug ) {
	if ( is_404() || $slug !== 'template-parts/header/page-header' ) {
		return;
	}

	if ( ! is_singular() ) {
		$ads = Mill_Ad::get_instance();
		$ads->display( 'main-top' );
	}
}

/**
 * Insert ad to after_main
 *
 * @since Mill 1.0.0
 */
add_action( 'get_footer', 'mill_insert_ad_to_after_main', 11 );
function mill_insert_ad_to_after_main() {
	if ( is_404() ) {
		return;
	}
	if ( ! is_singular() ) {
		$ads = Mill_Ad::get_instance();
		$ads->display( 'main-bottom' );
	}
}

/**
 * Insert google_mobile_ad
 *
 * @since Mill 1.0.0
 */
add_action( 'wp_head', 'mill_insert_google_mobile_ad' );
function mill_insert_google_mobile_ad() {
	$ad = Mill_Ad::get_instance();
	$ad = $ad->get( 'google-mobile-ad' );
	echo $ad['ad'];
}

/**
 *
 *
 * @since Mill 1.0.0
 */
add_filter( 'amp_post_template_data', 'mill_add_amp_ad_js_to_amp_post_data' );
function mill_add_amp_ad_js_to_amp_post_data( $data ) {
	$data['amp_component_scripts']['amp-ad'] = 'https://cdn.ampproject.org/v0/amp-ad-0.1.js';
	return $data;
}

add_action( 'widgets_init', function () {
	register_widget( 'Mill_Widget_Ad' );
});