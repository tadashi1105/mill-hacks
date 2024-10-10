<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

require_once plugin_dir_path( __FILE__ ) . 'class-widget-author-box.php';

add_action( 'widgets_init', function () {
	register_widget( 'Mill_Widget_Author_Box' );
});
