<?php
/**
 * Widget
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

/**
 * @since Mill 1.0.0
 */
class Mill_Widget_Social_Subscription extends WP_Widget {
	private $defaults = [];

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = [
			'title'      => __( 'Social Subscription', 'mill' ),
			'mail'       => '//millkeyweb.com/mail-magazine',
			'rss'        => '//millkeyweb.com/feed/',
			'feedly'     => '//cloud.feedly.com/#subscription%2Ffeed%2Fhttp%3A%2F%2Fmillkeyweb.com%2Ffeed%2F',
			'twitter'    => '//twitter.com/millkeyweb',
			'youtube'    => '',
			'facebook'   => '//www.facebook.com/millkeyweb',
			'line'       => '//line.me/ti/p/%40cml9771a',
			'class_name' => 'show',
		];
		$widget_ops = [
			'classname'   => 'mill_widget_social_subscription',
			'description' => __( 'Entries from any social subscription link.', 'mill' )
		];
		$control_ops = [ 'width' => 400, 'height' => 200 ];
		parent::__construct( 'mill_social_subscription', __( 'Mill: Social Subscription', 'mill' ), $widget_ops, $control_ops );
		$this->alt_option_name = 'mill_widget_social_subscription';

	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$socials = [];
		$socials['facebook'] = strip_tags( $instance['facebook'] );
		$socials['twitter'] = strip_tags( $instance['twitter'] );
		$socials['rss'] = strip_tags( $instance['rss'] );
		$socials['feedly'] = strip_tags( $instance['feedly'] );
		$socials['line'] = strip_tags( $instance['line'] );
		$socials['mail'] = strip_tags( $instance['mail'] );
		$socials['youtube'] = strip_tags( $instance['youtube'] );
		$buttons = [];
		$default_classes = 'site-c-btn site-c-btn--social ';

		foreach ( $socials as $name => $url ) {
			if ( ! empty( $url ) ) {
				// $classes = $default_classes . 'site-u-bg-' . $name;
				$classes = $default_classes . 'site-u-text-' . $name;
				// $classes = $default_classes;

				$buttons[$name] = [
					'url'         => $url,
					'text_before' => '<span class="site-c-btn__text">',
					'text_after'  => '</span>',
					'count'       => '',
					'attrs' => [
						'rel'     => 'nofollow',
						'class'   => $classes,
					],
				];

				switch ( $name ) {
				case 'mail' :
					$buttons[$name]['text'] = 'News letter';
					$buttons[$name]['icon'] = '<span class="fa fa-envelope" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Subscribe our news letter';
					break;
				case 'rss' :
					$buttons[$name]['text'] = 'RSS';
					$buttons[$name]['icon'] = '<span class="fa fa-rss" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Subscribe our site';
					break;
				case 'feedly' :
					$buttons[$name]['text'] = 'Feedly';
					$buttons[$name]['icon'] = '<span class="icon-feedly" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Subscribe our site in feedly';
					break;
				case 'twitter' :
					$buttons[$name]['text'] = 'Twitter';
					$buttons[$name]['icon'] = '<span class="fa fa-twitter" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Follow our Twitter';
					break;
				case 'facebook' :
					$buttons[$name]['text'] = 'Facebook';
					$buttons[$name]['icon'] = '<span class="fa fa-facebook" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Like our Facebook';
					break;
				case 'youtube' :
					$buttons[$name]['text'] = 'YouTube';
					$buttons[$name]['icon'] = '<span class="fa fa-youtube" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Subscribe our YouTube';
					break;
				case 'line' :
					$buttons[$name]['text'] = 'LINE@';
					$buttons[$name]['icon'] = '<span class="icon-line" aria-hidden="true"></span>';
					$buttons[$name]['attrs']['title'] = 'Add Friends';
					break;
				}
			}
		}

		$mill_social_buttons = new Mill_Social_Buttons( $buttons );
		// $buttons = [
		// 	'twitter',
		// 	'facebook',
		// ];
		$buttons = $mill_social_buttons->get( $buttons );

