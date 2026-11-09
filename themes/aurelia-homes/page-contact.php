<?php
/**
 * Contact page template.
 *
 * @package AureliaHomes
 */

get_header();

$status = isset( $_GET['contact-status'] ) ? sanitize_key( wp_unslash( $_GET['contact-status'] ) ) : '';
?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article <?php post_class( 'contact-page' ); ?>>
			<header class="contact-page__header">
				<p class="eyebrow"><?php esc_html_e( 'Get in touch', 'aurelia-homes' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<div class="contact-page__intro"><?php the_content(); ?></div>
			</header>

			<?php if ( 'sent' === $status ) : ?>
				<p class="form-notice form-notice--success" role="status"><?php esc_html_e( 'Thanks — your message has been sent.', 'aurelia-homes' ); ?></p>
			<?php elseif ( 'invalid' === $status ) : ?>
				<p class="form-notice form-notice--error" role="alert"><?php esc_html_e( 'Please complete all fields with a valid email address.', 'aurelia-homes' ); ?></p>
			<?php elseif ( 'error' === $status ) : ?>
				<p class="form-notice form-notice--error" role="alert"><?php esc_html_e( 'Your message could not be sent. Please try again later.', 'aurelia-homes' ); ?></p>
			<?php endif; ?>

			<form class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="aurelia_contact">
				<?php wp_nonce_field( 'aurelia_submit_contact', 'aurelia_contact_nonce' ); ?>

				<p>
					<label for="contact-name"><?php esc_html_e( 'Name', 'aurelia-homes' ); ?></label>
					<input id="contact-name" name="aurelia_contact_name" type="text" autocomplete="name" required>
				</p>
				<p>
					<label for="contact-email"><?php esc_html_e( 'Email', 'aurelia-homes' ); ?></label>
					<input id="contact-email" name="aurelia_contact_email" type="email" autocomplete="email" required>
				</p>
				<p>
					<label for="contact-message"><?php esc_html_e( 'Message', 'aurelia-homes' ); ?></label>
					<textarea id="contact-message" name="aurelia_contact_message" rows="7" required></textarea>
				</p>
				<p><button class="button" type="submit"><?php esc_html_e( 'Send inquiry', 'aurelia-homes' ); ?></button></p>
			</form>
		</article>
	<?php endwhile; ?>
<?php
get_footer();
