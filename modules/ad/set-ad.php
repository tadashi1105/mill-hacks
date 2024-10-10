<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

/**
 * Add adsbygooglejs
 *
 * @since Mill 1.0.0
 */
add_action( 'wp_head', 'mill_add_adsbygooglejs' );
function mill_add_adsbygooglejs() {
	echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
}

/**
 * 広告の設定
 *
 * @since Mill 1.0.0
 */
mill_set_ad();
function mill_set_ad() {

	$ad = Mill_Ad::get_instance();

	$ad->set(
		'main-top',
		<<<EOT
<!-- mkw-main-top -->
<ins class="adsbygoogle"
	 style="display:block"
	 data-ad-client="ca-pub-8903269669909171"
	 data-ad-slot="2888640470"
	 data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
EOT
	);

	$ad->set(
		'main-before-content',
		<<<EOT
<!-- mkw-main-before-content -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8903269669909171"
     data-ad-slot="8498608077"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
EOT
	);

	$ad->set(
		'main-after-content',
		<<<EOT
<!-- mkw-main-after-content -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8903269669909171"
     data-ad-slot="7478872078"
     data-ad-format="rectangle"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
EOT
	);

	$ad->set(
		'main-bottom',
		<<<EOT
<!-- mkw-main-bottom -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8903269669909171"
     data-ad-slot="8935174078"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
EOT
	);

	$ad->set(
		'side-top',
		<<<EOT
<!-- mkw-side-top -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8903269669909171"
     data-ad-slot="3028241279"
     data-ad-format="rectangle"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
EOT
	);

	// <!-- mkw-amp-before-content -->
	$ad->set(
		'amp-before-content',
		<<<EOT
<amp-ad width="336" height="280"
	 type="adsense"
	 data-ad-client="ca-pub-8903269669909171"
	 data-ad-slot="3434788076">
</amp-ad>
EOT
	);

	// <!-- mkw-amp-after-content -->
	$ad->set(
		'amp-after-content',
		<<<EOT
<amp-ad width="336" height="280"
	 type="adsense"
	 data-ad-client="ca-pub-8903269669909171"
	 data-ad-slot="9868619275">
</amp-ad>
EOT
	);

	$ad->set(
		'google-mobile-ad',
		<<<EOT
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-8903269669909171",
    enable_page_level_ads: true
  });
</script>
EOT
	);
}
