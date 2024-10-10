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
class Mill_Widget_Related_Posts extends WP_Widget {
	private $defaults = [];

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = [
		'title' => __( 'Related Posts', 'mill' ),
		'number' => 6,
		];
		$widget_ops = [
			'classname'   => 'mill_widget_related_posts',
			'description' => __( 'A list of related posts.', 'mill' )
		];
		parent::__construct( 'mill_related_posts', __( 'Mill: Related Posts', 'mill' ), $widget_ops );
		$this->alt_option_name = 'mill_widget_related_posts';
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 6;
		if ( ! $number ) {
			$number = 6;
		}
		$related_posts = new Mill_Related_Posts( [
			'posts_per_page' => $number,
		] );

		if ( $related_posts->have_posts() ) {
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			if ( isset( $args['before_content'] ) ) {
				echo $args['before_content'];
			}

			$related_posts->display( '', '' );

			if ( isset( $args['after_content'] ) ) {
				echo $args['after_content'];
			}
			echo $args['after_widget'];
		}
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;

		?>
		<!-- title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<!-- number -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'mill' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<?php
	}

	/**
	 * @return array $tag_ids
	 */
	private function get_tag_ids() {
		$tag_ids = [];
		$tags = get_the_tags();
		if ( is_array( $tags ) ) {
			foreach ( $tags as $tag ) {
				$tag_ids[] = $tag->term_id;
			}
		}
		return $tag_ids;
	}
}
