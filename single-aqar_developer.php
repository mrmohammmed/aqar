<?php get_header(); ?>

<main class="container page-width content-width developer-details-page">
	<?php while ( have_posts() ) : ?>
		<?php
		the_post();
		$description = get_post_meta( get_the_ID(), '_aqar_developer_description', true );
		$location    = get_post_meta( get_the_ID(), '_aqar_developer_location', true );
		$phone       = get_post_meta( get_the_ID(), '_aqar_developer_phone', true );
		$website     = get_post_meta( get_the_ID(), '_aqar_developer_website', true );
		$project_ids = array_filter( array_map( 'absint', (array) get_post_meta( get_the_ID(), '_aqar_developer_projects', true ) ) );
		?>
		<article <?php post_class( 'developer-details' ); ?>>
			<div class="developer-details__breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'aqar' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'aqar_developer' ) ); ?>"><?php esc_html_e( 'المطورون', 'aqar' ); ?></a>
				<span aria-hidden="true">/</span>
				<span><?php the_title(); ?></span>
			</div>

			<h1 class="developer-details__title"><?php the_title(); ?></h1>

			<div class="developer-details__profile">
				<div class="developer-details__image">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'developer-details__logo' ) ); ?>
					<?php else : ?>
						<img class="developer-details__logo" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/شركة-بالم-هيلز-للتطوير-العقاري-1.webp' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
					<?php endif; ?>
				</div>
				<div class="developer-details__info">
					<?php if ( $description ) : ?>
						<p class="developer-details__summary"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<p><strong><?php esc_html_e( 'المقر:', 'aqar' ); ?></strong> <?php echo esc_html( $location ); ?></p>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<p><strong><?php esc_html_e( 'الهاتف:', 'aqar' ); ?></strong> <a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
					<?php if ( $website ) : ?>
						<p><strong><?php esc_html_e( 'الموقع الإلكتروني:', 'aqar' ); ?></strong> <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $website ); ?></a></p>
					<?php endif; ?>
					<?php if ( trim( get_the_content() ) ) : ?>
						<div class="developer-details__content"><?php the_content(); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</article>

		<section class="developer-projects">
			<div class="section-heading developer-projects__heading">
				<h2><?php esc_html_e( 'مشاريع المطور', 'aqar' ); ?></h2>
			</div>
			<div class="card-grid developer-projects__grid">
				<?php
				if ( $project_ids ) {
					$projects_query = new WP_Query(
						array(
							'post_type'           => 'aqar_project',
							'post_status'         => 'publish',
							'posts_per_page'      => -1,
							'post__in'            => $project_ids,
							'orderby'             => 'post__in',
							'ignore_sticky_posts' => true,
						)
					);

					if ( $projects_query->have_posts() ) :
						while ( $projects_query->have_posts() ) :
							$projects_query->the_post();
							$project_location = get_post_meta( get_the_ID(), '_aqar_project_location', true );
							?>
							<article class="project-card">
								<a class="project-card__image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( 'تفاصيل %s', get_the_title() ) ); ?>">
									<div class="thumb">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'large', array( 'class' => 'project-image' ) ); ?>
										<?php else : ?>
											<img class="project-image" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
										<?php endif; ?>
									</div>
								</a>
								<div class="card-copy">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<?php if ( $project_location ) : ?>
										<small><?php echo esc_html( $project_location ); ?></small>
									<?php endif; ?>
								</div>
							</article>
							<?php
						endwhile;
					else :
						echo '<p class="aqar-empty-state">' . esc_html__( 'لم يتم ربط مشاريع منشورة بهذا المطور بعد.', 'aqar' ) . '</p>';
					endif;

					wp_reset_postdata();
				} else {
					echo '<p class="aqar-empty-state">' . esc_html__( 'لم يتم ربط مشاريع بهذا المطور بعد.', 'aqar' ) . '</p>';
				}
				?>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
