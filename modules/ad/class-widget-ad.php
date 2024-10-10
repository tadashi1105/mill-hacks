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
class Mill_Widget_Ad extends WP_Widget {

	private $defaults = [];
	private $ads;

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = [
			'title' => __( 'Advertisement', 'mill' ),
			'label' => __( 'Sponsored Link', 'mill' ),
			'text' => '',
		];

		$this->ads = Mill_Ad::get_instance();

		$widget_ops = [

			'classname'   => 'mill_widget_ad',
			'description' => __( 'Display Advertisement', 'mill' ),
		];

		parent::__construct( 'mill_ad', __( 'Mill: Advertisement columns', 'mill' ), $widget_ops );
		$this->alt_option_name = 'mill_widget_ad';
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$label = ( ! empty( $instance['label'] ) ) ? $instance['label'] : '';
		$ad    = ( ! empty( $instance['ad'] ) ) ? $instance['ad'] : '';
		$text = ( ! empty( $instance['text'] ) ) ? $instance['text'] : '';
		// $class = ( ! empty( $instance['class'] ) ) ? ' ' . $instance['class'] : '';

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( isset( $args['before_content'] ) ) {
			echo $args['before_content'];
		}

		if ( $label ) {
			echo '<div class="site-c-card__block">';
			echo '<p class="small site-u-m-a-0">' . esc_html( $label ) . '</p>';
			echo '</div>';
		}

		$this->ads->display( $ad );

		if ( $text ) {
			echo '<div class="site-c-card__block">';
			echo $text;
			echo '</div>';
		}

		if ( isset( $args['after_content'] ) ) {
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
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['label'] = strip_tags( $new_instance['label'] );
		$instance['ad']    = strip_tags( $new_instance['ad'] );

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$label = isset( $instance['label'] ) ? $instance['label'] : '';
		$ad    = isset( $instance['ad'] ) ? $instance['ad'] : '';
		$text = isset( $instance['text'] ) ? $instance['text'] : '';

		?>
		<!-- title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<!-- label -->
		<p>
			<label for="<?php echo $this->get_field_id( 'label' ); ?>"><?php _e( 'Label:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
		</p>

		<!-- ad -->
		<p>
			<label for="<?php echo $this->get_field_id( 'ad' ); ?>"><?php _e( 'Ad:','mill' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'ad' ); ?>" name="<?php echo $this->get_field_name( 'ad' ); ?>">
				<?php
				foreach ( $this->ads->get() as $key => $value ) {
					?>
					<option value="<?php echo esc_attr( $key );  ?>" <?php selected( $ad, $key ); ?>><?php echo esc_html( $key ) ?></option>
					<?php
				}
				?>
			</select>
		</p>

		<!-- text -->
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:', 'mill' ); ?></label>
			<textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text"><?php echo esc_textarea( $text ); ?></textarea>
		</p>
		<?php
	}
}
