<?php
/**
 * Plugin Name: Aurelia Properties
 * Description: Property data and business logic for Aurelia Homes.
 * Version: 0.2.0
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

		register_taxonomy(
			'property_city',
			array( 'property' ),
			array(
				'labels' => array(
					'name'          => __( 'Cities', 'aurelia-properties' ),
					'singular_name' => __( 'City', 'aurelia-properties' ),
				),
				'public'            => true,
				'hierarchical'      => false,
				'meta_box_cb'       => false,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'property-city' ),
			)
		);

		register_taxonomy(
			'property_type',
			array( 'property' ),
			array(
				'labels' => array(
					'name'          => __( 'Property Types', 'aurelia-properties' ),
					'singular_name' => __( 'Property Type', 'aurelia-properties' ),
				),
				'public'            => true,
				'hierarchical'      => true,
				'meta_box_cb'       => false,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'property-type' ),
			)
		);
	}
);

add_action(
	'add_meta_boxes_property',
	static function (): void {
		add_meta_box(
			'aurelia-property-details',
			__( 'Listing details', 'aurelia-properties' ),
			static function ( WP_Post $post ): void {
				wp_nonce_field( 'aurelia_save_property_details', 'aurelia_property_details_nonce' );

				$cities         = get_terms( array( 'taxonomy' => 'property_city', 'hide_empty' => false ) );
				$property_types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
				$cities         = is_wp_error( $cities ) ? array() : $cities;
				$property_types = is_wp_error( $property_types ) ? array() : $property_types;
				$city_ids       = wp_get_object_terms( $post->ID, 'property_city', array( 'fields' => 'ids' ) );
				$type_ids       = wp_get_object_terms( $post->ID, 'property_type', array( 'fields' => 'ids' ) );
				$city_id        = is_wp_error( $city_ids ) || empty( $city_ids ) ? 0 : (int) $city_ids[0];
				$type_id        = is_wp_error( $type_ids ) || empty( $type_ids ) ? 0 : (int) $type_ids[0];
				$price          = get_post_meta( $post->ID, '_aurelia_property_price', true );
				$area           = get_post_meta( $post->ID, '_aurelia_property_area', true );
				$bedrooms       = get_post_meta( $post->ID, '_aurelia_property_bedrooms', true );
				$bathrooms      = get_post_meta( $post->ID, '_aurelia_property_bathrooms', true );
				$is_featured = '1' === get_post_meta( $post->ID, '_aurelia_property_is_featured', true );
				?>
				<p class="aurelia-property-details__intro"><?php esc_html_e( 'Add the key facts buyers need to compare this home.', 'aurelia-properties' ); ?></p>
				<table class="form-table aurelia-property-details__table" role="presentation">
					<tr>
						<th scope="row"><label for="aurelia-property-city"><?php esc_html_e( 'City', 'aurelia-properties' ); ?></label></th>
						<td>
							<select id="aurelia-property-city" name="aurelia_property_city">
								<option value=""><?php esc_html_e( 'Select a city', 'aurelia-properties' ); ?></option>
								<?php foreach ( $cities as $city ) : ?>
									<option value="<?php echo esc_attr( (string) $city->term_id ); ?>" <?php selected( $city_id, $city->term_id ); ?>><?php echo esc_html( $city->name ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="aurelia-property-type"><?php esc_html_e( 'Property Type', 'aurelia-properties' ); ?></label></th>
						<td>
							<select id="aurelia-property-type" name="aurelia_property_type">
								<option value=""><?php esc_html_e( 'Select a property type', 'aurelia-properties' ); ?></option>
								<?php foreach ( $property_types as $property_type ) : ?>
									<option value="<?php echo esc_attr( (string) $property_type->term_id ); ?>" <?php selected( $type_id, $property_type->term_id ); ?>><?php echo esc_html( $property_type->name ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="aurelia-property-price"><?php esc_html_e( 'Price', 'aurelia-properties' ); ?></label></th>
						<td><input id="aurelia-property-price" name="aurelia_property_price" type="number" min="0" step="0.01" value="<?php echo esc_attr( $price ); ?>"> <span class="description">USD</span></td>
					</tr>
					<tr>
						<th scope="row"><label for="aurelia-property-area"><?php esc_html_e( 'Area', 'aurelia-properties' ); ?></label></th>
						<td><input id="aurelia-property-area" name="aurelia_property_area" type="number" min="0" step="0.01" value="<?php echo esc_attr( $area ); ?>"> <span class="description">m²</span></td>
					</tr>
					<tr>
						<th scope="row"><label for="aurelia-property-bedrooms"><?php esc_html_e( 'Bedrooms', 'aurelia-properties' ); ?></label></th>
						<td><input id="aurelia-property-bedrooms" name="aurelia_property_bedrooms" type="number" min="0" step="1" value="<?php echo esc_attr( $bedrooms ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="aurelia-property-bathrooms"><?php esc_html_e( 'Bathrooms', 'aurelia-properties' ); ?></label></th>
						<td><input id="aurelia-property-bathrooms" name="aurelia_property_bathrooms" type="number" min="0" step="1" value="<?php echo esc_attr( $bathrooms ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Homepage', 'aurelia-properties' ); ?></th>
						<td>
							<label for="aurelia-property-is-featured">
								<input id="aurelia-property-is-featured" name="aurelia_property_is_featured" type="checkbox" value="1" <?php checked( $is_featured ); ?>>
								<?php esc_html_e( 'Feature this property on the homepage', 'aurelia-properties' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<?php
			},
			'property',
			'normal',
			'high'
		);
	}
);

add_action(
	'admin_enqueue_scripts',
	static function (): void {
		$screen = get_current_screen();

		$is_property_screen = $screen && (
			'property' === $screen->post_type ||
			in_array( $screen->taxonomy ?? '', array( 'property_city', 'property_type' ), true )
		);

		if ( ! $is_property_screen ) {
			return;
		}

		$stylesheet_path = plugin_dir_path( __FILE__ ) . 'assets/css/admin.css';

		wp_enqueue_style(
			'aurelia-properties-admin',
			plugins_url( 'assets/css/admin.css', __FILE__ ),
			array(),
			file_exists( $stylesheet_path ) ? (string) filemtime( $stylesheet_path ) : '0.2.0'
		);
	}
);

add_filter(
	'manage_property_posts_columns',
	static function ( array $columns ): array {
		$columns['property_price']    = __( 'Price', 'aurelia-properties' );
		$columns['property_featured'] = __( 'Featured', 'aurelia-properties' );

		return $columns;
	}
);

add_action(
	'manage_property_posts_custom_column',
	static function ( string $column_name, int $post_id ): void {
		if ( 'property_price' === $column_name ) {
			$price = get_post_meta( $post_id, '_aurelia_property_price', true );

			if ( '' !== $price && is_numeric( $price ) ) {
				echo esc_html( '$' . number_format_i18n( (float) $price, 0 ) );
			}
		}

		if ( 'property_featured' === $column_name ) {
			$is_featured = '1' === get_post_meta( $post_id, '_aurelia_property_is_featured', true );

			if ( $is_featured ) {
				echo '<span class="aurelia-featured-badge">' . esc_html__( 'Featured', 'aurelia-properties' ) . '</span>';
			} else {
				echo '<span class="aurelia-muted-value">' . esc_html__( '—', 'aurelia-properties' ) . '</span>';
			}
		}
	},
	10,
	2
);

add_filter(
	'manage_edit-property_sortable_columns',
	static function ( array $columns ): array {
		$columns['property_price'] = 'property_price';

		return $columns;
	}
);

add_action(
	'pre_get_posts',
	static function ( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() || 'property' !== $query->get( 'post_type' ) ) {
			return;
		}

		if ( 'property_price' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', '_aurelia_property_price' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
);

add_action(
	'save_post_property',
	static function ( int $post_id ): void {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['aurelia_property_details_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['aurelia_property_details_nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'aurelia_save_property_details' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$taxonomy_fields = array(
			'aurelia_property_city' => 'property_city',
			'aurelia_property_type' => 'property_type',
		);

		foreach ( $taxonomy_fields as $field_name => $taxonomy ) {
			$term_id = isset( $_POST[ $field_name ] ) ? absint( wp_unslash( $_POST[ $field_name ] ) ) : 0;

			if ( 0 === $term_id ) {
				wp_set_object_terms( $post_id, array(), $taxonomy, false );
				continue;
			}

			if ( get_term( $term_id, $taxonomy ) instanceof WP_Term ) {
				wp_set_object_terms( $post_id, array( $term_id ), $taxonomy, false );
			}
		}

		$decimal_fields = array(
			'aurelia_property_price' => '_aurelia_property_price',
			'aurelia_property_area'  => '_aurelia_property_area',
		);

		foreach ( $decimal_fields as $field_name => $meta_key ) {
			$value = isset( $_POST[ $field_name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) : '';
			$value = str_replace( ',', '.', $value );

			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta_key );
				continue;
			}

			if ( is_numeric( $value ) && (float) $value >= 0 ) {
				update_post_meta( $post_id, $meta_key, number_format( (float) $value, 2, '.', '' ) );
			}
		}

		$integer_fields = array(
			'aurelia_property_bedrooms'  => '_aurelia_property_bedrooms',
			'aurelia_property_bathrooms' => '_aurelia_property_bathrooms',
		);

		foreach ( $integer_fields as $field_name => $meta_key ) {
			$value = isset( $_POST[ $field_name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) : '';

			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta_key );
				continue;
			}

			if ( ctype_digit( $value ) ) {
				update_post_meta( $post_id, $meta_key, absint( $value ) );
			}
		}

		update_post_meta( $post_id, '_aurelia_property_is_featured', isset( $_POST['aurelia_property_is_featured'] ) ? '1' : '0' );
	}
);

add_action(
	'pre_get_posts',
	static function ( WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'property' ) ) {
			return;
		}

		$tax_query = array();
		$meta_query = array();
		$city       = isset( $_GET['city'] ) ? sanitize_title( wp_unslash( $_GET['city'] ) ) : '';
		$type       = isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : '';
		$min_price  = isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '';
		$max_price  = isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '';

		if ( $city && get_term_by( 'slug', $city, 'property_city' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'property_city',
				'field'    => 'slug',
				'terms'    => $city,
			);
		}

		if ( $type && get_term_by( 'slug', $type, 'property_type' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'property_type',
				'field'    => 'slug',
				'terms'    => $type,
			);
		}

		if ( is_numeric( $min_price ) && (float) $min_price >= 0 ) {
			$meta_query[] = array(
				'key'     => '_aurelia_property_price',
				'value'   => (float) $min_price,
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}

		if ( is_numeric( $max_price ) && (float) $max_price >= 0 ) {
			$meta_query[] = array(
				'key'     => '_aurelia_property_price',
				'value'   => (float) $max_price,
				'compare' => '<=',
				'type'    => 'NUMERIC',
			);
		}

		if ( $tax_query ) {
			$query->set( 'tax_query', $tax_query );
		}

		if ( $meta_query ) {
			$query->set( 'meta_query', $meta_query );
		}

		$query->set( 'posts_per_page', 9 );
	}
);

/**
 * Handle a public contact form submission.
 *
 * @return void
 */
