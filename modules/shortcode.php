<?php
/**
 *
 * @package WordPress
 * @since Mill 1.0.0
 */


add_shortcode('mill_aside', 'mill_hacks_aside');
function mill_hacks_aside( $atts, $content = "" ) {
    $atts = shortcode_atts( [
        'title' => '',
        'type' => ''
    ], $atts, 'mill_hacks_aside' );
    
    if ( ! empty( $atts['title'] ) ) {
        $atts['title'] = '<p><strong>' . $atts['title'] . '</strong></p>';
    }
    $html = "<aside>" . $atts['title'] . $content . "</aside>";
    return $html;
}
