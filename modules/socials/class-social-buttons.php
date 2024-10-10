<?php
/**
 * Social buttons
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

class Mill_Social_Buttons {
	private $options = [];

	public function __construct( array $args = [] ) {
		$this->set( $args );
	}

	public function set( array $args = [] ) {
		$link  = apply_filters( 'the_permalink', get_permalink() );
		$title = strip_tags( get_the_title() );

		$defaults = [
			'twitter' => [
				'url'         => '//twitter.com/share?text=' . rawurlencode( $title ) . '&amp;url=' . $link . '&amp;via=millkeyweb&amp;hashtags=millkeyweb',
				'text'        => 'Tweet',
				'icon'        => '<span class="fa fa-twitter" aria-hidden="true"></span>',
				'attrs' => [
					'title'   => 'Twitter tweet button',
					'class'   => 'site-c-btn site-u-bg-twitter',
					'rel'     => 'nofollow',
				],
			],
			'facebook' => [
				'url'         => '//www.facebook.com/sharer/sharer.php?u=' . $link . '&amp;t=' . rawurlencode( $title ),
				'text'        => 'Share',
				'icon'        => '<span class="fa fa-facebook" aria-hidden="true"></span>',
				'attrs' => [
					'title'   => 'Facebook share button',
					'class'   => 'site-c-btn site-u-bg-facebook',
					'rel'     => 'nofollow',
				],
			],
			'hatebu' => [
				'url'         => '//b.hatena.ne.jp/entry/' . $link,
				'text'        => 'Bookmark',
				'icon'        => '<span class="icon-hatena" aria-hidden="true"></span>',
				'attrs' => [
					'title'   => 'このエントリーをはてなブックマークに追加',
					'class'   => 'site-c-btn site-u-bg-hatena hatena-bookmark-button',
					'data-hatena-bookmark-title'  =>  $title,
					'data-hatena-bookmark-layout' => 'simple',
					'rel'     => 'nofollow',
				],
			],
			'pocket' => [
				'url'         => '//getpocket.com/edit?url=' . $link,
				'text'        => 'Add',
				'icon'        => '<span class="fa fa-get-pocket btn-icon" aria-hidden="true"></span>',
				'attrs' => [
					'title'   => 'Pocket add button',
					'class'   => 'site-c-btn site-u-bg-pocket',
					'rel'     => 'nofollow',
				],
			],
			'feedly' => [
				'url'         => '//cloud.feedly.com/#subscription%2Ffeed%2F' . get_feed_link( 'rss2' ),
				'text'        => 'Subscribe',
				'icon'        => '<span class="icon-feedly btn-icon" aria-hidden="true"></span>',
				'attrs' => [
					'title'   => 'Feedly add button',
					'class'   => 'site-c-btn site-u-bg-feedly',
					'rel'     => 'nofollow',
				],
			],
			'line' => [
				'url'         => '//line.me/R/msg/text/?' . rawurlencode( $title ) .'%0D%0A' . $link,
				'text'        => 'Send',
				'icon'        => '<span class="icon-line btn-icon" aria-hidden="true"></span>',
				'attrs' => [
					'title'   => 'LINE send button',
					'class'   => 'site-c-btn site-u-bg-line',
					'rel'     => 'nofollow',
				],
			],
		];

		$this->options = array_replace_recursive( $defaults, $args );
	}

	public function get( array $names = [] ) {
		if ( empty( $names ) ) {
			return;
		}

		$buttons = '';

		foreach ( $names as $name => $value ) {
			$buttons .= $this->generate_button( $name );
		}

		return $buttons;
	}

    /**
	 * $type : text&count text
     */
	private function generate_button( $type ) {
		if ( empty( $this->options[$type] ) ) {
			return;
		}

		$button = '<span %1$s>%2$s</span>';
		$attrs = [];
		$text = '';

		if ( ! empty( $this->options[$type]['url'] ) && is_string( $this->options[$type]['url'] ) ) {
			$button = '<a %1$s>%2$s</a>';
			$attrs[] = 'href="' . esc_url( $this->options[$type]['url'] ) . '"';
		}

		if ( ! empty( $this->options[$type]['attrs'] ) && is_array( $this->options[$type]['attrs'] ) ) {
			foreach ( $this->options[$type]['attrs'] as $attr => $value ) {
				if ( is_string( $value ) ) {
					$attrs[] = esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
				}
			}
		}
		$attrs = implode( ' ', $attrs );

		if ( ! empty( $this->options[$type]['icon'] ) && is_string( $this->options[$type]['icon'] ) ) {
			$text .= $this->options[$type]['icon'];
		}

		if ( ! empty( $this->options[$type]['text_before'] ) && is_string( $this->options[$type]['text_before'] ) ) {
			$text .= $this->options[$type]['text_before'];
		}

		if ( ( ! empty( $this->options[$type]['text'] ) && is_string( $this->options[$type]['text'] ) ) ) {
			$text .= esc_html( $this->options[$type]['text'] );
		}

		if ( ! empty( $this->options[$type]['text_after'] ) && is_string( $this->options[$type]['text_after'] ) ) {
			$text .= $this->options[$type]['text_after'];
		}

		if ( ! empty( $this->options[$type]['before'] ) && is_string( $this->options[$type]['before'] ) ) {
			$button = $this->options[$type]['before'] . $button;
		}
		
		if ( ! empty( $this->options[$type]['after'] ) && is_string( $this->options[$type]['after'] ) ) {
			$button .= $this->options[$type]['after'];
		}
		$button = sprintf( $button, $attrs, $text );

		return $button;
	}
}
