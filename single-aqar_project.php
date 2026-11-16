<?php
get_header();

while ( have_posts() ) :
	the_post();
	$project_id       = get_the_ID();
	$description      = get_post_meta( $project_id, '_aqar_project_description', true );
	$location         = get_post_meta( $project_id, '_aqar_project_location', true );
	$badge            = get_post_meta( $project_id, '_aqar_project_badge', true );
	$tagline          = aqar_get_project_meta( $project_id, 'tagline' );
	$status           = aqar_get_project_meta( $project_id, 'status' );
	$start_price      = aqar_get_project_meta( $project_id, 'start_price' );
	$price_currency   = aqar_get_project_meta( $project_id, 'price_currency' );
	$min_area         = aqar_get_project_meta( $project_id, 'min_area' );
	$delivery         = aqar_get_project_meta( $project_id, 'delivery' );
	$unit_summary     = aqar_get_project_meta( $project_id, 'unit_types_summary' );
	$payment_summary  = aqar_get_project_meta( $project_id, 'payment_summary' );
	$contact_phone    = aqar_get_project_meta( $project_id, 'contact_phone' );
	$developer_id     = absint( get_post_meta( $project_id, '_aqar_project_developer', true ) );
	$developer_name   = $developer_id ? get_the_title( $developer_id ) : '';
	$main_image       = get_the_post_thumbnail_url( $project_id, 'large' );
	$main_image       = $main_image ? $main_image : get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
	$gallery_images   = array_values( array_filter( array_map( 'aqar_project_image_url', preg_split( '/\r\n|\r|\n/', aqar_get_project_meta( $project_id, 'gallery' ) ) ) ) );
	$plans            = aqar_parse_project_rows( aqar_get_project_meta( $project_id, 'plans' ), 4 );
	$videos           = aqar_parse_project_rows( aqar_get_project_meta( $project_id, 'videos' ), 4 );
	$unit_types       = aqar_parse_project_rows( aqar_get_project_meta( $project_id, 'unit_types' ), 6 );
	$available_units  = get_posts(
		array(
			'post_type'      => 'aqar_unit',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'meta_key'       => '_aqar_unit_project',
			'meta_value'     => $project_id,
			'orderby'        => array(
				'date' => 'DESC',
				'ID'   => 'DESC',
			),
		)
	);
	$payment_plans    = aqar_parse_project_rows( aqar_get_project_meta( $project_id, 'payment_plans' ), 6 );
	$location_points  = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', aqar_get_project_meta( $project_id, 'location_points' ) ) ) );
	$faqs             = aqar_parse_project_rows( aqar_get_project_meta( $project_id, 'faqs' ), 2 );
	$about_heading    = aqar_get_project_meta( $project_id, 'about_heading' );
	$about_image      = esc_url( aqar_get_project_meta( $project_id, 'about_image' ) );
	$location_heading = aqar_get_project_meta( $project_id, 'location_heading' );
	$location_subhead = aqar_get_project_meta( $project_id, 'location_subheading' );
	$location_description = aqar_get_project_meta( $project_id, 'location_description' );
	$location_map_url = esc_url( aqar_get_project_meta( $project_id, 'location_map_url' ) );
	$contact_heading  = aqar_get_project_meta( $project_id, 'contact_heading' );
	$contact_message  = aqar_get_project_meta( $project_id, 'contact_message' );
	$faq_heading      = aqar_get_project_meta( $project_id, 'faq_heading' );
	$has_content      = trim( get_the_content() );

	$tabs = array(
		'plans'    => ! empty( $plans ),
		'videos'   => ! empty( $videos ),
		'types'    => ! empty( $unit_types ),
		'units'    => ! empty( $available_units ),
		'payments' => ! empty( $payment_plans ),
	);
	$has_tabs = in_array( true, $tabs, true );
	$active_tab = '';
	foreach ( $tabs as $tab_key => $has_tab ) {
		if ( $has_tab ) {
			$active_tab = $tab_key;
			break;
		}
	}
	?>
	<main class="project-details project-details-page">
		<div class="container project-details__container">
			<nav class="project-details__breadcrumb" aria-label="<?php esc_attr_e( 'مسار التنقل', 'aqar' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'aqar' ); ?></a>
				<i class="fas fa-chevron-left" aria-hidden="true"></i>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'aqar_project' ) ); ?>"><?php esc_html_e( 'المشروعات', 'aqar' ); ?></a>
				<i class="fas fa-chevron-left" aria-hidden="true"></i>
				<span><?php the_title(); ?></span>
			</nav>

			<section class="project-showcase" aria-labelledby="project-title">
				<div class="project-showcase__gallery <?php echo $gallery_images ? 'project-showcase__gallery--has-thumbnails' : 'project-showcase__gallery--single'; ?>">
					<img class="project-showcase__main-image" src="<?php echo esc_url( $main_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
					<?php if ( $gallery_images ) : ?>
						<div class="project-showcase__thumbs">
							<?php foreach ( array_slice( $gallery_images, 0, 3 ) as $gallery_image ) : ?>
								<img src="<?php echo esc_url( $gallery_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
							<?php endforeach; ?>
						</div>
						<span class="project-showcase__image-count"><i class="far fa-images" aria-hidden="true"></i> <?php echo esc_html( count( $gallery_images ) + 1 ); ?> <?php esc_html_e( 'صور', 'aqar' ); ?></span>
					<?php endif; ?>
				</div>

				<div class="project-showcase__summary">
					<div class="project-showcase__topline">
						<?php if ( $tagline || $badge ) : ?>
							<span class="project-details__eyebrow"><?php echo esc_html( $tagline ? $tagline : $badge ); ?></span>
						<?php endif; ?>
						<?php if ( $status ) : ?>
							<span class="project-showcase__status"><i class="fas fa-circle" aria-hidden="true"></i> <?php echo esc_html( $status ); ?></span>
						<?php endif; ?>
					</div>
					<h1 id="project-title"><?php the_title(); ?></h1>
					<?php if ( $location ) : ?>
						<p class="project-showcase__location"><i class="fas fa-location-dot" aria-hidden="true"></i> <?php echo esc_html( $location ); ?></p>
					<?php endif; ?>
					<?php if ( $description ) : ?>
						<p class="project-showcase__description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
					<?php if ( $start_price || $min_area || $delivery ) : ?>
						<div class="project-showcase__facts">
							<?php if ( $start_price ) : ?>
								<div><span><?php esc_html_e( 'يبدأ السعر من', 'aqar' ); ?></span><strong><?php echo esc_html( $start_price ); ?> <small><?php echo esc_html( $price_currency ); ?></small></strong></div>
							<?php endif; ?>
							<?php if ( $min_area ) : ?>
								<div><span><?php esc_html_e( 'مساحات تبدأ من', 'aqar' ); ?></span><strong><?php echo esc_html( $min_area ); ?> <small><?php esc_html_e( 'م²', 'aqar' ); ?></small></strong></div>
							<?php endif; ?>
							<?php if ( $delivery ) : ?>
								<div><span><?php esc_html_e( 'موعد الاستلام', 'aqar' ); ?></span><strong><?php echo esc_html( $delivery ); ?></strong></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $contact_phone ) : ?>
						<a class="project-showcase__phone" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><i class="fas fa-phone" aria-hidden="true"></i> <?php echo esc_html( $contact_phone ); ?></a>
					<?php endif; ?>
				</div>
			</section>

			<?php if ( $developer_name || $location || $unit_summary || $payment_summary ) : ?>
				<section class="project-highlights" aria-label="<?php esc_attr_e( 'معلومات سريعة عن المشروع', 'aqar' ); ?>">
					<?php if ( $developer_name ) : ?>
						<div><i class="fas fa-building" aria-hidden="true"></i><span><?php esc_html_e( 'المطور العقاري', 'aqar' ); ?></span><strong><?php echo esc_html( $developer_name ); ?></strong></div>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<div><i class="fas fa-map-marker-alt" aria-hidden="true"></i><span><?php esc_html_e( 'الموقع', 'aqar' ); ?></span><strong><?php echo esc_html( $location ); ?></strong></div>
					<?php endif; ?>
					<?php if ( $unit_summary ) : ?>
						<div><i class="fas fa-house" aria-hidden="true"></i><span><?php esc_html_e( 'أنواع الوحدات', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_summary ); ?></strong></div>
					<?php endif; ?>
					<?php if ( $payment_summary ) : ?>
						<div><i class="fas fa-credit-card" aria-hidden="true"></i><span><?php esc_html_e( 'أنظمة السداد', 'aqar' ); ?></span><strong><?php echo esc_html( $payment_summary ); ?></strong></div>
					<?php endif; ?>
				</section>
			<?php endif; ?>

			<?php if ( $has_tabs ) : ?>
				<section class="project-tabs-section" aria-labelledby="project-tabs-title">
					<div class="project-section-heading">
						<span><?php esc_html_e( 'كل ما تحتاج معرفته', 'aqar' ); ?></span>
						<h2 id="project-tabs-title"><?php echo esc_html( sprintf( __( 'تفاصيل %s', 'aqar' ), get_the_title() ) ); ?></h2>
					</div>
					<ul class="nav nav-tabs project-tabs" id="projectInfoTabs" role="tablist">
						<?php
						$tab_labels = array(
							'plans'    => __( 'خطط المشروع', 'aqar' ),
							'videos'   => __( 'الفيديوهات', 'aqar' ),
							'types'    => __( 'أنواع الوحدات', 'aqar' ),
							'units'    => __( 'الوحدات المتاحة', 'aqar' ),
							'payments' => __( 'أنظمة السداد', 'aqar' ),
						);
						foreach ( $tabs as $tab_key => $has_tab ) :
							if ( ! $has_tab ) {
								continue;
							}
							$tab_id = 'payments' === $tab_key ? 'payment' : $tab_key;
							?>
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php echo $active_tab === $tab_key ? 'active' : ''; ?>" id="<?php echo esc_attr( $tab_id ); ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_attr( $tab_id ); ?>-panel" type="button" role="tab" aria-controls="<?php echo esc_attr( $tab_id ); ?>-panel" aria-selected="<?php echo $active_tab === $tab_key ? 'true' : 'false'; ?>"><?php echo esc_html( $tab_labels[ $tab_key ] ); ?></button>
							</li>
							<?php endforeach; ?>
					
					</ul>

					<div class="tab-content project-tab-content" id="projectInfoTabContent">
						<?php if ( $plans ) : ?>
							<section class="tab-pane fade <?php echo 'plans' === $active_tab ? 'show active' : ''; ?>" id="plans-panel" role="tabpanel" aria-labelledby="plans-tab" tabindex="0">
								<div class="project-tab-heading"><div><span><?php esc_html_e( 'تخطيط متكامل للمجتمع', 'aqar' ); ?></span><h3><?php esc_html_e( 'خطط المشروع', 'aqar' ); ?></h3></div></div>
								<div class="project-plans-grid">
									<?php foreach ( $plans as $plan_index => $plan ) : ?>
										<?php $plan_rooms = array_filter( array_map( 'trim', explode( ',', $plan[3] ) ) ); ?>
										<article class="project-plan-card">
											<?php if ( $plan[2] && esc_url( $plan[2] ) ) : ?>
												<img class="project-plan-card__image" src="<?php echo esc_url( $plan[2] ); ?>" alt="<?php echo esc_attr( $plan[0] ); ?>">
											<?php else : ?>
												<?php if ( 0 === $plan_index ) : ?>
													<div class="project-plan-card__art project-plan-card__art--community"><i class="fas fa-map" aria-hidden="true"></i><?php if ( $plan[3] ) : ?><span><?php echo esc_html( $plan[3] ); ?></span><?php endif; ?></div>
												<?php else : ?>
													<div class="project-plan-card__art project-plan-card__art--<?php echo 1 === $plan_index ? 'apartment' : 'villa'; ?>">
														<?php foreach ( array_slice( $plan_rooms, 0, 4 ) as $room_index => $room ) : ?>
															<span class="plan-room plan-room--<?php echo esc_attr( array( 'one', 'two', 'three', 'four' )[ $room_index ] ); ?>"><?php echo esc_html( $room ); ?></span>
														<?php endforeach; ?>
													</div>
												<?php endif; ?>
											<?php endif; ?>
											<h4><?php echo esc_html( $plan[0] ); ?></h4>
											<?php if ( $plan[1] ) : ?><p><?php echo esc_html( $plan[1] ); ?></p><?php endif; ?>
										</article>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php if ( $videos ) : ?>
							<section class="tab-pane fade <?php echo 'videos' === $active_tab ? 'show active' : ''; ?>" id="videos-panel" role="tabpanel" aria-labelledby="videos-tab" tabindex="0">
								<div class="project-tab-heading"><div><span><?php esc_html_e( 'شاهد المشروع عن قرب', 'aqar' ); ?></span><h3><?php esc_html_e( 'فيديوهات المشروع', 'aqar' ); ?></h3></div></div>
								<div class="project-videos-grid">
									<?php foreach ( $videos as $video ) : ?>
										<article class="project-video-card">
											<?php $youtube_embed_url = aqar_get_youtube_embed_url( $video[2] ); ?>
											<?php if ( $youtube_embed_url ) : ?>
												<div class="project-video-card__poster">
													<iframe src="<?php echo esc_url( $youtube_embed_url ); ?>" title="<?php echo esc_attr( $video[0] ); ?>" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
												</div>
											<?php else : ?>
												<?php if ( $video[2] && esc_url( $video[2] ) ) : ?>
													<a class="project-video-card__poster" href="<?php echo esc_url( $video[2] ); ?>" target="_blank" rel="noopener noreferrer">
												<?php else : ?>
													<div class="project-video-card__poster">
												<?php endif; ?>
												<?php if ( $video[3] && esc_url( $video[3] ) ) : ?>
													<img src="<?php echo esc_url( $video[3] ); ?>" alt="<?php echo esc_attr( $video[0] ); ?>">
												<?php else : ?>
													<img src="<?php echo esc_url( $main_image ); ?>" alt="<?php echo esc_attr( $video[0] ); ?>">
												<?php endif; ?>
												<span><i class="fas fa-play" aria-hidden="true"></i></span>
												<?php if ( $video[2] && esc_url( $video[2] ) ) : ?></a><?php else : ?></div><?php endif; ?>
											<?php endif; ?>
											<h4><?php echo esc_html( $video[0] ); ?></h4>
											<?php if ( $video[1] ) : ?><p><?php echo esc_html( $video[1] ); ?></p><?php endif; ?>
										</article>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php if ( $unit_types ) : ?>
							<section class="tab-pane fade <?php echo 'types' === $active_tab ? 'show active' : ''; ?>" id="types-panel" role="tabpanel" aria-labelledby="types-tab" tabindex="0">
								<div class="project-unit-types__heading"><h3><?php esc_html_e( 'أنواع ومساحات الوحدات', 'aqar' ); ?></h3><span><?php echo esc_html( $unit_summary ? $unit_summary : 'شقق' ); ?> <b>(<?php echo esc_html( count( $unit_types ) ); ?>)</b></span></div>
								<div class="project-unit-types__list">
									<?php foreach ( $unit_types as $unit_type ) : ?>
										<article class="project-unit-type-row">
											<?php if ( $contact_phone ) : ?><a href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $contact_phone ) ); ?>" class="project-unit-type-row__button"><i class="fab fa-whatsapp" aria-hidden="true"></i> <?php esc_html_e( 'استفسر عن هذه الوحدة', 'aqar' ); ?></a><?php endif; ?>
											<?php if ( $unit_type[4] ) : ?><div class="project-unit-type-row__price"><span><?php esc_html_e( 'السعر يبدأ من', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_type[4] . ' ' . $unit_type[5] ); ?></strong></div><?php endif; ?>
											<div class="project-unit-type-row__facts">
												<?php if ( $unit_type[1] ) : ?><span><i class="fas fa-expand" aria-hidden="true"></i> <?php echo esc_html( $unit_type[1] ); ?> <?php esc_html_e( 'م²', 'aqar' ); ?></span><?php endif; ?>
												<?php if ( $unit_type[2] ) : ?><span><i class="fas fa-bed" aria-hidden="true"></i> <?php echo esc_html( $unit_type[2] ); ?> <?php esc_html_e( 'غرفة', 'aqar' ); ?></span><?php endif; ?>
												<?php if ( $unit_type[3] ) : ?><span><i class="fas fa-bath" aria-hidden="true"></i> <?php echo esc_html( $unit_type[3] ); ?> <?php esc_html_e( 'حمام', 'aqar' ); ?></span><?php endif; ?>
											</div>
										</article>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php if ( $available_units ) : ?>
							<section class="tab-pane fade <?php echo 'units' === $active_tab ? 'show active' : ''; ?>" id="units-panel" role="tabpanel" aria-labelledby="units-tab" tabindex="0">
								<div class="project-available__heading"><h3><?php esc_html_e( 'الوحدات المتاحة للبيع', 'aqar' ); ?></h3><h4><?php echo esc_html( sprintf( __( 'شقق للبيع في %s', 'aqar' ), get_the_title() ) ); ?></h4></div>
								<div class="project-available-grid">
									<?php foreach ( $available_units as $unit ) : ?>
										<?php
										$unit_id            = $unit->ID;
										$unit_type          = get_post_meta( $unit_id, '_aqar_unit_type', true );
										$unit_location      = get_post_meta( $unit_id, '_aqar_unit_location', true );
										$unit_bedrooms      = get_post_meta( $unit_id, '_aqar_unit_bedrooms', true );
										$unit_bathrooms     = get_post_meta( $unit_id, '_aqar_unit_bathrooms', true );
										$unit_area          = get_post_meta( $unit_id, '_aqar_unit_area', true );
										$unit_price         = get_post_meta( $unit_id, '_aqar_unit_price', true );
										$unit_price_label   = get_post_meta( $unit_id, '_aqar_unit_price_label', true );
										$unit_currency      = get_post_meta( $unit_id, '_aqar_unit_price_currency', true );
										$unit_installment   = get_post_meta( $unit_id, '_aqar_unit_installment', true );
										$unit_install_label = get_post_meta( $unit_id, '_aqar_unit_installment_label', true );
										$unit_install_cur   = get_post_meta( $unit_id, '_aqar_unit_installment_currency', true );
										$unit_phone         = get_post_meta( $unit_id, '_aqar_unit_phone', true );
										$unit_status        = get_post_meta( $unit_id, '_aqar_unit_status', true );
										$unit_delivery      = get_post_meta( $unit_id, '_aqar_unit_delivery', true );
										$unit_terms         = wp_get_post_terms( $unit_id, 'aqar_city', array( 'fields' => 'names' ) );
										if ( ! $unit_location && ! is_wp_error( $unit_terms ) && $unit_terms ) {
											$unit_location = $unit_terms[0];
										}
										$unit_image = get_the_post_thumbnail_url( $unit_id, 'large' );
										if ( ! $unit_image ) {
											$unit_image = get_post_meta( $unit_id, '_aqar_unit_legacy_image', true );
										}
										if ( ! $unit_image ) {
											$unit_image = $main_image;
										}
										$unit_contact_phone = $unit_phone ? $unit_phone : $contact_phone;
										?>
										<article class="project-available-card">
											<div class="project-available-card__image">
												<img src="<?php echo esc_url( $unit_image ); ?>" alt="<?php echo esc_attr( $unit->post_title ); ?>">
												<?php if ( $unit_status || $unit_type ) : ?><span><?php echo esc_html( $unit_status ? $unit_status : $unit_type ); ?></span><?php endif; ?>
												<?php if ( $unit_delivery ) : ?><b><?php echo esc_html( sprintf( __( 'استلام: %s', 'aqar' ), $unit_delivery ) ); ?></b><?php endif; ?>
											</div>
											<div class="project-available-card__body">
												<h4><?php echo esc_html( $unit->post_title ); ?></h4>
												<?php if ( $unit_location ) : ?><p class="project-available-card__location"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?php echo esc_html( $unit_location ); ?></p><?php endif; ?>
												<div class="project-available-card__facts">
													<?php if ( $unit_bedrooms ) : ?><span><i class="fas fa-bed" aria-hidden="true"></i> <?php echo esc_html( $unit_bedrooms ); ?> <?php esc_html_e( 'غرف', 'aqar' ); ?></span><?php endif; ?>
													<?php if ( $unit_bathrooms ) : ?><span><i class="fas fa-bath" aria-hidden="true"></i> <?php echo esc_html( $unit_bathrooms ); ?> <?php esc_html_e( 'حمام', 'aqar' ); ?></span><?php endif; ?>
													<?php if ( $unit_area ) : ?><span><i class="fas fa-expand" aria-hidden="true"></i> <?php echo esc_html( $unit_area ); ?> <?php esc_html_e( 'م²', 'aqar' ); ?></span><?php endif; ?>
												</div>
												<?php if ( $unit_price || $unit_price_label || $unit_installment || $unit_install_label ) : ?>
													<div class="project-available-card__prices">
														<?php if ( $unit_price || $unit_price_label ) : ?><div><span><?php esc_html_e( 'السعر الإجمالي', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_price ? number_format_i18n( (float) $unit_price ) : $unit_price_label ); ?> <small><?php echo esc_html( $unit_currency ); ?></small></strong></div><?php endif; ?>
														<?php if ( $unit_installment || $unit_install_label ) : ?><div><span><?php esc_html_e( 'أقل قسط شهري', 'aqar' ); ?></span><strong><?php echo esc_html( $unit_installment ? number_format_i18n( (float) $unit_installment ) : $unit_install_label ); ?> <small><?php echo esc_html( $unit_install_cur ); ?></small></strong></div><?php endif; ?>
													</div>
												<?php endif; ?>
												<div class="project-available-card__actions"><div>
													<?php if ( $unit_contact_phone ) : ?>
														<a class="project-available-card__contact" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $unit_contact_phone ) ); ?>" aria-label="<?php esc_attr_e( 'اتصال', 'aqar' ); ?>"><i class="fas fa-phone" aria-hidden="true"></i></a>
														<a class="project-available-card__contact project-available-card__contact--whatsapp" href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $unit_contact_phone ) ); ?>" aria-label="<?php esc_attr_e( 'واتساب', 'aqar' ); ?>"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
													<?php endif; ?>
												</div><a class="project-available-card__details" href="<?php echo esc_url( get_permalink( $unit_id ) ); ?>"><i class="far fa-copy" aria-hidden="true"></i> <?php esc_html_e( 'تفاصيل الوحدة', 'aqar' ); ?></a></div>
											</div>
										</article>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php if ( $payment_plans ) : ?>
							<section class="tab-pane fade <?php echo 'payments' === $active_tab ? 'show active' : ''; ?>" id="payment-panel" role="tabpanel" aria-labelledby="payment-tab" tabindex="0">
								<div class="project-tab-heading"><div><span><?php esc_html_e( 'خطط مرنة تناسب ميزانيتك', 'aqar' ); ?></span><h3><?php esc_html_e( 'أنظمة السداد', 'aqar' ); ?></h3></div></div>
								<div class="project-payment-options">
									<?php foreach ( $payment_plans as $payment_plan ) : ?>
										<article class="<?php echo isset( $payment_plan[5] ) && 'featured' === $payment_plan[5] ? 'is-featured' : ''; ?>">
											<span><?php echo esc_html( $payment_plan[0] ); ?></span>
											<strong><?php echo esc_html( $payment_plan[1] ); ?></strong>
											<p><?php esc_html_e( 'مقدم تعاقد', 'aqar' ); ?></p>
											<div>
												<?php if ( $payment_plan[2] ) : ?><b><?php echo esc_html( sprintf( __( 'تقسيط حتى %s', 'aqar' ), $payment_plan[2] ) ); ?></b><?php endif; ?>
												<?php if ( $payment_plan[3] ) : ?><b><?php echo esc_html( $payment_plan[3] ); ?></b><?php endif; ?>
												<?php if ( $payment_plan[4] ) : ?><b><?php echo esc_html( $payment_plan[4] ); ?></b><?php endif; ?>
											</div>
											<?php if ( $contact_phone ) : ?><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php esc_html_e( 'اطلب تفاصيل الخطة', 'aqar' ); ?></a><?php endif; ?>
										</article>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( $has_content || $about_image ) : ?>
				<section class="project-about">
					<div class="project-section-heading"><span><?php esc_html_e( 'عن المشروع', 'aqar' ); ?></span><h2><?php echo esc_html( $about_heading ? $about_heading : get_the_title() ); ?></h2></div>
					<div class="project-about__layout">
						<div class="project-about__copy">
							<?php if ( $has_content ) : ?><div class="project-about__content"><?php the_content(); ?></div><?php endif; ?>
							<?php if ( $contact_phone ) : ?><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php esc_html_e( 'اطلب استشارة عن المشروع', 'aqar' ); ?> <i class="fas fa-arrow-left" aria-hidden="true"></i></a><?php endif; ?>
						</div>
						<?php if ( $about_image ) : ?><img src="<?php echo esc_url( $about_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"><?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( $location_heading || $location_subhead || $location_description || $location_map_url || $location_points ) : ?>
				<section class="project-location">
					<div class="project-section-heading"><span><?php esc_html_e( 'موقع مميز', 'aqar' ); ?></span><h2><?php echo esc_html( $location_heading ? $location_heading : sprintf( __( 'موقع %s', 'aqar' ), get_the_title() ) ); ?></h2></div>
					<div class="project-location__panel">
						<?php if ( $location_map_url ) : ?>
							<a class="project-location__map" href="<?php echo esc_url( $location_map_url ); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-map-marked-alt" aria-hidden="true"></i><span><?php echo esc_html( $location ? $location : get_the_title() ); ?></span></a>
						<?php else : ?>
							<div class="project-location__map" role="img" aria-label="<?php echo esc_attr( $location ? $location : get_the_title() ); ?>"><i class="fas fa-map-marked-alt" aria-hidden="true"></i><span><?php echo esc_html( $location ? $location : get_the_title() ); ?></span></div>
						<?php endif; ?>
						<div class="project-location__copy">
							<?php if ( $location_subhead ) : ?><h3><?php echo esc_html( $location_subhead ); ?></h3><?php endif; ?>
							<?php if ( $location_description ) : ?><p><?php echo esc_html( $location_description ); ?></p><?php elseif ( $location ) : ?><p><?php echo esc_html( $location ); ?></p><?php endif; ?>
							<?php if ( $location_points ) : ?><ul><?php foreach ( $location_points as $point ) : ?><li><i class="fas fa-check" aria-hidden="true"></i> <?php echo esc_html( $point ); ?></li><?php endforeach; ?></ul><?php endif; ?>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( $faqs ) : ?>
				<section class="project-faq">
					<div class="project-section-heading"><span><?php esc_html_e( 'أسئلة شائعة', 'aqar' ); ?></span><h2><?php echo esc_html( $faq_heading ? $faq_heading : sprintf( __( 'كل ما تريد معرفته عن %s', 'aqar' ), get_the_title() ) ); ?></h2></div>
					<div class="accordion project-accordion" id="projectFaq">
						<?php foreach ( $faqs as $index => $faq ) : $faq_id = 'project-faq-' . $project_id . '-' . $index; ?>
							<div class="accordion-item">
								<h3 class="accordion-header"><button class="accordion-button <?php echo 0 === $index ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $faq_id ); ?>" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $faq_id ); ?>"><?php echo esc_html( $faq[0] ); ?></button></h3>
								<div id="<?php echo esc_attr( $faq_id ); ?>" class="accordion-collapse collapse <?php echo 0 === $index ? 'show' : ''; ?>" data-bs-parent="#projectFaq"><div class="accordion-body"><?php echo esc_html( $faq[1] ); ?></div></div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( $contact_heading || $contact_message || $contact_phone ) : ?>
				<section class="project-contact" id="project-contact">
					<div>
						<span><?php esc_html_e( 'نحن هنا لمساعدتك', 'aqar' ); ?></span>
						<h2><?php echo esc_html( $contact_heading ? $contact_heading : sprintf( __( 'مهتم بـ %s؟', 'aqar' ), get_the_title() ) ); ?></h2>
						<?php if ( $contact_message ) : ?><p><?php echo esc_html( $contact_message ); ?></p><?php endif; ?>
					</div>
					<?php if ( preg_replace( '/[^0-9]/', '', (string) $contact_phone ) ) : ?>
					<form class="project-contact__form" data-whatsapp="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', (string) $contact_phone ) ); ?>" data-project="<?php echo esc_attr( get_the_title() ); ?>">
						<label class="visually-hidden" for="project-contact-name-<?php echo esc_attr( $project_id ); ?>"><?php esc_html_e( 'الاسم الكامل', 'aqar' ); ?></label>
						<input id="project-contact-name-<?php echo esc_attr( $project_id ); ?>" name="name" type="text" placeholder="<?php esc_attr_e( 'الاسم الكامل *', 'aqar' ); ?>" autocomplete="name" required>
						<label class="visually-hidden" for="project-contact-phone-<?php echo esc_attr( $project_id ); ?>"><?php esc_html_e( 'رقم الهاتف', 'aqar' ); ?></label>
						<input id="project-contact-phone-<?php echo esc_attr( $project_id ); ?>" name="phone" type="tel" placeholder="<?php esc_attr_e( 'رقم الهاتف *', 'aqar' ); ?>" autocomplete="tel" required>
						<button type="submit"><?php esc_html_e( 'اطلب التواصل', 'aqar' ); ?></button>
					</form>
					<?php else : ?>
						<p role="status"><?php esc_html_e( 'نموذج التواصل غير متاح حاليًا لهذا المشروع.', 'aqar' ); ?></p>
					<?php endif; ?>
				</section>
			<?php endif; ?>
		</div>
	</main>
	<?php
endwhile;

get_footer();
