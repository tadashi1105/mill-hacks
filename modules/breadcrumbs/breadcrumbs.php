<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

require_once plugin_dir_path( __FILE__ ) . 'class-breadcrumbs.php';

/**
 *
 *
 * @since Mill 1.0.0
 */
function mill_get_breadcrumb ( array $args = [] ) {
	$defaults = [
		'attr' => [
			'id'    => 'breadcrumb',
			'class' => 'site-p-breadcrumb site-u-scrollable-x site-u-text-nowrap site-u-hidden-sm-down',
		],
		'separator'   => '<span class="site-p-breadcrumb__separator" aria-hidden="true"> &gt; </span>',
		'wrap_format' => '<nav itemscope itemtype="http://schema.org/BreadcrumbList"%1$s><div class="site-c-container">%2$s</div></nav>',
		'link_format' => '<a itemprop="item" href="%1$s">%2$s</a><meta itemprop="position" content="%3$d" />',
		'last_format' => '<span>%1$s</span>',
		'before'      => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
		'after'       => '</span>',
		'link_before' => '<span itemprop="name">',
		'link_after'  => '</span>',
		'front_label' => '',
		'home_label'  => __( 'Home', 'mill' ),
		'echo'        => false,
	];

	$args = wp_parse_args( $args, $defaults );
	return Mill_Breadcrumbs::get_instance( $args );
}

/**
 *
 *
 * @since Mill 1.0.0
 */
add_action( 'get_template_part', 'mill_display_breadcrumb' );
function mill_display_breadcrumb( $slug ) {
	if ( $slug === 'template-parts/header/page-header') {
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			  yoast_breadcrumb( '<nav id="breadcrumbs" class="site-p-breadcrumb site-u-scrollable-x site-u-text-nowrap site-u-hidden-sm-down"><div class="site-c-container">', '</div></nav>' );
		} else {
			echo mill_get_breadcrumb();
		}
	}
}

