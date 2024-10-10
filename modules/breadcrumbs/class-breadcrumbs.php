<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */

/**
 * Name       : Mill Breadcrumbs
 * Version    : 1.0.0
 * Author     : Tadashi Matsuura
 * Author URI : http://millkeyweb.com
 * Created    : April 20, 2015
 * Modified   :
 * License    : GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * This class handles the Breadcrumbs generation and display
 */
class Mill_Breadcrumbs {

	/**
	 * @var object instance of this class
	 */
	private static $instance;

	/**
	 * @var string Blog's show on front setting, 'page' or 'posts'
	 */
	private $show_on_front;

	/**
	 * @var mixed Blog's page for posts setting, page id or false
	 */
	private $page_for_posts;

	/**
	 * @var mixed Current post object
	 */
	private $post;

	/**
	 * @var array Array of options from crumbs
	 */
	private $options = [];

	/**
	 * Each element of the crumbs array have these keys:
	 *     'text':      => for page title or post title;
	 *     'url':       => for a page link;
	 *     'allow_html' => true or false;
	 *
	 * @var array Array of crumbs
	 */
	private $crumbs = [];

	/**
	 * @var array Count of the elements in the $crumbs property
	 */
	private $crumb_count = 0;

	/**
	 * @var array Array of individual (linked) html strings created from crumbs
	 */
	private $links = [];

	/**
	 * @var string Breadcrumb html string
	 */
	private $output;

	/**
	 * create the breadcrumb
	 */
	private function __construct( array $args = [] ) {
		$defaults = [
			'attr' => [
				'class' => 'breadcrumbs',
				'id'    => 'breadcrumbs',
			],
			'separator'   => '<span> &gt; </span>',
			'wrap_format' => '<nav %1$s>%2$s</nav>',
			'link_format' => '<a href="%1$s">%2$s</a>',
			'last_format' => '<span>%1$s</span>',
			'before'      => '',
			'after'       => '',
			'link_before' => '',
			'link_after'  => '',
			'front_label' => '',
			'home_label'  => __( 'Home', 'mill' ),
			'echo'        => false,
		];

		$this->options = wp_parse_args( $args, $defaults );
		$this->options = (object) $this->options;
		$this->show_on_front = get_option( 'show_on_front' );
		$this->page_for_posts = get_option( 'page_for_posts' );

		$this->set_crumbs();
		$this->prepare_links();
		$this->links_to_string();
	}

