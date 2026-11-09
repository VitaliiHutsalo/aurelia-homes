<?php
/**
 * Fallback template.
 *
 * @package AureliaHomes
 */

get_header();

$featured_properties = new WP_Query(
	array(
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'meta_key'       => '_aurelia_property_is_featured',
		'meta_value'     => '1',
	)
);
?>
	<section class="hero">
		<p class="eyebrow"><?php esc_html_e( 'Exceptional places to call home', 'aurelia-homes' ); ?></p>
		<h1><?php esc_html_e( 'Find your place with Aurelia Homes.', 'aurelia-homes' ); ?></h1>
		<p><?php esc_html_e( 'Discover thoughtfully selected homes in exceptional locations.', 'aurelia-homes' ); ?></p>
		<?php $properties_url = get_post_type_archive_link( 'property' ); ?>
		<?php if ( $properties_url ) : ?>
			<p><a class="button" href="<?php echo esc_url( $properties_url ); ?>"><?php esc_html_e( 'Browse properties', 'aurelia-homes' ); ?></a></p>
		<?php endif; ?>
	</section>

	<?php if ( $featured_properties->have_posts() ) : ?>
		<section class="featured-properties">
			<div class="section-heading">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Curated collection', 'aurelia-homes' ); ?></p>
					<h2><?php esc_html_e( 'Featured properties', 'aurelia-homes' ); ?></h2>
				</div>
				<?php if ( $properties_url ) : ?>
					<a href="<?php echo esc_url( $properties_url ); ?>"><?php esc_html_e( 'View all', 'aurelia-homes' ); ?></a>
				<?php endif; ?>
			</div>

			<div class="property-grid">
				<?php
				while ( $featured_properties->have_posts() ) :
					$featured_properties->the_post();
					get_template_part( 'template-parts/property', 'card' );
				endwhile;
				?>
			</div>
		</section>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
<?php
get_footer();
