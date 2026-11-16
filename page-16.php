<?php get_header(); ?>

<main class="container page-width content-width directory-page">
	<section class="content-section">
		<div class="section-heading">
			<h1><?php esc_html_e( 'شركات التطوير العقاري', 'aqar' ); ?></h1>
		</div>
		<div class="directory-intro">
			<p><?php esc_html_e( 'تعرّف على أفضل المطورين العقاريين واكتشف المشاريع التي يعملون عليها.', 'aqar' ); ?></p>
		</div>
	</section>

	<section class="content-section" id="best-developers">
		<div class="section-heading">
			<h2><?php esc_html_e( 'أفضل المطورين', 'aqar' ); ?></h2>
		</div>
		<div class="grid grid--3">
			<?php aqar_render_developer_cards( true, -1 ); ?>
		</div>
	</section>

	<section class="content-section" id="all-developers">
		<div class="section-heading">
			<h2><?php esc_html_e( 'كل شركات التطوير', 'aqar' ); ?></h2>
		</div>
		<div class="grid grid--3">
			<?php $aqar_all_developers = aqar_render_developer_cards( false, 12, max( 1, absint( get_query_var( 'paged' ) ) ) ); ?>
		</div>
		<?php if ( $aqar_all_developers->max_num_pages > 1 ) : ?>
			<nav class="directory-pagination" aria-label="<?php esc_attr_e( 'صفحات المطورين', 'aqar' ); ?>">
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
							'total'   => $aqar_all_developers->max_num_pages,
						)
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>
