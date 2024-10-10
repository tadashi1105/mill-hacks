<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

require_once plugin_dir_path( __FILE__ ) . 'class-social-buttons.php';
require_once plugin_dir_path( __FILE__ ) . 'class-widget-social-subscription.php';
require_once plugin_dir_path( __FILE__ ) . 'class-widget-social-follow.php';

/**
 *
 *
 * @since Mill 1.0.0
 */
add_action( 'mill_single_after_entry_title', 'mill_add_social_buttons_on_single_after_entry_title' );
function mill_add_social_buttons_on_single_after_entry_title() {
	$classes = 'site-c-btn site-c-btn--block site-c-btn--social ';
	$mill_social_buttons = new Mill_Social_Buttons( [
		'facebook' => [
			'text_before' => '<span class="site-c-btn__text site-js-facebook-count">',
			'text_after'  => '</span>',
			'before'      => '<div class="site-c-col-xs-3 site-c-col-md-2">',
			'after'       => '</div>',
			'attrs' => [
				'class'   => $classes . 'site-u-bg-facebook',
			],
		],
		'twitter' => [
			'text_before' => '<span class="site-c-btn__text site-js-twitter-count">',
			'text_after'  => '</span>',
			'before'      => '<div class="site-c-col-xs-3 site-c-col-md-2">',
			'after'       => '</div>',
			'attrs' => [
				'class'   => $classes . 'site-u-bg-twitter',
			],
		],
		'hatebu' => [
			'text_before' => '<span class="site-c-btn__text site-js-hatena-count">',
			'text_after'  => '</span>',
			'before'      => '<div class="site-c-col-xs-3 site-c-col-md-2">',
			'after'       => '</div>',
			'attrs' => [
				'class'   => $classes . 'site-u-bg-hatena hatena-bookmark-button',
			],
		],
		'line' => [
			'text_before' => '<span class="site-c-btn__text site-js-line-count">',
			'text_after'  => '</span>',
			'before'      => '<div class="site-c-col-xs-3 site-c-col-md-2">',
			'after'       => '</div>',
			'attrs' => [
				'class'   => $classes . 'site-u-bg-line',
			],
		],
	] );

	$buttons = $mill_social_buttons->get( [
		'facebook',
		'twitter',
		'hatebu',
		'line',
	] );

	echo '<div class="site-c-row site-c-row--sm site-u-m-t-1">' . $buttons . '</div>';
}

/**
 *
 *
 * @since Mill 1.0.0
 */
add_action( 'mill_single_after_entry_content', 'mill_add_meta_on_single_and_page_after_entry_content' );
function mill_add_meta_on_single_and_page_after_entry_content() {
	?>
	<div class="site-p-entry-card__footer site-c-card__block">
		<?php if ( ! is_front_page() ) : ?>
			<div class="site-p-entry-card__actions">
			<?php $classes = 'site-c-btn site-c-btn--block site-c-btn--social ';
			$mill_social_buttons = new Mill_Social_Buttons( [
				'facebook' => [
					'text_before' => '<span class="site-c-btn__text">',
					'text_after'  => '</span>',
					'before'      => '<div class="site-c-col-xs-3">',
					'after'       => '</div>',
					'attrs' => [
						'class'   => $classes . 'site-u-bg-facebook',
					],
				],
				'twitter' => [
					'text_before' => '<span class="site-c-btn__text">',
					'text_after'  => '</span>',
					'before'      => '<div class="site-c-col-xs-3">',
					'after'       => '</div>',
					'attrs' => [
						'class'   => $classes . 'site-u-bg-twitter',
					],
				],
				'hatebu' => [
					'text_before' => '<span class="site-c-btn__text">',
					'text_after'  => '</span>',
					'before'      => '<div class="site-c-col-xs-3">',
					'after'       => '</div>',
					'attrs' => [
						'class'   => $classes . 'site-u-bg-hatena',
					],
				],
				'line' => [
					'text_before' => '<span class="site-c-btn__text">',
					'text_after'  => '</span>',
					'before'      => '<div class="site-c-col-xs-3">',
					'after'       => '</div>',
					'attrs' => [
						'class'   => $classes . 'site-u-bg-line',
					],
				],
			] );
			$buttons = $mill_social_buttons->get( [
				'facebook',
				'twitter',
				'hatebu',
				'line',
			] );

			echo '<div class="site-c-row site-c-row--sm">' . $buttons . '</div>'; ?>
			</div><!-- .site-p-entry-card__actions -->
		<?php endif; ?>
	</div><!-- .site-p-entry-card__footer -->
<?php }

/**
 *
 *
 * @since Mill 1.0.0
 */
add_filter( 'amp_post_template_data', 'mill_add_amp_social_share_js_to_amp_post_data' );
function mill_add_amp_social_share_js_to_amp_post_data( $data ) {
	$data['amp_component_scripts']['amp-social-share'] = 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js';
	return $data;
}

/**
 *
 *
 * @since Mill 1.0.0
 */
add_filter( 'the_content', 'mill_add_amp_social_share_after_content' );
function mill_add_amp_social_share_after_content( $content ) {
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		if ( is_singular() ) {
			// Twitter
			$content .= '<amp-social-share type="twitter" width="60" height="44"></amp-social-share>';

			// Facebook
			$content .= '<amp-social-share type="facebook" width="60" height="44" data-param-app_id="288134901322895"></amp-social-share>';

			// Pinterest
			$content .= '<amp-social-share type="pinterest" width="60" height="44"></amp-social-share>';

			// Email
			$content .= '<amp-social-share type="email" width="60" height="44"></amp-social-share>';
		}
	}
	return $content;
}

add_action( 'widgets_init', function () {
	register_widget( 'Mill_Widget_Social_Follow' );
	register_widget( 'Mill_Widget_Social_Subscription' );
});