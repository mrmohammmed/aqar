<?php
$city = get_queried_object();
if ( ! $city instanceof WP_Term ) {
	get_header();
	get_footer();
	return;
}

$selected_city_slug = isset( $_GET['city'] ) ? sanitize_title( wp_unslash( $_GET['city'] ) ) : '';
if ( $selected_city_slug ) {
	$selected_city = get_term_by( 'slug', $selected_city_slug, 'aqar_city' );
	if ( $selected_city && ! is_wp_error( $selected_city ) && (int) $selected_city->term_id !== (int) $city->term_id ) {
		$redirect_args = array();
		foreach ( array( 'unit_type', 'min_price', 'max_price', 'sort' ) as $filter_key ) {
			if ( isset( $_GET[ $filter_key ] ) && is_scalar( $_GET[ $filter_key ] ) ) {
				$redirect_args[ $filter_key ] = sanitize_text_field( wp_unslash( $_GET[ $filter_key ] ) );
			}
		}
		$redirect_url = get_term_link( $selected_city );
		if ( ! is_wp_error( $redirect_url ) ) {
			wp_safe_redirect( $redirect_args ? add_query_arg( $redirect_args, $redirect_url ) : $redirect_url );
			exit;
		}
	}
}

get_header();

$page_number = max( 1, absint( get_query_var( 'paged' ) ) );
$unit_type   = isset( $_GET['unit_type'] ) ? sanitize_text_field( wp_unslash( $_GET['unit_type'] ) ) : '';
$area        = isset( $_GET['area'] ) ? sanitize_text_field( wp_unslash( $_GET['area'] ) ) : '';
$min_price   = isset( $_GET['min_price'] ) && is_numeric( wp_unslash( $_GET['min_price'] ) ) ? (float) wp_unslash( $_GET['min_price'] ) : '';
$max_price   = isset( $_GET['max_price'] ) && is_numeric( wp_unslash( $_GET['max_price'] ) ) ? (float) wp_unslash( $_GET['max_price'] ) : '';
$sort        = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'newest';
$sort_options = array(
	'newest'     => __( 'الأحدث أولاً', 'aqar' ),
	'price_asc'  => __( 'السعر من الأقل', 'aqar' ),
	'price_desc' => __( 'السعر من الأعلى', 'aqar' ),
);
if ( ! isset( $sort_options[ $sort ] ) ) {
	$sort = 'newest';
}

$tax_query = array(
	array(
		'taxonomy' => 'aqar_city',
		'field'    => 'term_id',
		'terms'    => $city->term_id,
	),
);
$meta_query = array();
if ( $unit_type ) {
	$meta_query[] = array(
		'key'     => '_aqar_unit_type',
		'value'   => $unit_type,
		'compare' => '=',
	);
}
if ( $area ) {
	$meta_query[] = array(
		'key'     => '_aqar_unit_location',
		'value'   => $area,
		'compare' => '=',
	);
}
if ( '' !== $min_price || '' !== $max_price ) {
	$price_query = array(
		'key'     => '_aqar_unit_price',
		'type'    => 'NUMERIC',
		'compare' => 'BETWEEN',
		'value'   => array(
			'' !== $min_price ? $min_price : 0,
			'' !== $max_price ? $max_price : 999999999999,
		),
	);
	if ( '' === $min_price ) {
		$price_query['compare'] = '<=';
		$price_query['value']   = $max_price;
	} elseif ( '' === $max_price ) {
		$price_query['compare'] = '>=';
		$price_query['value']   = $min_price;
	}
	$meta_query[] = $price_query;
}

$query_args = array(
	'post_type'           => 'aqar_unit',
	'post_status'         => 'publish',
	'posts_per_page'      => 10,
	'paged'               => $page_number,
	'ignore_sticky_posts' => true,
	'tax_query'           => $tax_query,
	'orderby'             => array(
		'date' => 'DESC',
		'ID'   => 'DESC',
	),
);
if ( $meta_query ) {
	$query_args['meta_query'] = $meta_query;
}
if ( 'price_asc' === $sort || 'price_desc' === $sort ) {
	$query_args['meta_key'] = '_aqar_unit_price';
	$query_args['orderby']  = 'meta_value_num';
	$query_args['order']    = 'price_asc' === $sort ? 'ASC' : 'DESC';
}
$units = new WP_Query( $query_args );

