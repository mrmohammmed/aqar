<?php
get_header();

$search_term = trim( get_search_query( false ) );
$matched_cities = array();
if ( $search_term ) {
	$matched_cities = get_terms(
		array(
			'taxonomy'   => 'aqar_city',
			'hide_empty' => false,
			'search'     => $search_term,
			'number'     => 8,
		)
	);
	if ( is_wp_error( $matched_cities ) ) {
		$matched_cities = array();
	}
}

$city_unit_ids = array();
if ( $matched_cities ) {
	$city_unit_ids = get_posts(
		array(
			'post_type'      => 'aqar_unit',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'aqar_city',
					'field'    => 'term_id',
					'terms'    => wp_list_pluck( $matched_cities, 'term_id' ),
				),
			),
		)
	);
}

$extra_location_results = array();
if ( $search_term ) {
	$extra_location_results = get_posts(
		array(
			'post_type'      => array( 'aqar_project', 'aqar_unit' ),
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => '_aqar_project_location',
					'value'   => $search_term,
					'compare' => 'LIKE',
				),
				array(
					'key'     => '_aqar_unit_location',
					'value'   => $search_term,
					'compare' => 'LIKE',
				),
			),
		)
	);
}

$shown_post_ids = array();
?>
<main class="container page-width content-width search-page">
	<section class="content-section">
		<div class="section-heading">
			<h1><?php echo esc_html( sprintf( __( 'نتائج البحث عن: %s', 'aqar' ), $search_term ) ); ?></h1>
		</div>
		<form class="search-box search-box--results" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input class="form-control" type="search" name="s" value="<?php echo esc_attr( $search_term ); ?>" placeholder="<?php esc_attr_e( 'ابحث عن مدينة، مشروع أو وحدة...', 'aqar' ); ?>" aria-label="<?php esc_attr_e( 'ابحث عن مدينة، مشروع أو وحدة', 'aqar' ); ?>" required>
			<button class="btn" type="submit" aria-label="<?php esc_attr_e( 'بحث', 'aqar' ); ?>"><i class="fas fa-search" aria-hidden="true"></i></button>
		</form>
	</section>

	<?php if ( $matched_cities ) : ?>
		<section class="content-section search-section">
			<div class="section-heading"><h2><?php esc_html_e( 'المدن والمناطق', 'aqar' ); ?></h2></div>
			<div class="card-grid image-grid">
				<?php foreach ( $matched_cities as $city ) : ?>
					<?php
					$city_image_id = absint( get_term_meta( $city->term_id, '_aqar_city_image', true ) );
					$city_image    = $city_image_id ? wp_get_attachment_image_url( $city_image_id, 'large' ) : '';
					if ( ! $city_image ) {
						$city_image = get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
					}
					$city_link = get_term_link( $city );
					?>
					<?php if ( ! is_wp_error( $city_link ) ) : ?>
						<a class="image-card" href="<?php echo esc_url( $city_link ); ?>">
							<img class="city-image" src="<?php echo esc_url( $city_image ); ?>" alt="<?php echo esc_attr( $city->name ); ?>">
							<div class="city-card__content"><h3><?php echo esc_html( $city->name ); ?></h3></div>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php if ( $city_unit_ids ) : ?>
				<p class="search-city-units-link">
					<a href="<?php echo esc_url( get_term_link( $matched_cities[0] ) ); ?>"><?php echo esc_html( sprintf( __( 'عرض الوحدات في %s', 'aqar' ), $matched_cities[0]->name ) ); ?></a>
				</p>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<section class="content-section search-section">
			<div class="section-heading"><h2><?php esc_html_e( 'المشاريع والوحدات والمقالات', 'aqar' ); ?></h2></div>
			<div class="search-results-grid">
				<?php while ( have_posts() ) : ?>
					<?php
					the_post();
					$post_id       = get_the_ID();
					$post_type     = get_post_type();
					$shown_post_ids[] = $post_id;
					$image         = get_the_post_thumbnail_url( $post_id, 'large' );
					$unit_project  = 'aqar_unit' === $post_type ? absint( get_post_meta( $post_id, '_aqar_unit_project', true ) ) : 0;
					$unit_cities   = 'aqar_unit' === $post_type ? wp_get_post_terms( $post_id, 'aqar_city' ) : array();
					$unit_location = 'aqar_unit' === $post_type ? get_post_meta( $post_id, '_aqar_unit_location', true ) : '';
					$project_location = 'aqar_project' === $post_type ? get_post_meta( $post_id, '_aqar_project_location', true ) : '';
					$result_badge  = 'aqar_project' === $post_type ? __( 'مشروع', 'aqar' ) : ( 'aqar_unit' === $post_type ? __( 'وحدة عقارية', 'aqar' ) : __( 'مقال', 'aqar' ) );
					if ( ! $image && 'aqar_unit' === $post_type ) {
						$image = get_post_meta( $post_id, '_aqar_unit_legacy_image', true );
					}
					if ( ! $image ) {
						$image = get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
					}
					?>
					<article class="search-result-card">
						<a class="search-result-card__image" href="<?php the_permalink(); ?>">
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php the_title_attribute(); ?>">
							<span><?php echo esc_html( $result_badge ); ?></span>
						</a>
						<div class="search-result-card__body">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php if ( $unit_project && get_post_status( $unit_project ) ) : ?>
								<p><?php esc_html_e( 'المشروع:', 'aqar' ); ?> <a href="<?php echo esc_url( get_permalink( $unit_project ) ); ?>"><?php echo esc_html( get_the_title( $unit_project ) ); ?></a></p>
							<?php endif; ?>
							<?php if ( $unit_location ) : ?>
								<p><?php echo esc_html( $unit_location ); ?></p>
							<?php elseif ( ! is_wp_error( $unit_cities ) && $unit_cities ) : ?>
								<p><?php echo esc_html( implode( '، ', wp_list_pluck( $unit_cities, 'name' ) ) ); ?></p>
							<?php elseif ( $project_location ) : ?>
								<p><?php echo esc_html( $project_location ); ?></p>
							<?php endif; ?>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : get_the_content(), 22 ) ); ?></p>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		</section>
	<?php endif; ?>

	<?php
