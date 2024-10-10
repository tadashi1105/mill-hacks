<?php
/**
 *
 *
 * @package WordPress
 * @since Mill 1.0.0
 */
?>
<div class="site-p-author-box">
	<div class="site-p-author-box__bio-bg"></div>
	<div class="site-p-author-box__avatar">
		<a href="<?php echo esc_url( $url ); ?>" title="<?php echo $attr_title; ?>" rel="bookmark">
		<?php
		echo get_avatar(
			$user_id,
			80,
			'',
			$display_name,
			[ 'class' => 'site-p-author-box__avatar-img' ]
		);
		?>
		</a>
	</div>
	<ul class="site-c-nav site-c-nav--inline" role="tablist">
		<li class="site-c-nav__item" role="presentation">
			<a href="#<?php echo esc_attr( $tab_bio ); ?>" class="site-c-nav__link active site-c-btn site-c-btn--primary site-c-btn--sm" role="tab" aria-controls="bio" data-toggle="tab">
				<?php esc_html_e( 'Bio', 'mill' ); ?>
			</a>
		</li>
		<li class="site-c-nav__item" role="presentation">
			<a href="#<?php echo esc_attr( $tab_posts ); ?>" class="site-c-nav__link site-c-btn site-c-btn--primary site-c-btn--sm" role="tab" aria-controls="posts" data-toggle="tab">
				<?php esc_html_e( 'Latest Posts', 'mill' ); ?>
			</a>
		</li>
	</ul>

	<div class="site-c-tabs__content site-c-card__block">
		<div id="<?php echo esc_attr( $tab_bio ); ?>" class="site-c-tabs__tab-pane fade in active" role="tabpanel">
			<div class="site-p-author-box__description">
				<p class="site-p-author-box__name">
					<a href="<?php echo esc_url( $url ); ?>" title="<?php echo $attr_title; ?>" rel="bookmark" class="site-p-author-box__link">
						<?php echo esc_html( $display_name ); ?>
					</a>
				</p>
				<?php echo $description; ?>
			</div>
		</div>

		<div id="<?php echo esc_attr( $tab_posts ); ?>" class="site-c-tabs__tab-pane fade">
			<div class="site-p-author-box__posts">
				<p class="site-p-author-box__title">
					<?php esc_html_e( 'Latest posts by ', 'mill' ); echo esc_html( $display_name );  ?>
					<small>(<a href="<?php echo esc_url( $url ); ?>" class="site-p-author-box__link"><?php esc_html_e( 'see all', 'mill' ) ?></a>)</small>
				</p>
				<?php if ( $latest_posts_by_author->have_posts() ) : ?>
					<ul class="site-p-author-box__posts-list">
						<?php while ( $latest_posts_by_author->have_posts() ) : $latest_posts_by_author->the_post(); ?>
							<li class="site-p-author-box__posts-item">
								<a href="<?php the_permalink();?>" class="site-p-author-box__link"><?php the_title(); ?></a>
								<small> - <?php echo esc_html( date_i18n( get_option( 'date_format' ), get_the_time( 'U' ) ) ); ?></small>
							</li>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</ul>
				<?php else : ?>
					<p class="site-p-author-box__title"><?php esc_html_e( 'Nothing Found', 'mill' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
