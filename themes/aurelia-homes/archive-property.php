<?php
/**
 * Property archive template.
 *
 * @package AureliaHomes
 */

get_header();

$cities         = get_terms( array( 'taxonomy' => 'property_city', 'hide_empty' => false ) );
$property_types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
$cities         = is_wp_error( $cities ) ? array() : $cities;
$property_types = is_wp_error( $property_types ) ? array() : $property_types;
$selected_city  = isset( $_GET['city'] ) ? sanitize_title( wp_unslash( $_GET['city'] ) ) : '';
$selected_type  = isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : '';
$min_price      = isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '';
$max_price      = isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '';
$has_filters    = '' !== $selected_city || '' !== $selected_type || '' !== $min_price || '' !== $max_price;
$archive_url    = get_post_type_archive_link( 'property' );
?>
	<header class="archive-header">
		<p class="eyebrow"><?php esc_html_e( 'Aurelia Homes', 'aurelia-homes' ); ?></p>
		<h1><?php post_type_archive_title(); ?></h1>
		<p><?php esc_html_e( 'Explore our collection of exceptional homes.', 'aurelia-homes' ); ?></p>
	</header>

	<form class="property-filters" action="<?php echo esc_url( $archive_url ); ?>" method="get">
		<div class="property-filters__field">
			<label for="property-filter-city"><?php esc_html_e( 'City', 'aurelia-homes' ); ?></label>
			<select id="property-filter-city" name="city">
				<option value=""><?php esc_html_e( 'Any city', 'aurelia-homes' ); ?></option>
				<?php foreach ( $cities as $city ) : ?>
					<option value="<?php echo esc_attr( $city->slug ); ?>" <?php selected( $selected_city, $city->slug ); ?>><?php echo esc_html( $city->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="property-filters__field">
			<label for="property-filter-type"><?php esc_html_e( 'Property type', 'aurelia-homes' ); ?></label>
			<select id="property-filter-type" name="type">
				<option value=""><?php esc_html_e( 'Any type', 'aurelia-homes' ); ?></option>
				<?php foreach ( $property_types as $property_type ) : ?>
					<option value="<?php echo esc_attr( $property_type->slug ); ?>" <?php selected( $selected_type, $property_type->slug ); ?>><?php echo esc_html( $property_type->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="property-filters__field">
			<label for="property-filter-min-price"><?php esc_html_e( 'Min price', 'aurelia-homes' ); ?></label>
			<input id="property-filter-min-price" name="min_price" type="number" min="0" step="1000" value="<?php echo esc_attr( $min_price ); ?>" placeholder="0">
		</div>

		<div class="property-filters__field">
			<label for="property-filter-max-price"><?php esc_html_e( 'Max price', 'aurelia-homes' ); ?></label>
			<input id="property-filter-max-price" name="max_price" type="number" min="0" step="1000" value="<?php echo esc_attr( $max_price ); ?>" placeholder="Any">
		</div>

		<div class="property-filters__actions">
			<button class="button" type="submit"><?php esc_html_e( 'Apply filters', 'aurelia-homes' ); ?></button>
			<?php if ( $has_filters && $archive_url ) : ?>
				<a href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Reset', 'aurelia-homes' ); ?></a>
			<?php endif; ?>
		</div>
	</form>

	<?php if ( have_posts() ) : ?>
		<div class="property-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/property', 'card' );
			endwhile;
			?>
		</div>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<section class="empty-state">
			<h2><?php esc_html_e( 'No properties available yet.', 'aurelia-homes' ); ?></h2>
			<p><?php esc_html_e( 'Please check back soon.', 'aurelia-homes' ); ?></p>
		</section>
	<?php endif; ?>
<?php
get_footer();
