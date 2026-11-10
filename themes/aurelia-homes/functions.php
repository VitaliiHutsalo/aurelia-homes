<?php
/**
 * Theme setup and assets.
 *
 * @package AureliaHomes
 */

declare( strict_types=1 );

add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 160,
				'width'       => 320,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$theme        = wp_get_theme();
		$manifest_path = get_theme_file_path( 'dist/.vite/manifest.json' );

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
			$entry    = is_array( $manifest ) && isset( $manifest['src/js/main.js'] ) ? $manifest['src/js/main.js'] : null;

			if ( is_array( $entry ) ) {
				if ( isset( $entry['css'] ) && is_array( $entry['css'] ) ) {
					foreach ( $entry['css'] as $stylesheet ) {
						wp_enqueue_style(
							'aurelia-homes',
							get_theme_file_uri( 'dist/' . $stylesheet ),
							array(),
							$theme->get( 'Version' )
						);
					}
				}

				if ( isset( $entry['file'] ) ) {
					wp_enqueue_script(
						'aurelia-homes',
						get_theme_file_uri( 'dist/' . $entry['file'] ),
						array(),
						$theme->get( 'Version' ),
						true
					);
				}

				return;
			}
		}

		wp_enqueue_style(
			'aurelia-homes',
			get_stylesheet_uri(),
			array(),
			$theme->get( 'Version' )
		);
	}
);
