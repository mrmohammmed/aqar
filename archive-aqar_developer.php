<?php get_header(); ?>

<main class="container page-width content-width">
	<section class="content-section">
		<div class="section-heading">
			<h1><?php post_type_archive_title(); ?></h1>
		</div>
		<div class="grid grid--3">
			<?php $aqar_developers_query = aqar_render_developer_cards( false, 12, max( 1, absint( get_query_var( 'paged' ) ) ) ); ?>
		</div>
		<?php
		if ( $aqar_developers_query->max_num_pages > 1 ) {
			echo wp_kses_post(
				paginate_links(
					array(
						'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
						'total'   => $aqar_developers_query->max_num_pages,
					)
				)
			);
		}
		?>
	</section>
</main>

<?php get_footer(); ?>
