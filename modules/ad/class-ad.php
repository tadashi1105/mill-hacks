<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

/**
 *
 *
 * @since Mill 1.0.0
 */
class Mill_Ad {

	private static $instance;
	private $ads;

	/**
	 *
	 *
	 * @since Mill 1.0.0
	 */
	private function __construct() {
		$this->ads = [];
	}

	/**
	 *
	 *
	 * @since Mill 1.0.0
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof Mill_Ad ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *
	 *
	 * @since Mill 1.0.0
	 */
	public function set( $key, $ad, $class = '' ) {
		$this->ads[$key] = [
			'before_ad' => '<div class="site-p-ad ' . esc_attr( $class ) . '"><div class="site-p-ad__item site-p-ad__item--'. esc_attr( $key ) . '">',
			'ad' => $ad,
			'after_ad' => '</div></div>',
		];
	}

	/**
	 *
	 *
	 * @since Mill 1.0.0
	 */
	public function get( $key = null ) {
		if ( isset( $key ) ) {
			return $this->ads[$key];
		}
		return $this->ads;
	}

	/**
	 *
	 *
	 * @since Mill 1.0.0
	 */
	public function display( $key ) {
		echo $this->ads[$key]['before_ad'];
		echo $this->ads[$key]['ad'];
		echo $this->ads[$key]['after_ad'];
	}
}
