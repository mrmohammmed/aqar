<?php get_header(); ?>

<main class="container page-width content-width directory-page">
	<section class="content-section">
		<div class="section-heading">
			<h1><?php esc_html_e( 'المدن والمناطق', 'aqar' ); ?></h1>
		</div>
		<div class="directory-intro">
			<p><?php esc_html_e( 'تصفح الوحدات العقارية حسب المدينة والمنطقة، واختر الموقع المناسب لك.', 'aqar' ); ?></p>
		</div>
	</section>

	<section class="content-section" id="best-cities">
		<div class="section-heading">
			<h2><?php esc_html_e( 'أفضل المدن', 'aqar' ); ?></h2>
		</div>
		<div class="card-grid image-grid">
			<?php aqar_render_city_cards( 'best', -1 ); ?>
		</div>
	</section>

	<section class="content-section" id="all-cities">
		<div class="section-heading">
			<h2><?php esc_html_e( 'كل المدن والمناطق', 'aqar' ); ?></h2>
		</div>
		<div class="card-grid image-grid">
			<?php aqar_render_city_cards( 'all', -1 ); ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
