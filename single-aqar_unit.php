<?php
get_header();

while ( have_posts() ) :
	the_post();
	$unit_id         = get_the_ID();
	$project_id      = absint( get_post_meta( $unit_id, '_aqar_unit_project', true ) );
	$unit_type       = get_post_meta( $unit_id, '_aqar_unit_type', true );
	$unit_status     = get_post_meta( $unit_id, '_aqar_unit_status', true );
	$unit_location   = get_post_meta( $unit_id, '_aqar_unit_location', true );
	$unit_delivery   = get_post_meta( $unit_id, '_aqar_unit_delivery', true );
	$unit_bedrooms   = get_post_meta( $unit_id, '_aqar_unit_bedrooms', true );
	$unit_bathrooms  = get_post_meta( $unit_id, '_aqar_unit_bathrooms', true );
	$unit_area       = get_post_meta( $unit_id, '_aqar_unit_area', true );
	$unit_price      = get_post_meta( $unit_id, '_aqar_unit_price', true );
	$unit_price_label = get_post_meta( $unit_id, '_aqar_unit_price_label', true );
	$unit_currency   = get_post_meta( $unit_id, '_aqar_unit_price_currency', true );
	$unit_installment = get_post_meta( $unit_id, '_aqar_unit_installment', true );
	$unit_installment_label = get_post_meta( $unit_id, '_aqar_unit_installment_label', true );
	$unit_installment_currency = get_post_meta( $unit_id, '_aqar_unit_installment_currency', true );
	$unit_phone      = get_post_meta( $unit_id, '_aqar_unit_phone', true );
	$unit_image      = get_the_post_thumbnail_url( $unit_id, 'large' );
	if ( ! $unit_image ) {
		$unit_image = get_post_meta( $unit_id, '_aqar_unit_legacy_image', true );
	}
	if ( ! $unit_image ) {
		$unit_image = get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
	}
	$city_terms    = wp_get_post_terms( $unit_id, 'aqar_city' );
	$developer_id  = $project_id ? absint( get_post_meta( $project_id, '_aqar_project_developer', true ) ) : 0;
	$developer_name = $developer_id ? get_the_title( $developer_id ) : '';
	?>
	<main class="city-page unit-details-page">
		<div class="container city-container">
			<nav class="city-breadcrumb" aria-label="<?php esc_attr_e( 'مسار التنقل', 'aqar' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'aqar' ); ?></a>
				<span>/</span>
				<?php if ( ! is_wp_error( $city_terms ) && $city_terms ) : ?>
					<a href="<?php echo esc_url( get_term_link( $city_terms[0] ) ); ?>"><?php echo esc_html( $city_terms[0]->name ); ?></a>
					<span>/</span>
				<?php endif; ?>
				<span><?php the_title(); ?></span>
			</nav>
			<article class="unit-details">
				<div class="unit-details__image"><img src="<?php echo esc_url( $unit_image ); ?>" alt="<?php the_title_attribute(); ?>"></div>
				<div class="unit-details__content">
					<?php if ( $unit_status || $unit_type ) : ?><p class="property-tag"><?php echo esc_html( $unit_status ? $unit_status : $unit_type ); ?></p><?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<?php if ( $unit_location || ( ! is_wp_error( $city_terms ) && $city_terms ) ) : ?>
						<p class="property-location"><i class="fas fa-location-dot" aria-hidden="true"></i> <?php echo esc_html( $unit_location ? $unit_location : implode( '، ', wp_list_pluck( $city_terms, 'name' ) ) ); ?></p>
					<?php endif; ?>
					<?php if ( $project_id && get_post_status( $project_id ) ) : ?>
						<p><?php esc_html_e( 'ضمن مشروع:', 'aqar' ); ?> <a href="<?php echo esc_url( get_permalink( $project_id ) ); ?>"><?php echo esc_html( get_the_title( $project_id ) ); ?></a></p>
					<?php endif; ?>
					<?php if ( $developer_name ) : ?><p><?php esc_html_e( 'المطور العقاري:', 'aqar' ); ?> <?php echo esc_html( $developer_name ); ?></p><?php endif; ?>
					<?php if ( $unit_delivery ) : ?><p><?php esc_html_e( 'موعد الاستلام:', 'aqar' ); ?> <?php echo esc_html( $unit_delivery ); ?></p><?php endif; ?>
					<div class="unit-details__facts">
						<?php if ( $unit_type ) : ?><div><span><?php esc_html_e( 'نوع الوحدة', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_type ); ?></strong></div><?php endif; ?>
						<?php if ( $unit_bedrooms ) : ?><div><span><?php esc_html_e( 'غرف النوم', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_bedrooms ); ?></strong></div><?php endif; ?>
						<?php if ( $unit_bathrooms ) : ?><div><span><?php esc_html_e( 'الحمامات', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_bathrooms ); ?></strong></div><?php endif; ?>
						<?php if ( $unit_area ) : ?><div><span><?php esc_html_e( 'المساحة', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_area ); ?> <?php esc_html_e( 'م²', 'aqar' ); ?></strong></div><?php endif; ?>
					</div>
					<?php if ( $unit_price || $unit_price_label || $unit_installment || $unit_installment_label ) : ?>
						<div class="unit-details__prices">
							<?php if ( $unit_price || $unit_price_label ) : ?><div><span><?php esc_html_e( 'السعر الإجمالي', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_price_label ? $unit_price_label : number_format_i18n( (float) $unit_price ) ); ?> <?php echo esc_html( $unit_currency ); ?></strong></div><?php endif; ?>
							<?php if ( $unit_installment || $unit_installment_label ) : ?><div><span><?php esc_html_e( 'أقل قسط شهري', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_installment_label ? $unit_installment_label : number_format_i18n( (float) $unit_installment ) ); ?> <?php echo esc_html( $unit_installment_currency ); ?></strong></div><?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $unit_phone ) : ?>
						<div class="unit-details__actions">
							<a class="city-search-button" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $unit_phone ) ); ?>"><i class="fas fa-phone" aria-hidden="true"></i> <?php esc_html_e( 'اتصل الآن', 'aqar' ); ?></a>
							<a class="unit-details__whatsapp" href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $unit_phone ) ); ?>"><i class="fab fa-whatsapp" aria-hidden="true"></i> <?php esc_html_e( 'تواصل عبر واتساب', 'aqar' ); ?></a>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( trim( get_the_content() ) ) : ?>
					<div class="unit-details__description">
						<h2><?php esc_html_e( 'تفاصيل الوحدة', 'aqar' ); ?></h2>
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</article>
		</div>
	</main>
	<?php
endwhile;

get_footer();
