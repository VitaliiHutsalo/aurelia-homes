<?php
/**
 * Single property template.
 *
 * @package AureliaHomes
 */

get_header();

while ( have_posts() ) :
	the_post();

	$property_id = get_the_ID();
	$price       = get_post_meta( $property_id, '_aurelia_property_price', true );
	$area        = get_post_meta( $property_id, '_aurelia_property_area', true );
	$bedrooms    = get_post_meta( $property_id, '_aurelia_property_bedrooms', true );
	$bathrooms   = get_post_meta( $property_id, '_aurelia_property_bathrooms', true );
	$cities      = get_the_terms( $property_id, 'property_city' );
	$types       = get_the_terms( $property_id, 'property_type' );
	$city        = ! is_wp_error( $cities ) && ! empty( $cities ) ? $cities[0]->name : '';
	$type        = ! is_wp_error( $types ) && ! empty( $types ) ? $types[0]->name : '';
	$archive_url = get_post_type_archive_link( 'property' );
	?>
	<article <?php post_class( 'single-property' ); ?>>
		<?php if ( $archive_url ) : ?>
			<p class="single-property__back"><a href="<?php echo esc_url( $archive_url ); ?>">&larr; <?php esc_html_e( 'All properties', 'aurelia-homes' ); ?></a></p>
		<?php endif; ?>

		<header class="single-property__header">
			<?php if ( $city || $type ) : ?>
				<p class="eyebrow"><?php echo esc_html( implode( ' · ', array_filter( array( $city, $type ) ) ) ); ?></p>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( '' !== $price && is_numeric( $price ) ) : ?>
				<p class="single-property__price"><?php echo esc_html( '$' . number_format_i18n( (float) $price, 0 ) ); ?></p>
			<?php endif; ?>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="single-property__image">
				<?php the_post_thumbnail( 'full' ); ?>
			</div>
		<?php endif; ?>

		<div class="single-property__layout">
			<div class="single-property__content">
				<?php the_content(); ?>
			</div>

			<?php if ( '' !== $area || '' !== $bedrooms || '' !== $bathrooms ) : ?>
				<aside class="property-facts" aria-label="<?php esc_attr_e( 'Property details', 'aurelia-homes' ); ?>">
					<h2><?php esc_html_e( 'At a glance', 'aurelia-homes' ); ?></h2>
					<dl>
						<?php if ( '' !== $area ) : ?>
							<div><dt><?php esc_html_e( 'Area', 'aurelia-homes' ); ?></dt><dd><?php echo esc_html( $area . ' m²' ); ?></dd></div>
						<?php endif; ?>
						<?php if ( '' !== $bedrooms ) : ?>
							<div><dt><?php esc_html_e( 'Bedrooms', 'aurelia-homes' ); ?></dt><dd><?php echo esc_html( $bedrooms ); ?></dd></div>
						<?php endif; ?>
						<?php if ( '' !== $bathrooms ) : ?>
							<div><dt><?php esc_html_e( 'Bathrooms', 'aurelia-homes' ); ?></dt><dd><?php echo esc_html( $bathrooms ); ?></dd></div>
						<?php endif; ?>
					</dl>
				</aside>
			<?php endif; ?>
		</div>
	</article>

	<?php if ( ! is_wp_error( $cities ) && ! empty( $cities ) ) : ?>
		<?php
		$related_properties = new WP_Query(
			array(
				'post_type'      => 'property',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'post__not_in'   => array( $property_id ),
				'tax_query'      => array(
					array(
						'taxonomy' => 'property_city',
						'field'    => 'term_id',
						'terms'    => (int) $cities[0]->term_id,
					),
				),
			)
		);
		?>

		<?php if ( $related_properties->have_posts() ) : ?>
			<section class="related-properties">
				<div class="section-heading">
					<div>
						<p class="eyebrow"><?php echo esc_html( $city ); ?></p>
						<h2><?php esc_html_e( 'More homes nearby', 'aurelia-homes' ); ?></h2>
					</div>
				</div>

				<div class="property-grid">
					<?php
					while ( $related_properties->have_posts() ) :
						$related_properties->the_post();
						get_template_part( 'template-parts/property', 'card' );
					endwhile;
					?>
				</div>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endwhile; ?>
<?php
get_footer();
