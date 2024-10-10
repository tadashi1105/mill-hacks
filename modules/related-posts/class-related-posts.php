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
class Mill_Related_Posts {

	private $args;
	/**
	 *
	 *
	 */
	public function __construct( array $args = [] ) {
		$this->args = $args;
	}
	/**
	 * Display related posts
	 */
	public function display( $before = '', $after = '') {
		$post_type = get_post_type();
		$tax_query = $this->get_tax_query( $post_type );

		if ( $tax_query ) {
			$posts = $this->get_related_posts( $tax_query );
		}

		if ( empty( $posts ) ) {
			return;
		}

		global $post, $wp_query;
		$default_query = clone $wp_query;
		set_query_var( 'is_related', true );
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			echo $before;
			require_once dirname( __FILE__ ) . '/entry-list.php';
			echo $after;
		}
		wp_reset_postdata();
		$wp_query = $default_query;
	}

	/**
	 * Return the argments for the tax_query
	 *
	 * @param string $taxonomy_name
	 * @param array $term_ids
	 * @return array
	 */
	protected function get_tax_query_condition( $taxonomy_name, $term_ids ) {
		$condition = [
			'taxonomy' => $taxonomy_name,
			'field'    => 'id',
			'terms'    => $term_ids,
		];
		return $condition;
	}

	/**
	 * Return the tax_query for the specified post type
	 *
	 * @param string $post_type
	 * @return array
	 */
	protected function get_tax_query( $post_type ) {
		$tax_query = [];

		if ( $post_type === 'post' ) {
			$category_ids = $this->get_the_category_ids();
			if ( $category_ids ) {
				$tax_query[] = $this->get_tax_query_condition( 'category', $category_ids );
			}
			$tag_ids = $this->get_the_tag_ids();
			if ( $tag_ids ) {
				$tax_query[] = $this->get_tax_query_condition( 'post_tag', $tag_ids );
			}
			return $tax_query;
		}

		if ( $post_type ) {
			$taxonomies = $this->get_the_taxonomies();
			foreach ( $taxonomies as $taxonomy_name ) {
				$term_ids = $this->get_the_term_ids( $taxonomy_name );
				if ( $term_ids ) {
					$tax_query[] = $this->get_tax_query_condition( $taxonomy_name, $term_ids );
				}
			}
			return $tax_query;
		}
		return $tax_query;
	}

	/**
	 * Return the related posts
	 *
	 * @param array $tax_query
	 * @return array
	 */
	protected function get_related_posts( $tax_query ) {
		global $post;
		if ( ! $post ) {
			return [];
		}

		$args = [
			'post_type'      => get_post_type( $post->ID ),
			'posts_per_page' => apply_filters( 'mill_relates_posts_per_page', 6 ),
			'orderby'        => 'rand',
			'post__not_in'   => [ $post->ID ],
			'tax_query'      => array_merge(
				[
					'relation' => 'AND',
				],
				$tax_query
			),
		];

		$args = wp_parse_args( $this->args, $args );

		$args  = apply_filters( 'mill_relates_posts_args', $args );
		$posts = get_posts( $args );
		return $posts;
	}

	/**
	 * Return the category ids
	 *
	 * @return array
	 */
	protected function get_the_category_ids() {
		$category_ids = [];
		$categories = get_the_category();
		if ( is_array( $categories ) ) {
			foreach ( $categories as $category ) {
				$category_ids[] = $category->term_id;
			}
		}
		return $category_ids;
	}

	/**
	 * Return the tag ids
	 *
	 * @return array
	 */
	protected function get_the_tag_ids() {
		$tag_ids = [];
		$tags = get_the_tags();
		if ( is_array( $tags ) ) {
			foreach ( $tags as $tag ) {
				$tag_ids[] = $tag->term_id;
			}
		}
		return $tag_ids;
	}

	/**
	 * Return the custom taxonomy ids
	 *
	 * @param string $taxonomy_name
	 * @return array
	 */
	protected function get_the_term_ids( $taxonomy_name ) {
		$term_ids = [];
		$terms = get_the_terms( get_the_ID(), $taxonomy_name );
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_ids[] = $term->term_id;
			}
		}
		return $term_ids;
	}

	/**
	 * Return the custom taxonomies of the current post
	 *
	 * @param int|null $post_id
	 * @return array
	 */
	protected function get_the_taxonomies( $post_id = null ) {
		$post_type_object = get_post_type_object( get_post_type( $post_id ) );
		if ( ! empty( $post_type_object->taxonomies ) ) {
			return $post_type_object->taxonomies;
		}
		return [];
	}

	/**
	 * Return
	 *
	 * @return boolean
	 */
	public function have_posts() {
		$post_type = get_post_type();
		$tax_query = $this->get_tax_query( $post_type );

		if ( $tax_query ) {
			$posts = $this->get_related_posts( $tax_query );
		}

		if ( empty( $posts ) ) {
			return false;
		}
		return true;
	}
}
