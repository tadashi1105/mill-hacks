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
class Mill_Widget_Social_Follow extends WP_Widget {

	private $defaults = [];

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = [
			'title'       => __( 'Follow me', 'mill' ),
			'twitter_id'  => 'millkeyweb',
			'line_url'    => '//line.me/ti/p/%40cml9771a',
			'before_text' => __( 'If you like this article, please follow us.', 'mill' ),
			'class_name'  => 'show',
		];
		$widget_ops = [
			'classname'   => 'mill_widget_social_follow',
			'description' => __('Display follow', 'mill')
		];
		parent::__construct( 'mill_social_follow', __( 'Mill: Social follow', 'mill' ), $widget_ops );
		$this->alt_option_name = 'mill_widget_social_follow';
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$twitter_id  = ( ! empty( $instance['twitter_id'] ) )  ? strip_tags( $instance['twitter_id'] ) : '';
		$line_url    = ( ! empty( $instance['line_url'] ) )    ? strip_tags( $instance['line_url'] )   : '//line.me';
		$before_text = ( ! empty( $instance['before_text'] ) ) ? $instance['before_text'] : '';

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
		}
		?>
<div class="site-c-card__block">
	<?php if ( $before_text ) : ?>
		<p class=""><?php echo $before_text; ?></p>
	<?php endif; ?>
	<div class="site-c-row site-c-row--sm site-u-m-t-1">
		<div class="site-c-col-xs-6">
			<a href="//twitter.com/<?php echo esc_html( $twitter_id ); ?>" class="site-c-btn site-c-btn--block site-c-btn--social site-u-bg-twitter">
				<span class="fa fa-twitter" aria-hidden="true"></span>
				<span class="site-c-btn__text">フォロー<span class="site-u-hidden-sm-down"> @<?php echo esc_html( $twitter_id ); ?></span></span>
			</a>
		</div>
		<div class="site-c-col-xs-6">
		<a href="<?php echo esc_url( $line_url ); ?>" class="site-c-btn site-c-btn--block site-c-btn--social site-u-bg-line">
				<span class="icon-line" aria-hidden="true"></span>
				<span class="site-c-btn__text">友だち追加</span>
			</a>
		</div>
	</div>
</div>
		<?php
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
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['twitter_id']  = strip_tags( $new_instance['twitter_id'] );
		$instance['line_url']    = strip_tags( $new_instance['line_url'] );
		$instance['before_text'] = strip_tags( $new_instance['before_text'] );
		$instance['class_name']  = strip_tags( $new_instance['class_name'] );

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance    = wp_parse_args( (array) $instance, $this->defaults );
		$title       = strip_tags( $instance['title'] );
		$twitter_id  = strip_tags( $instance['twitter_id'] );
		$line_url    = strip_tags( $instance['line_url'] );
		$before_text = strip_tags( $instance['before_text'] );
		$class_name  = strip_tags( $instance['class_name'] );

		?>
		<!-- title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<!-- twitter_id -->
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_id' ); ?>"><?php _e( 'Twitter ID:', 'mill'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_id' ); ?>" type="text" value="<?php echo esc_attr( $twitter_id ); ?>" />
		</p>

		<!-- line_url -->
		<p>
			<label for="<?php echo $this->get_field_id( 'line_url' ); ?>"><?php _e( 'LINE@ URL:', 'mill'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'line_url' ); ?>" name="<?php echo $this->get_field_name( 'line_url' ); ?>" type="text" value="<?php echo esc_attr( $line_url ); ?>" />
		</p>

		<!-- before_text -->
		<p>
			<label for="<?php echo $this->get_field_id( 'before_text' ); ?>"><?php _e( 'Before text:', 'mill' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_text' ); ?>" name="<?php echo $this->get_field_name( 'before_text' ); ?>" type="text" value="<?php echo esc_attr( $before_text ); ?>" />
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
