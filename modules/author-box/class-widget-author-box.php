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
class Mill_Widget_Author_Box extends WP_Widget {
	private $defaults = [];

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = [
			'title' => __( 'Author', 'mill' ),
			'user_id' => 0,
		];
		$widget_ops = [
			'classname'   => 'mill_widget_author_box',
			'description' => __( 'Display author', 'mill' )
		];

		parent::__construct( 'mill_author_box', __( 'Mill: Author Box', 'mill' ), $widget_ops );
		$this->alt_option_name = 'mill_widget_author_box';
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$user_id = ( ! empty( $instance['user_id'] ) ) ? absint( $instance['user_id'] ) : 0;

		if ( $user_id === 0 && ! is_singular() && ! is_author() ) {
			$admins = get_users( [
				'role' => 'administrator',
				'order' => 'ASC',
				'orderby' => 'display_name'
			] );

			$user_id = $admins[0]->ID;
		}

		if ( $user_id === 0 ) {
			$user_id = get_the_author_meta( 'ID' );
		}

		$display_name = get_the_author_meta( 'display_name', $user_id );
		$url = get_author_posts_url( $user_id );
		$attr_title = sprintf(
			esc_attr__('Permalink to %s', 'mill'),
			$display_name
		);

		$description = esc_html( get_the_author_meta( 'description', $user_id ) );
		$description = wptexturize( $description );
		$description = wpautop( $description );

		$tab_bio = $args['widget_id'] . '-bio';
		$tab_posts = $args['widget_id'] . '-posts';

		$latest_posts_by_author = new WP_Query( [
			'posts_per_page' => 3,
			'author'         => $user_id,
			'post_type'      => 'post',
		] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( isset( $args['before_content'] ) ) {
			echo $args['before_content'];
		}
		require_once dirname( __FILE__ ) . '/author-box-template.php';
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
		$instance['user_id'] = (int) $new_instance['user_id'];

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$user_id = isset( $instance['user_id'] ) ? absint( $instance['user_id'] ) : 0;

		$admins = get_users( [
			'role' => 'administrator',
			'order' => 'ASC',
			'orderby' => 'display_name'
		] );

		?>
		<!-- title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<!-- admin -->
		<p>
			<label for="<?php echo $this->get_field_id( 'user_id' ); ?>"><?php _e( 'Fixed display the Admin:', 'mill' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'user_id' ); ?>" name="<?php echo $this->get_field_name( 'user_id' ); ?>">
				<option value="0" <?php selected( $user_id, 0 ); ?>><?php esc_html_e( 'Not displayed', 'mill' ) ; ?></option>
				<?php foreach ( $admins as $admin ) :
					$display_name = esc_html( get_the_author_meta( 'display_name', $admin->ID ) );
					$value = esc_attr( $admin->ID );
				?>
					<option value="<?php echo $value; ?>" <?php selected( $user_id, $value ); ?>><?php echo $display_name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}
}
