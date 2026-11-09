<?php
/**
 * Plugin Name: Aurelia Properties
 * Description: Property data and business logic for Aurelia Homes.
 * Version: 0.1.0
 * Text Domain: aurelia-properties
 *
 * @package AureliaProperties
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function (): void {
		register_post_type(
			'property',
			array(
				'labels' => array(
					'name'          => __( 'Properties', 'aurelia-properties' ),
					'singular_name' => __( 'Property', 'aurelia-properties' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'properties' ),
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			)
		);
	}
);