$city_units = get_posts(
	array(
		'post_type'      => 'aqar_unit',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'tax_query'      => $tax_query,
	)
);
$unit_types = array();
$areas      = array();
foreach ( $city_units as $city_unit_id ) {
	$type_value = trim( (string) get_post_meta( $city_unit_id, '_aqar_unit_type', true ) );
	$area_value = trim( (string) get_post_meta( $city_unit_id, '_aqar_unit_location', true ) );
	if ( $type_value ) {
		$unit_types[ $type_value ] = $type_value;
	}
	if ( $area_value ) {
		$areas[ $area_value ] = $area_value;
	}
}
$city_options = get_terms(
	array(
		'taxonomy'   => 'aqar_city',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $city_options ) ) {
	$city_options = array();
}
$city_option_ids = wp_list_pluck( $city_options, 'term_id' );
if ( ! in_array( $city->term_id, array_map( 'intval', $city_option_ids ), true ) ) {
	$city_options[] = $city;
}

$featured_units = get_posts(
	array(
		'post_type'      => 'aqar_unit',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'meta_key'       => '_aqar_unit_featured',
		'meta_value'     => '1',
		'tax_query'      => $tax_query,
		'orderby'        => array(
			'date' => 'DESC',
			'ID'   => 'DESC',
		),
	)
);
$related_terms = get_terms(
	array(
		'taxonomy'   => 'aqar_city',
		'hide_empty' => true,
		'parent'     => $city->parent ? $city->parent : $city->term_id,
		'exclude'    => array( $city->term_id ),
		'number'     => 5,
	)
);
$city_faqs = aqar_parse_project_rows( get_term_meta( $city->term_id, '_aqar_city_faqs', true ), 2 );
$articles  = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	)
);
$city_image_id = absint( get_term_meta( $city->term_id, '_aqar_city_image', true ) );
$city_image    = $city_image_id ? wp_get_attachment_image_url( $city_image_id, 'large' ) : '';
$city_link     = get_term_link( $city );
$parent_city   = $city->parent ? get_term( $city->parent, 'aqar_city' ) : false;
$preserved_args = array();
foreach ( array( 'unit_type', 'area', 'min_price', 'max_price', 'sort' ) as $filter_key ) {
	if ( isset( $_GET[ $filter_key ] ) && is_scalar( $_GET[ $filter_key ] ) ) {
		$preserved_args[ $filter_key ] = sanitize_text_field( wp_unslash( $_GET[ $filter_key ] ) );
	}
}
?>
<main class="city-page">
	<section class="city-search-band">
		<div class="container city-container">
			<nav class="city-breadcrumb" aria-label="<?php esc_attr_e( 'مسار التنقل', 'aqar' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'aqar' ); ?></a>
				<span>/</span>
				<?php if ( $parent_city && ! is_wp_error( $parent_city ) ) : ?>
					<a href="<?php echo esc_url( get_term_link( $parent_city ) ); ?>"><?php echo esc_html( $parent_city->name ); ?></a>
					<span>/</span>
				<?php else : ?>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'aqar_unit' ) ); ?>"><?php esc_html_e( 'الوحدات العقارية', 'aqar' ); ?></a>
					<span>/</span>
				<?php endif; ?>
				<span><?php echo esc_html( $city->name ); ?></span>
			</nav>
			<div class="city-title-row">
				<div>
					<h1><?php echo esc_html( sprintf( __( 'عقارات للبيع في %s', 'aqar' ), $city->name ) ); ?></h1>
					<?php if ( term_description( $city->term_id, 'aqar_city' ) ) : ?>
						<div class="city-description"><?php echo wp_kses_post( term_description( $city->term_id, 'aqar_city' ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $city_image ) : ?>
					<div class="city-title-image"><img src="<?php echo esc_url( $city_image ); ?>" alt="<?php echo esc_attr( $city->name ); ?>"></div>
				<?php endif; ?>
				<a class="city-outline-button" href="#property-list"><?php esc_html_e( 'تصفح العقارات', 'aqar' ); ?></a>
			</div>
			<form class="city-search-form" action="<?php echo esc_url( $city_link ); ?>" method="get">
				<label class="city-search-field">
					<span><?php esc_html_e( 'المدينة', 'aqar' ); ?></span>
					<select name="city">
						<?php foreach ( $city_options as $city_option ) : ?>
							<option value="<?php echo esc_attr( $city_option->slug ); ?>" <?php selected( $city->term_id, $city_option->term_id ); ?>><?php echo esc_html( $city_option->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="city-search-field">
					<span><?php esc_html_e( 'نوع الوحدة', 'aqar' ); ?></span>
					<select name="unit_type">
						<option value=""><?php esc_html_e( 'كل الأنواع', 'aqar' ); ?></option>
						<?php foreach ( $unit_types as $type_value ) : ?>
							<option value="<?php echo esc_attr( $type_value ); ?>" <?php selected( $unit_type, $type_value ); ?>><?php echo esc_html( $type_value ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="city-search-field">
					<span><?php esc_html_e( 'المنطقة', 'aqar' ); ?></span>
					<select name="area">
						<option value=""><?php esc_html_e( 'كل المناطق', 'aqar' ); ?></option>
						<?php foreach ( $areas as $area_value ) : ?>
							<option value="<?php echo esc_attr( $area_value ); ?>" <?php selected( $area, $area_value ); ?>><?php echo esc_html( $area_value ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="city-search-field">
					<span><?php esc_html_e( 'أقل سعر', 'aqar' ); ?></span>
					<input type="number" min="0" name="min_price" value="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php esc_attr_e( 'من', 'aqar' ); ?>">
				</label>
				<label class="city-search-field">
					<span><?php esc_html_e( 'أعلى سعر', 'aqar' ); ?></span>
					<input type="number" min="0" name="max_price" value="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php esc_attr_e( 'إلى', 'aqar' ); ?>">
				</label>
				<label class="city-search-field">
					<span><?php esc_html_e( 'ترتيب حسب', 'aqar' ); ?></span>
					<select name="sort">
						<?php foreach ( $sort_options as $sort_key => $sort_label ) : ?>
							<option value="<?php echo esc_attr( $sort_key ); ?>" <?php selected( $sort, $sort_key ); ?>><?php echo esc_html( $sort_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<button type="submit" class="city-search-button"><i class="fas fa-search" aria-hidden="true"></i> <?php esc_html_e( 'بحث', 'aqar' ); ?></button>
			</form>
		</div>
	</section>

	<div class="container city-container">
		<section class="listing-layout" id="property-list">
			<aside class="listing-sidebar">
				<div class="consultation-card">
					<h2><?php esc_html_e( 'احصل على استشارة مجانية', 'aqar' ); ?></h2>
					<p><?php esc_html_e( 'تواصل مع فريقنا لمساعدتك في اختيار الوحدة المناسبة.', 'aqar' ); ?></p>
					<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'اطلب استشارة', 'aqar' ); ?></a>
				</div>
				<?php if ( ! is_wp_error( $related_terms ) && $related_terms ) : ?>
					<div class="related-properties">
						<h2><?php esc_html_e( 'مدن ومناطق ذات صلة', 'aqar' ); ?></h2>
						<?php foreach ( $related_terms as $related_term ) : ?>
							<a href="<?php echo esc_url( get_term_link( $related_term ) ); ?>"><?php echo esc_html( $related_term->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</aside>

			<div class="listing-results">
				<div class="listing-toolbar">
					<div>
						<h2><?php echo esc_html( sprintf( __( 'عقارات للبيع في %s', 'aqar' ), $city->name ) ); ?></h2>
						<p><?php echo esc_html( sprintf( __( 'عدد الوحدات: %s', 'aqar' ), number_format_i18n( $units->found_posts ) ) ); ?></p>
					</div>
				</div>
				<div class="property-list">
					<?php if ( $units->have_posts() ) : ?>
						<?php while ( $units->have_posts() ) : $units->the_post(); ?>
							<?php
							$unit_id          = get_the_ID();
							$project_id       = absint( get_post_meta( $unit_id, '_aqar_unit_project', true ) );
							$project_name     = $project_id ? get_the_title( $project_id ) : '';
							$developer_id     = $project_id ? absint( get_post_meta( $project_id, '_aqar_project_developer', true ) ) : 0;
							$developer_name   = $developer_id ? get_the_title( $developer_id ) : '';
							$unit_status      = get_post_meta( $unit_id, '_aqar_unit_status', true );
							$unit_delivery    = get_post_meta( $unit_id, '_aqar_unit_delivery', true );
							$unit_location    = get_post_meta( $unit_id, '_aqar_unit_location', true );
							$unit_type_value  = get_post_meta( $unit_id, '_aqar_unit_type', true );
							$unit_bedrooms    = get_post_meta( $unit_id, '_aqar_unit_bedrooms', true );
							$unit_bathrooms   = get_post_meta( $unit_id, '_aqar_unit_bathrooms', true );
							$unit_area        = get_post_meta( $unit_id, '_aqar_unit_area', true );
							$unit_price       = get_post_meta( $unit_id, '_aqar_unit_price', true );
							$unit_price_label = get_post_meta( $unit_id, '_aqar_unit_price_label', true );
							$unit_currency    = get_post_meta( $unit_id, '_aqar_unit_price_currency', true );
							$unit_phone       = get_post_meta( $unit_id, '_aqar_unit_phone', true );
							$unit_terms       = wp_get_post_terms( $unit_id, 'aqar_city', array( 'fields' => 'names' ) );
							$unit_image       = get_the_post_thumbnail_url( $unit_id, 'large' );
							if ( ! $unit_image ) {
								$unit_image = get_post_meta( $unit_id, '_aqar_unit_legacy_image', true );
							}
							if ( ! $unit_image ) {
								$unit_image = get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
							}
							?>
							<article class="property-row">
								<div class="property-row__image">
									<img src="<?php echo esc_url( $unit_image ); ?>" alt="<?php the_title_attribute(); ?>">
									<?php if ( $unit_status || $unit_type_value ) : ?><span><?php echo esc_html( $unit_status ? $unit_status : $unit_type_value ); ?></span><?php endif; ?>
									<?php if ( $unit_delivery ) : ?><b><?php echo esc_html( sprintf( __( 'استلام: %s', 'aqar' ), $unit_delivery ) ); ?></b><?php endif; ?>
								</div>
								<div class="property-row__copy">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<?php if ( $unit_location || ( ! is_wp_error( $unit_terms ) && $unit_terms ) ) : ?>
										<p class="property-location"><?php echo esc_html( $unit_location ? $unit_location : implode( '، ', $unit_terms ) ); ?> <i class="fas fa-location-dot" aria-hidden="true"></i></p>
									<?php endif; ?>
									<?php if ( $project_name ) : ?><p class="property-developer"><?php esc_html_e( 'ضمن مشروع', 'aqar' ); ?> <?php echo esc_html( $project_name ); ?><?php if ( $developer_name ) : ?> — <?php esc_html_e( 'المطور', 'aqar' ); ?> <?php echo esc_html( $developer_name ); ?><?php endif; ?></p><?php endif; ?>
									<div class="property-facts">
										<?php if ( $unit_bedrooms ) : ?><span><?php echo esc_html( $unit_bedrooms ); ?> <?php esc_html_e( 'غرف', 'aqar' ); ?></span><?php endif; ?>
										<?php if ( $unit_bathrooms ) : ?><span><?php echo esc_html( $unit_bathrooms ); ?> <?php esc_html_e( 'حمام', 'aqar' ); ?></span><?php endif; ?>
										<?php if ( $unit_area ) : ?><span><?php echo esc_html( $unit_area ); ?> <?php esc_html_e( 'م²', 'aqar' ); ?></span><?php endif; ?>
									</div>
									<div class="property-row__actions">
										<?php if ( $unit_price || $unit_price_label ) : ?><strong><?php echo esc_html( $unit_price_label ? $unit_price_label : number_format_i18n( (float) $unit_price ) ); ?> <?php echo esc_html( $unit_currency ); ?></strong><?php endif; ?>
										<a class="property-row__link" href="<?php the_permalink(); ?>"><i class="far fa-copy" aria-hidden="true"></i> <?php esc_html_e( 'تفاصيل الوحدة', 'aqar' ); ?></a>
										<?php if ( $unit_phone ) : ?>
											<a class="property-contact-icon" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $unit_phone ) ); ?>" aria-label="<?php esc_attr_e( 'اتصال', 'aqar' ); ?>"><i class="fas fa-phone" aria-hidden="true"></i></a>
											<a class="property-contact-icon property-contact-icon--whatsapp" href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $unit_phone ) ); ?>" aria-label="<?php esc_attr_e( 'واتساب', 'aqar' ); ?>"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
										<?php endif; ?>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php else : ?>
						<p class="aqar-empty-state"><?php esc_html_e( 'لا توجد وحدات مطابقة لخيارات البحث في هذه المدينة حتى الآن.', 'aqar' ); ?></p>
					<?php endif; ?>
				</div>
				<?php
				$pagination = paginate_links(
					array(
						'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $page_number,
						'total'     => $units->max_num_pages,
						'type'      => 'list',
						'add_args'  => $preserved_args,
						'prev_text' => '<i class="fas fa-chevron-right" aria-hidden="true"></i>',
						'next_text' => '<i class="fas fa-chevron-left" aria-hidden="true"></i>',
					)
				);
				if ( $pagination ) :
					?>
					<nav class="city-pagination" aria-label="<?php esc_attr_e( 'صفحات النتائج', 'aqar' ); ?>"><?php echo wp_kses_post( $pagination ); ?></nav>
				<?php endif; ?>
			</div>
		</section>

		<?php if ( $featured_units ) : ?>
			<section class="city-section" id="featured-listings">
				<div class="city-section__heading">
					<div><span><?php esc_html_e( 'اختيارات مميزة', 'aqar' ); ?></span><h2><?php echo esc_html( sprintf( __( 'أفضل العقارات في %s', 'aqar' ), $city->name ) ); ?></h2></div>
					<a href="#property-list"><?php esc_html_e( 'عرض كل العقارات', 'aqar' ); ?></a>
				</div>
				<div class="row g-3 property-cards">
					<?php foreach ( $featured_units as $featured_unit ) : ?>
						<?php
						$featured_id       = $featured_unit->ID;
						$featured_image    = get_the_post_thumbnail_url( $featured_id, 'large' );
						$featured_image    = $featured_image ? $featured_image : get_post_meta( $featured_id, '_aqar_unit_legacy_image', true );
						$featured_image    = $featured_image ? $featured_image : get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
						$featured_area     = get_post_meta( $featured_id, '_aqar_unit_area', true );
						$featured_bedrooms = get_post_meta( $featured_id, '_aqar_unit_bedrooms', true );
						$featured_bathroom = get_post_meta( $featured_id, '_aqar_unit_bathrooms', true );
						$featured_price    = get_post_meta( $featured_id, '_aqar_unit_price', true );
						$featured_label    = get_post_meta( $featured_id, '_aqar_unit_price_label', true );
						$featured_currency = get_post_meta( $featured_id, '_aqar_unit_price_currency', true );
						?>
						<div class="col-12 col-md-6 col-lg-4">
							<article class="property-card">
								<a class="property-card__image" href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>">
									<img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php echo esc_attr( $featured_unit->post_title ); ?>">
									<span><?php echo esc_html( get_post_meta( $featured_id, '_aqar_unit_status', true ) ?: __( 'للبيع', 'aqar' ) ); ?></span>
								</a>
								<div class="property-card__body">
									<h3><a href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>"><?php echo esc_html( $featured_unit->post_title ); ?></a></h3>
									<p><i class="fas fa-location-dot" aria-hidden="true"></i> <?php echo esc_html( $city->name ); ?></p>
									<div class="property-card__features">
										<?php if ( $featured_bedrooms ) : ?><span><?php echo esc_html( $featured_bedrooms ); ?> <?php esc_html_e( 'غرف', 'aqar' ); ?></span><?php endif; ?>
										<?php if ( $featured_bathroom ) : ?><span><?php echo esc_html( $featured_bathroom ); ?> <?php esc_html_e( 'حمام', 'aqar' ); ?></span><?php endif; ?>
										<?php if ( $featured_area ) : ?><span><?php echo esc_html( $featured_area ); ?> <?php esc_html_e( 'م²', 'aqar' ); ?></span><?php endif; ?>
									</div>
									<?php if ( $featured_price || $featured_label ) : ?><strong><?php echo esc_html( $featured_label ? $featured_label : number_format_i18n( (float) $featured_price ) ); ?> <?php echo esc_html( $featured_currency ); ?></strong><?php endif; ?>
								</div>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $city_faqs ) : ?>
			<section class="city-section city-faq">
				<div class="city-section__heading"><div><span><?php esc_html_e( 'دليلك العقاري', 'aqar' ); ?></span><h2><?php echo esc_html( sprintf( __( 'أسئلة شائعة عن %s', 'aqar' ), $city->name ) ); ?></h2></div></div>
				<div class="faq-copy">
					<?php foreach ( $city_faqs as $faq ) : ?>
						<?php if ( $faq[0] ) : ?><h3><?php echo esc_html( $faq[0] ); ?></h3><?php endif; ?>
						<?php if ( $faq[1] ) : ?><p><?php echo esc_html( $faq[1] ); ?></p><?php endif; ?>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $articles->have_posts() ) : ?>
			<section class="city-section city-guides">
				<div class="city-section__heading"><div><span><?php esc_html_e( 'مقالات ونصائح', 'aqar' ); ?></span><h2><?php echo esc_html( sprintf( __( 'دليلك للسكن والاستثمار في %s', 'aqar' ), $city->name ) ); ?></h2></div></div>
				<div class="city-articles">
					<?php while ( $articles->have_posts() ) : $articles->the_post(); ?>
						<article class="guide-article">
							<div class="guide-article__meta"><?php echo esc_html( get_the_date() ); ?></div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php if ( has_post_thumbnail() ) : ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?></a><?php endif; ?>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : get_the_content(), 40 ) ); ?></p>
							<div class="guide-actions"><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'اطلب استشارة مجانية', 'aqar' ); ?></a><a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'اقرأ المقال', 'aqar' ); ?></a></div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
