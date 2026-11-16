<?php get_header(); ?>

<main class="container page-width content-width">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article <?php post_class( 'content-section' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
				<?php endif; ?>
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'لا توجد محتويات لعرضها.', 'aqar' ); ?></p>
	<?php endif; ?>
</main>

<?php get_footer(); ?>