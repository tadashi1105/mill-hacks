<?php
/**
 *
 * @package WordPress
 * @since Mill 1.0.0
 */
?>
<a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title_attribute(); ?>" <?php post_class( [ 'site-p-entry-list', 'site-p-entry-list--secondary', 'site-c-media' ] ); ?>>
	<div class="site-c-media__cell site-c-media__cell--left">
		<?php if ( ! post_password_required() && has_post_thumbnail() ) :
			the_post_thumbnail( 'entry-image', [
				'alt' => the_title_attribute( [ 'echo' => false ] ),
				'class' => 'site-p-entry-list__object site-c-media__object post-thumbnail',
				'style' => 'width: 2.5rem'
			] );
		else : ?>
			<div class="site-p-entry-list__object site-c-media__object site-u-bg-primary post-thumbnail"></div>
		<?php endif; ?>
	</div>
	<div class="site-c-media__cell site-c-media__cell--body">
		<p class="site-p-entry-list__title entry-title">
			<?php echo apply_filters(
				'mill_related_posts_title',
				wp_trim_words(
					get_the_title(),
					apply_filters( 'mill_related_posts_title_length', 40 ),
					apply_filters( 'mill_related_posts_title_more', ' ' . '[&hellip;]' )
				)
			); ?>
		</p>
	</div>
</a>
