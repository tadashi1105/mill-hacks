<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

require_once plugin_dir_path( __FILE__ ) . 'class-related-posts.php';
require_once plugin_dir_path( __FILE__ ) . 'class-widget-related-posts.php';

/**
 * Related Posts for AMP
 *
 * @since Mill 1.0.0
 */
add_action( 'amp_post_template_data', 'mill_related_posts_for_amp', 10, 2 );
function mill_related_posts_for_amp( $data, $post ) {
	if ( class_exists( 'AMP_Content_Sanitizer' ) ) {
		$related_posts = new Mill_Related_Posts( [
			'posts_per_page' => 6,
		] );

		ob_start();
		if ( $related_posts->have_posts() ) {
			$related_posts->display( '', '' );
		}
		$related_posts_html = ob_get_clean();
		$related_posts_html = '<div class="amp-wp-after-article"><h2 style="margin-left: 16px; margin-right: 16px;">Related Posts</h2>' . $related_posts_html . '</div>';

		list( $sanitized_html, $scripts, $styles ) = AMP_Content_Sanitizer::sanitize(
			$related_posts_html,
			[
				'AMP_Style_Sanitizer' => [],
				'AMP_Img_Sanitizer' => [],
			],
			[
				'content_max_width' => $data['content_max_width']
			]
		);

		$data['mill_related_posts'] = [
			'amp_html' => $sanitized_html,
		];

		if ( $scripts ) {
			if ( is_array( $data['amp_component_scripts'] ) ) {
				$data['amp_component_scripts'] = array_merge( $data['amp_component_scripts'], $scripts );
			} else {
				$data['amp_component_scripts'] = $scripts;
			}
		}

		if ( $styles ) {
			if ( is_array( $data['post_amp_styles'] ) ) {
				$data['post_amp_styles'] = array_merge( $data['post_amp_styles'], $styles );
			} else {
				$data['post_amp_styles'] = $styles;
			}
		}
	}

	return $data;
}

/**
 * Add Related Posts for AMP
 *
 * @since Mill 1.0.0
 */
add_action( 'amp_post_template_include_footer', 'mill_add_related_posts_for_amp' );
function mill_add_related_posts_for_amp( $amp_post_template ) {
		$related_posts = $amp_post_template->get( 'mill_related_posts' );

		if ( empty( $related_posts ) ) {
			return;
		}

		echo $related_posts['amp_html'];
}

add_action( 'widgets_init', function () {
	register_widget( 'Mill_Widget_Related_Posts' );
});