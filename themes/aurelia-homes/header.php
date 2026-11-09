<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<div class="site-branding">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php bloginfo( 'name' ); ?>
		</a>
	</div>
	<?php
	$properties_url = get_post_type_archive_link( 'property' );
	$contact_page   = get_page_by_path( 'contact' );
	$contact_url    = $contact_page instanceof WP_Post ? get_permalink( $contact_page ) : '';
	?>
	<?php if ( $properties_url || $contact_url ) : ?>
		<nav class="site-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'aurelia-homes' ); ?>">
			<?php if ( $properties_url ) : ?>
				<a href="<?php echo esc_url( $properties_url ); ?>"><?php esc_html_e( 'Properties', 'aurelia-homes' ); ?></a>
			<?php endif; ?>
			<?php if ( $contact_url ) : ?>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact', 'aurelia-homes' ); ?></a>
			<?php endif; ?>
		</nav>
	<?php endif; ?>
</header>
<main class="site-content">