	/**
	 * get breadcrumb string using the singleton instance of this class
	 *
	 * @param array $args
	 * @return string
	 */
	public static function get_instance( array $args = [] ) {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self( $args );
		}
		if ( self::$instance->options->echo === true ) {
			echo self::$instance->output;
			return true;
		} else {
			return self::$instance->output;
		}
	}

	/**
	 * Determine the crumbs which should form the breadcrumb.
	 */
	private function set_crumbs() {

		$post_type = get_post_type();
		$this->add_home_crumb();

		/**
		 * (表示設定->フロントページの表示->固定ページ->投稿ページ)
		 * が設定されている場合、カテゴリー、タグ、シングルページに
		 * Add blog home
		 */
		if (
			( $this->show_on_front === 'page' && $post_type === 'post' )
			&&
			! is_search()
			&&
			$this->page_for_posts
		) {
			$this->add_blog_crumb();
		} elseif (
			get_post_type_archive_link( $post_type )
			&&
			! is_search()
		) {
			$this->add_post_type_archive_crumb();
		}


		if (
			( $this->show_on_front === 'page' && is_front_page() )
			||
			( $this->show_on_front === 'posts' && is_home() )
		) {
			// Do nothing
		} elseif ( is_404() ) {
			$this->add_404_crumb();
		} elseif ( is_search() ) {
			$this->add_search_crumb();
		} elseif ( is_singular() ) {
			// Posted page that does not have a parent
			$post_parent_id = wp_get_post_parent_id( get_the_ID() );
			if ( isset( $post_parent_id ) && $post_parent_id == 0 ) {
				$this->add_single_post_crumb();
			} else {
				$this->add_page_crumb();
			}
		} elseif ( is_archive() ) {
			if ( is_tax() || is_tag() || is_category() ) {
				$this->add_tax_crumb();
			} elseif ( is_author() ) {
				$this->add_author_crumb();
			} elseif ( is_date() ) {
				if ( is_day() ) {
					$this->add_day_crumb();
				} elseif ( is_month() ) {
					$this->add_month_crumb();
				} elseif ( is_year() ) {
					$this->add_year_crumb();
				}
			}
		}

		$this->crumb_count = count( $this->crumbs );
	}

	/**
	 * Take the crumbs array and convert each crumb to a single breadcrumb string.
	 *
	 * @link http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417 Google documentation on RDFA
	 */
	private function prepare_links() {
		if ( ! is_array( $this->crumbs ) || $this->crumbs === [] ) {
			return;
		}
		foreach ( $this->crumbs as $i => $crumb ) {
			$this->links[] = $this->crumb_to_link( $crumb, $i );
		}
	}

	/**
	 * Create a breadcrumb element string
	 *
	 * @todo The `$paged` variable only works for archives, not for paged articles, so this does not work
	 * for paged article at this moment.
	 *
	 * @param array $link Link info array containing the keys:
	 *                     'text'    => (string) link text
	 *                     'url'    => (string) link url
	 *                     (optional) 'allow_html'    => (bool) whether to (not) escape html in the link text
	 *                     This prevents html stripping from the text strings set in the
	 *                     WPSEO -> Internal Links options page.
	 * @param int $i Index for the current breadcrumb.
	 * @return string
	 */
	private function crumb_to_link( $link, $i ) {
		$link_output = '';
		if (
			isset( $link['text'] )
			&&
			( is_string( $link['text'] ) && $link['text'] !== '' )
		) {
			$link['text'] = trim( $link['text'] );
			if ( ! isset( $link['allow_html'] ) || $link['allow_html'] !== true ) {
				$link['text'] = esc_html( $link['text'] );
			}

			if (
				(
					isset( $link['url'] )
					&&
					(
						is_string( $link['url'] )
						&&
						$link['url'] !== ''
					)
				)
				&&
				( $i < ( $this->crumb_count - 1 ) )
			) {
				if ( $i === 0 ) {
					$link_output .= $this->options->before;
				} else {
					$link_output .= $this->options->before;
				}
				$link_text  = $this->options->link_before;
				$link_text .= $link['text'];
				$link_text .= $this->options->link_after;
				$link_output .= sprintf(
					$this->options->link_format,
					esc_url( $link['url'] ),
					$link_text,
					$i + 1
				);

				$link_output .= $this->options->after;
			} else {
				$link_text  = $this->options->link_before;
				$link_text .= $link['text'];
				$link_text .= $this->options->link_after;
				$link_output .= $this->options->before;
				$link_output .= sprintf(
					$this->options->last_format,
					$link_text
				);
				$link_output .= $this->options->after;
			}
		}

		/**
		 * Filter: 'wpseo_breadcrumb_single_link' - Allow changing of each link being put out by the Yoast SEO breadcrumbs class
		 *
		 * @api string $link_output The output string
		 *
		 * @param array $link The link array.
		 */
		return $link_output;
	}

	/**
	 * Create a complete breadcrumb string from an array of breadcrumb element strings
	 */
	private function links_to_string() {
		if ( is_array( $this->links ) && $this->links !== [] ) {
			// Remove any effectively empty links.
			$links = array_map( 'trim', $this->links );
			$links = array_filter( $links );
			$this->output = implode( $this->options->separator, $links );
			if ( isset( $this->options->wrap_format )
				&& $this->options->wrap_format !== ''
			) {

				$attr = '';
				if (
					isset( $this->options->attr )
					&&
					$this->options->attr !== []
				) {
					foreach ( $this->options->attr as $attr_name => $attr_value ) {
						if ( ! empty( $attr_value ) ) {
							$attr .= sprintf(
								' %1$s="%2$s"',
								esc_attr( $attr_name ),
								esc_attr( $attr_value )
							);
						}
					}
				}

				$output = sprintf( $this->options->wrap_format, $attr, $this->output );
				$this->output = $output;
			}
		}
	}

	/**
	 * Find the deepest term in an array of term objects
	 *
	 * @param  array $terms
	 * @return object
	 */
	private function find_deepest_term( $terms ) {
		/**
		 * Let's find the deepest term in this array, by looping through and then
		 * unsetting every term that is used as a parent by another one in the array.
		 */
		$terms_by_id = [];
		foreach ( $terms as $term ) {
			$terms_by_id[ $term->term_id ] = $term;
		}
		foreach ( $terms as $term ) {
			unset( $terms_by_id[ $term->parent ] );
		}
		unset( $term );

		/**
		 * As we could still have two subcategories, from different parent categories,
		 * let's pick the one with the lowest ordered ancestor.
		 */
		$parents_count = 0;
		reset( $terms_by_id );
		$deepest_term = current( $terms_by_id );
		foreach ( $terms_by_id as $term ) {
			$parents = $this->get_term_parents( $term );
			if ( count( $parents ) >= $parents_count ) {
				$parents_count = count( $parents );
				$deepest_term = $term;
			}
		}
		return $deepest_term;
	}

	/**
	 * Get a term's parents.
	 *
	 * @param object $term Term to get the parents for.
	 * @return array
	 */
	private function get_term_parents( $term ) {
		$tax     = $term->taxonomy;
		$parents = array();
		while ( $term->parent != 0 ) {
			$term      = get_term( $term->parent, $tax );
			$parents[] = $term;
		}
		return array_reverse( $parents );
	}

	/**
	 * Add a predefined crumb to the crumbs property
	 *
	 * @param string $text
	 * @param string $url
	 * @param bool   $allow_html
	 */
	private function add_predefined_crumb( $text, $url = '', $allow_html = false ) {
		$this->crumbs[] = [
			'text'       => $text,
			'url'        => $url,
			'allow_html' => $allow_html,
		];
	}

	/**
	 * 指定されたページもしくはタクソノミーの先祖をセット
	 *
	 * @param int $object_id Post id or term id
	 * @param string $object_type Post type or taxonomy
	 */
	private function add_ancestor_crumbs( $object_id, $object_type ) {
		$ancestor_ids = get_ancestors( $object_id, $object_type );
		if ( is_array( $ancestor_ids ) && $ancestor_ids === [] ) {
			return;
		}
		$post_parent_id = wp_get_post_parent_id( get_the_ID() );

		krsort( $ancestor_ids );
		if ( is_singular()
			&& isset( $post_parent_id )
			&& ( $post_parent_id !== 0 )
		) {
			foreach ( $ancestor_ids as $ancestor_id ) {
				$this->add_predefined_crumb(
					get_the_title( $ancestor_id ),
					get_permalink( $ancestor_id )
				);
			}
		} else {
			foreach ( $ancestor_ids as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, $object_type );
				$this->add_predefined_crumb(
					$ancestor->name,
					get_term_link( $ancestor )
				);
			}
		}
	}

	/**
	 * Add Homepage crumb to the crumbs property
	 */
	private function add_home_crumb() {
		$this->add_predefined_crumb(
			$this->get_home_label(),
			get_home_url()
		);
	}

	/**
	 * Add Blog crumb to the crumbs property
	 */
	private function add_blog_crumb() {
		$this->add_predefined_crumb(
			get_the_title( $this->page_for_posts ),
			get_permalink( $this->page_for_posts )
		);
	}

	/**
	 * Add Search crumb to the crumbs property
	 */
	private function add_search_crumb() {
		$this->add_predefined_crumb(
			sprintf(
				__( 'Search Results for: %s', 'mill' ),
				esc_html( get_search_query() )
			),
			null
		);
	}

	/**
	 * Add Author crumb to the crumbs property
	 */
	private function add_author_crumb() {
		$author_id = get_query_var( 'author' );
		$this->add_predefined_crumb(
			get_the_author_meta( 'display_name', $author_id ),
			null
		);
	}

	/**
	 * Add Page crumb to the crumbs property
	 */
	private function add_page_crumb() {
		$this->add_ancestor_crumbs( get_the_ID(), get_post_type() );
		$this->add_predefined_crumb(
			get_the_title(),
			get_permalink()
		);
	}

	/**
	 * Add Single crumb to the crumbs property
	 */
	private function add_single_post_crumb() {
		$post_type = get_post_type();
		$post_id = get_the_ID();
		$taxonomies = get_post_taxonomies( $post_id );
		$have_terms = false;

		if ( is_array( $taxonomies ) && $taxonomies !== [] ) {
			if ( isset( $post_type ) && ( $post_type !== 'post' ) && !$have_terms ) {
                /**
				 * Exclude non-custom taxonomies
                 */
				$custom_taxnomies = [];
				$exclude_taxonomies = [
					'category',
					'post_tag',
					'nav_menu',
					'link_category',
					'post_format'
				];
				foreach ( $taxonomies as $taxonomy ) {
					$exclude_taxonomy = array_search( $taxonomy, $exclude_taxonomies );
					if( $exclude_taxonomy !== false ) {
						continue;
					}
					$custom_taxnomies[] = $taxonomy;
				}
				unset( $taxonomy );

				$custom_taxonomy = array_shift( $custom_taxnomies );
				$terms = get_the_terms( $post_id, $custom_taxonomy );
				if ( is_array( $terms ) && $terms !== [] ) {
					$have_terms = true;
				}
			}

			if ( in_array( 'category', $taxonomies ) && !$have_terms ) {
				$terms = get_the_category( $post_id );
				if ( is_array( $terms ) && $terms !== [] ) {
					$have_terms = true;
				}
			}

			if ( in_array( 'post_tag', $taxonomies ) && !$have_terms ) {
				$terms = get_the_tags( $post_id );
				if ( is_array( $terms ) && $terms !== [] ) {
					$have_terms = true;
				}
			}

			if ( $terms && $have_terms) {
				$term = $this->find_deepest_term( $terms );
				$this->add_ancestor_crumbs( $term->term_id, $term->taxonomy );
				if ( $term ) {
					$this->add_predefined_crumb(
						$term->name,
						get_term_link( $term )
					);
				}
			}
		}
		$this->add_predefined_crumb(
			get_the_title(),
			get_permalink()
		);
	}

	/**
	 * Add Post type archive crumb to the crumbs property
	 */
	private function add_post_type_archive_crumb() {
		$post_type = get_post_type();
		if ( isset( $post_type ) && get_post_type_archive_link( $post_type ) ) {
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_archive_title = '';
			if ( is_object( $post_type_obj ) ) {
				if ( isset( $post_type_obj->label ) && $post_type_obj->label !== '' ) {
					$post_type_archive_title = $post_type_obj->label;
				} elseif ( isset( $post_type_obj->labels->menu_name )
					&& $post_type_obj->labels->menu_name !== ''
				) {
					$post_type_archive_title = $post_type_obj->labels->menu_name;
				} else {
					$post_type_archive_title = $post_type_obj->name;
				}
			}
			$this->add_predefined_crumb(
				$post_type_archive_title,
				get_post_type_archive_link( $post_type )
			);
		}
	}

	/**
	 * Add 404 crumb to the crumbs property
	 */
	private function add_404_crumb() {
		if ( get_query_var( 'year' ) !== 0
			|| ( get_query_var( 'monthnum' ) !== 0
			|| get_query_var( 'day' ) !== 0
		)
		) {
			if ( $this->show_on_front == 'page'
				&& !is_home()
				&& $this->page_for_posts
			) {
				$this->add_blog_crumb();
			}

			if ( get_query_var( 'day' ) !== 0 ) {
				$this->add_day_crumb();
			} elseif ( get_query_var( 'monthnum' ) !== 0 ) {
				$this->add_month_crumb();
			} elseif ( get_query_var( 'year' ) !== 0 ) {
				$this->add_year_crumb();
			}
		} else {
			$this->add_predefined_crumb(
				__( 'Page not found', 'mill' ),
				null
			);
		}
	}

	/**
	 * Add Taxonomy crumb to crumbs property
	 */
	private function add_tax_crumb() {
		$term = $GLOBALS['wp_query']->get_queried_object();
		$this->add_ancestor_crumbs( $term->term_id, $term->taxonomy );
		$this->add_predefined_crumb(
			$term->name,
			get_term_link( $term )
		);
	}

	/**
	 * Add day crumb to crumbs property
	 */
	private function add_day_crumb() {
		$year = get_query_var( 'year' );
		$month = get_query_var( 'monthnum' );
		$day = get_query_var( 'day' );
		$this->add_month_crumb();
		$this->add_predefined_crumb(
			$this->get_day_label( $day ),
			get_day_link( $year, $month, $day )
		);
	}

	/**
	 * Add month crumb to crumbs property
	 */
	private function add_month_crumb() {
		$year = get_query_var( 'year' );
		$month = get_query_var( 'monthnum' );
		$this->add_year_crumb();
		$this->add_predefined_crumb(
			$GLOBALS['wp_locale']->get_month( $month ),
			get_month_link( $year, $month )
		);
	}

	/**
	 * Add year crumb to crumbs property
	 */
	private function add_year_crumb() {
		$year =  get_query_var( 'year' );
		$this->add_predefined_crumb(
			$this->get_year_label( $year ),
			get_year_link( $year )
		);
	}

	/**
	 * Get home label
	 *
	 * @return string
	 */
	private function get_home_label() {
		$page_on_front = get_option( 'page_on_front' );
		$home_label    = $this->options->home_label;
		$front_label   = $this->options->front_label;
		if ( $page_on_front && ( $front_label === '' ) ) {
			$home_label = get_the_title( $page_on_front );
		} elseif ( $page_on_front && $front_label ) {
			$home_label = $front_label;
		}
		return $home_label;
	}

	/**
	 * Get year label
	 *
	 * @param string $year
	 * @return string
	 */
	private function get_year_label( $year ) {
		if ( get_locale() === 'ja' ) {
			$year .= '年';
		}
		return $year;
	}

	/**
	 * Get day label
	 *
	 * @param string $day
	 * @return string
	 */
	private function get_day_label( $day ) {
		if ( get_locale() === 'ja' ) {
			$day .= '日';
		}
		return $day;
	}
}
