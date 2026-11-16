<?php get_header(); ?>

<main class="container page-width content-width">
	<section class="content-section">
		<div class="section-heading">
			<h1><?php post_type_archive_title(); ?></h1>
		</div>
		<div class="card-grid">
			<?php $aqar_projects_query = aqar_render_project_cards( false, 12, max( 1, absint( get_query_var( 'paged' ) ) ) ); ?>
		</div>
		<?php
		if ( $aqar_projects_query->max_num_pages > 1 ) {
			echo wp_kses_post(
				paginate_links(
					array(
						'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
						'total'   => $aqar_projects_query->max_num_pages,
					)
				)
			);
		}
		?>
	</section>
</main>

<?php get_footer(); ?>
