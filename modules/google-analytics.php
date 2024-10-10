<?php
/**
 *
 *
 * @since 1.0.0
 */
add_action( 'wp_footer', 'mill_hacks_ga_tracking_code' );
function mill_hacks_ga_tracking_code() {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41230754-1', 'auto');
  ga('send', 'pageview');

</script>
<?php
}

/**
 *
 *
 * @since 1.0.0
 */
add_filter( 'amp_post_template_analytics', 'mill_hacks_amp_add_custom_analytics' );
function mill_hacks_amp_add_custom_analytics( $analytics ) {
    if ( ! is_array( $analytics ) ) {
        $analytics = [];
    }

	// https://developers.google.com/analytics/devguides/collection/amp-analytics/
	$analytics['mill-googleanalytics'] = [
		'type' => 'googleanalytics',
		'attributes' => [
			// 'data-credentials' => 'include',
		],
		'config_data' => [
			'vars' => [
				'account' => 'UA-41230754-1'
			],
			'triggers' => [
				'trackPageview' => [
					'on' => 'visible',
					'request' => 'pageview',
				],
			],
		],
	];

	return $analytics;
}