function aurelia_properties_handle_contact_submission(): void {
	$fallback_url = home_url( '/contact/' );
	$return_url   = wp_validate_redirect( wp_get_referer(), $fallback_url );
	$nonce        = isset( $_POST['aurelia_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['aurelia_contact_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'aurelia_submit_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact-status', 'invalid', $return_url ) );
		exit;
	}

	$name    = isset( $_POST['aurelia_contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['aurelia_contact_name'] ) ) : '';
	$email   = isset( $_POST['aurelia_contact_email'] ) ? sanitize_email( wp_unslash( $_POST['aurelia_contact_email'] ) ) : '';
	$message = isset( $_POST['aurelia_contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['aurelia_contact_message'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		wp_safe_redirect( add_query_arg( 'contact-status', 'invalid', $return_url ) );
		exit;
	}

	$subject = sprintf( __( 'New Aurelia Homes inquiry from %s', 'aurelia-properties' ), $name );
	$body    = sprintf(
		"Name: %1\$s\nEmail: %2\$s\n\nMessage:\n%3\$s",
		$name,
		$email,
		$message
	);
	$sent    = wp_mail( get_option( 'admin_email' ), $subject, $body, array( 'Reply-To: ' . $email ) );
	$status  = $sent ? 'sent' : 'error';

	wp_safe_redirect( add_query_arg( 'contact-status', $status, $return_url ) );
	exit;
}

add_action( 'admin_post_aurelia_contact', 'aurelia_properties_handle_contact_submission' );
add_action( 'admin_post_nopriv_aurelia_contact', 'aurelia_properties_handle_contact_submission' );
