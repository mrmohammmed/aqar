<?php get_header(); ?>

<main class="container page-width content-width directory-page">
	<section class="content-section">
		<div class="section-heading">
			<h1><?php esc_html_e( 'مشاريع جديدة', 'aqar' ); ?></h1>
		</div>
		<div class="directory-intro">
			<p><?php esc_html_e( 'تصفح أحدث المشروعات العقارية المضافة إلى الموقع.', 'aqar' ); ?></p>
		</div>
		<div class="card-grid">
			<?php $aqar_latest_projects = aqar_render_project_cards( false, 12, max( 1, absint( get_query_var( 'paged' ) ) ) ); ?>
		</div>
		<?php if ( $aqar_latest_projects->max_num_pages > 1 ) : ?>
			<nav class="directory-pagination" aria-label="<?php esc_attr_e( 'صفحات المشاريع', 'aqar' ); ?>">
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
							'total'   => $aqar_latest_projects->max_num_pages,
						)
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>