		if ( ! empty( $instance['class_name'] ) && $instance['class_name'] !== 'show') {
			$class_name = strip_tags( $instance['class_name'] );
			if ( strpos( $args['before_widget'], 'class' ) === false ) {
				$args['before_widget'] = str_replace( '>', ' class="'. $class_name . '">', $args['before_widget'] );
			} else {
				$args['before_widget'] = str_replace( 'class="', 'class="'. $class_name . ' ', $args['before_widget'] );
			}
		}

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( isset( $args['before_content'] ) ) {
			echo $args['before_content'];
		} ?>
		<div class="site-p-social-subscription site-u-clearfix">
			<?php echo $buttons; ?>
		</div>
		<?php if ( isset( $args['after_content'] ) ) {
			echo $args['after_content'];
		}
		echo $args['after_widget'];
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['mail']        = strip_tags( $new_instance['mail'] );
		$instance['rss']         = strip_tags( $new_instance['rss'] );
		$instance['feedly']      = strip_tags( $new_instance['feedly'] );
		$instance['twitter']     = strip_tags( $new_instance['twitter'] );
		$instance['facebook']    = strip_tags( $new_instance['facebook'] );
		$instance['youtube']     = strip_tags( $new_instance['youtube'] );
		$instance['line']        = strip_tags( $new_instance['line'] );
		$instance['class_name']  = strip_tags( $new_instance['class_name'] );

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance    = wp_parse_args( (array) $instance, $this->defaults );
		$title       = strip_tags( $instance['title'] );
		$mail        = strip_tags( $instance['mail'] );
		$rss         = strip_tags( $instance['rss'] );
		$feedly      = strip_tags( $instance['feedly'] );
		$twitter     = strip_tags( $instance['twitter'] );
		$facebook    = strip_tags( $instance['facebook'] );
		$youtube     = strip_tags( $instance['youtube'] );
		$line        = strip_tags( $instance['line'] );
		$class_name  = strip_tags( $instance['class_name'] );

		?>
		<!-- title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<!-- mail -->
		<p>
			<label for="<?php echo $this->get_field_id( 'mail' ); ?>"><?php _e( 'Mail:', 'mill'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'mail' ); ?>" name="<?php echo $this->get_field_name( 'mail' ); ?>" type="text" value="<?php echo esc_attr( $mail ); ?>" />
		</p>

		<!-- rss -->
		<p>
			<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e( 'RSS:', 'mill'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo esc_attr( $rss ); ?>" />
		</p>

		<!-- feedly -->
		<p>
			<label for="<?php echo $this->get_field_id( 'feedly' ); ?>"><?php _e( 'Feedly:', 'mill'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'feedly' ); ?>" name="<?php echo $this->get_field_name( 'feedly' ); ?>" type="text" value="<?php echo esc_attr( $feedly ); ?>" />
		</p>

		<!-- twitter username -->
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter:', 'mill'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" />
		</p>

		<!-- facebook id -->
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook page:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>" />
		</p>

		<!-- youtube username -->
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>" />
		</p>

		<!-- line@ url -->
		<p>
			<label for="<?php echo $this->get_field_id( 'line' ); ?>"><?php _e( 'LINE@ url:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'line' ); ?>" name="<?php echo $this->get_field_name( 'line' ); ?>" type="text" value="<?php echo esc_attr( $line ); ?>" />
		</p>

		<!-- class name -->
		<p>
			<label for="<?php echo $this->get_field_id( 'class_name' ); ?>"><?php _e( 'Class name:', 'mill' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'class_name' ); ?>" name="<?php echo $this->get_field_name( 'class_name' ); ?>">
				<option value="show" <?php selected( $class_name, 'show' ); ?>><?php _e( 'show', 'mill' ) ?></option>
				<option value="site-u-hidden-xs-down" <?php selected( $class_name, 'site-u-hidden-xs-down' ); ?>><?php _e( 'site-u-hidden-xs-down', 'mill' ) ?></option>
				<option value="site-u-hidden-sm-down" <?php selected( $class_name, 'site-u-hidden-sm-down' ); ?>><?php _e( 'site-u-hidden-sm-down', 'mill' ) ?></option>
				<option value="site-u-hidden-md-down" <?php selected( $class_name, 'site-u-hidden-md-down' ); ?>><?php _e( 'site-u-hidden-md-down', 'mill' ) ?></option>
				<option value="site-u-hidden-lg-down" <?php selected( $class_name, 'site-u-hidden-lg-down' ); ?>><?php _e( 'site-u-hidden-lg-down', 'mill' ) ?></option>
				<option value="site-u-hidden-xl-down" <?php selected( $class_name, 'site-u-hidden-xl-down' ); ?>><?php _e( 'site-u-hidden-xl-down', 'mill' ) ?></option>
				<option value="site-u-hidden-xs-up" <?php selected( $class_name, 'site-u-hidden-xs-up' ); ?>><?php _e( 'site-u-hidden-xs-up', 'mill' ) ?></option>
				<option value="site-u-hidden-sm-up" <?php selected( $class_name, 'site-u-hidden-sm-up' ); ?>><?php _e( 'site-u-hidden-sm-up', 'mill' ) ?></option>
				<option value="site-u-hidden-md-up" <?php selected( $class_name, 'site-u-hidden-md-up' ); ?>><?php _e( 'site-u-hidden-md-up', 'mill' ) ?></option>
				<option value="site-u-hidden-lg-up" <?php selected( $class_name, 'site-u-hidden-lg-up' ); ?>><?php _e( 'site-u-hidden-lg-up', 'mill' ) ?></option>
				<option value="site-u-hidden-xl-up" <?php selected( $class_name, 'site-u-hidden-xl-up' ); ?>><?php _e( 'site-u-hidden-xl-up', 'mill' ) ?></option>
			</select>
		</p>
		<?php
	}
}