$extra_results = array();
foreach ( $extra_location_results as $location_result ) {
	if ( ! in_array( $location_result->ID, $shown_post_ids, true ) && ! in_array( $location_result->ID, $city_unit_ids, true ) ) {
		$extra_results[] = $location_result;
	}
}
if ( $extra_results ) :
	?>
		<section class="content-section search-section">
			<div class="section-heading"><h2><?php esc_html_e( 'نتائج حسب الموقع', 'aqar' ); ?></h2></div>
			<div class="search-results-grid">
				<?php foreach ( $extra_results as $result ) : ?>
					<?php
					$result_image = get_the_post_thumbnail_url( $result->ID, 'large' );
					if ( ! $result_image && 'aqar_unit' === $result->post_type ) {
						$result_image = get_post_meta( $result->ID, '_aqar_unit_legacy_image', true );
					}
					if ( ! $result_image ) {
						$result_image = get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
					}
					?>
					<article class="search-result-card">
						<a class="search-result-card__image" href="<?php echo esc_url( get_permalink( $result->ID ) ); ?>">
							<img src="<?php echo esc_url( $result_image ); ?>" alt="<?php echo esc_attr( $result->post_title ); ?>">
							<span><?php echo esc_html( 'aqar_unit' === $result->post_type ? __( 'وحدة عقارية', 'aqar' ) : __( 'مشروع', 'aqar' ) ); ?></span>
						</a>
						<div class="search-result-card__body">
							<h3><a href="<?php echo esc_url( get_permalink( $result->ID ) ); ?>"><?php echo esc_html( $result->post_title ); ?></a></h3>
							<p><?php echo esc_html( get_post_meta( $result->ID, '_aqar_' . ( 'aqar_unit' === $result->post_type ? 'unit' : 'project' ) . '_location', true ) ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! have_posts() && ! $matched_cities && ! $extra_results ) : ?>
		<section class="content-section search-empty-state">
			<h2><?php esc_html_e( 'لم نعثر على نتائج مطابقة', 'aqar' ); ?></h2>
			<p><?php esc_html_e( 'جرّب البحث باسم مدينة أو مشروع أو وحدة عقارية.', 'aqar' ); ?></p>
		</section>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
