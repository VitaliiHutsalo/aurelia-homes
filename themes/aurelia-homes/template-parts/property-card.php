<?php
/**
 * Property card template part.
 *
 * @package AureliaHomes
 */

$property_id = get_the_ID();
$price       = get_post_meta( $property_id, '_aurelia_property_price', true );
$area        = get_post_meta( $property_id, '_aurelia_property_area', true );
$bedrooms    = get_post_meta( $property_id, '_aurelia_property_bedrooms', true );
$bathrooms   = get_post_meta( $property_id, '_aurelia_property_bathrooms', true );
$cities      = get_the_terms( $property_id, 'property_city' );
$types       = get_the_terms( $property_id, 'property_type' );
$city        = ! is_wp_error( $cities ) && ! empty( $cities ) ? $cities[0]->name : '';
$type        = ! is_wp_error( $types ) && ! empty( $types ) ? $types[0]->name : '';
?>
<article <?php post_class( 'property-card' ); ?>>
	<a class="property-card__image" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span><?php esc_html_e( 'Image coming soon', 'aurelia-homes' ); ?></span>
		<?php endif; ?>
	</a>

	<div class="property-card__content">
		<?php if ( $city || $type ) : ?>
			<p class="property-card__taxonomy">
				<?php echo esc_html( implode( ' · ', array_filter( array( $city, $type ) ) ) ); ?>
			</p>
		<?php endif; ?>

		<h2 class="property-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<?php if ( '' !== $price && is_numeric( $price ) ) : ?>
			<p class="property-card__price"><?php echo esc_html( '$' . number_format_i18n( (float) $price, 0 ) ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== $area || '' !== $bedrooms || '' !== $bathrooms ) : ?>
			<ul class="property-card__details">
				<?php if ( '' !== $area ) : ?>
					<li><?php echo esc_html( $area . ' m²' ); ?></li>
				<?php endif; ?>
				<?php if ( '' !== $bedrooms ) : ?>
					<li><?php echo esc_html( sprintf( _n( '%s bed', '%s beds', (int) $bedrooms, 'aurelia-homes' ), $bedrooms ) ); ?></li>
				<?php endif; ?>
				<?php if ( '' !== $bathrooms ) : ?>
					<li><?php echo esc_html( sprintf( _n( '%s bath', '%s baths', (int) $bathrooms, 'aurelia-homes' ), $bathrooms ) ); ?></li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
	</div>
</article>
