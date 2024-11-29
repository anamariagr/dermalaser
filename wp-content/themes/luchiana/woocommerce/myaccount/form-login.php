<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version  10.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$is_custom_register_page = ideapark_mod( 'register_page' ) && ideapark_mod( 'register_page' ) != get_option( 'woocommerce_myaccount_page_id' );
?>
<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="c-login" id="customer_login">

	<div class="c-login__form js-login-form c-login__form--active">
		<div class="c-login__header"><?php esc_html_e( 'Login', 'woocommerce' ); ?></div>
		<form class="c-form login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<div class="c-form__row">
				<input type="text"
					   class="c-form__input c-form__input--full c-form__input--fill woocommerce-Input woocommerce-Input--text input-text"
					   placeholder="<?php esc_attr_e( 'Username or email address', 'woocommerce' ); ?> *"
					   name="username" id="username" autocomplete="username"
					   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/>
			</div>
			<div class="c-form__row">
				<input
					class="c-form__input c-form__input--full c-form__input--fill woocommerce-Input woocommerce-Input--text input-text"
					placeholder="<?php esc_attr_e( 'Password', 'woocommerce' ); ?> *" type="password" name="password"
					id="password" autocomplete="current-password"/>
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="c-form__row c-form__row--inline c-login__remember">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<label class="c-form__label">
					<input class="c-form__checkbox" name="rememberme" type="checkbox" id="rememberme"
						   value="forever"/> <?php esc_html_e( 'Remember me', 'woocommerce' ); ?>
				</label>
			</div>

			<div class="c-form__row">
				<button type="submit" class="c-button c-button--outline c-button--full woocommerce-Button button"
						name="login"
						value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
			</div>


			<div class="c-login__bottom">
				<div class="c-login__lost-password">
					<a class="c-login__lost-password-link"
					   href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
				</div>
				<?php if ( $is_custom_register_page ) { ?>
				<a href="<?php echo esc_url( get_permalink( apply_filters( 'wpml_object_id', ideapark_mod( 'register_page' ), 'any' ) ) ); ?>"
				   class="c-login__register">
					<?php } else { ?>
					<a href="" onclick="return false;"
					   class="c-login__register js-login-form-toggle">
						<?php } ?>
						<?php esc_html_e( 'Register', 'woocommerce' ); ?><i
							class="ip-menu-right c-login__more-icon"></i></a>
			</div>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>
	</div>

	<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' && ! $is_custom_register_page ) { ?>

		<div class="c-login__form js-register-form">

			<div class="c-login__header"><?php esc_html_e( 'Register', 'woocommerce' ); ?></div>
			<form method="post" class="c-form register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<div class="c-form__row">
						<input type="text" placeholder="<?php esc_html_e( 'Username', 'woocommerce' ); ?> *"
							   class="c-form__input c-form__input--full c-form__input--fill woocommerce-Input woocommerce-Input--text input-text"
							   name="username" id="reg_username" autocomplete="username"
							   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/>
					</div>

				<?php endif; ?>

				<div class="c-form__row">
					<input type="email" placeholder="<?php esc_attr_e( 'Email address', 'woocommerce' ); ?> *"
						   class="c-form__input c-form__input--full c-form__input--fill woocommerce-Input woocommerce-Input--text input-text"
						   name="email" id="reg_email" autocomplete="email"
						   value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/>
				</div>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<div class="c-form__row">
						<input type="password" placeholder="<?php esc_attr_e( 'Password', 'woocommerce' ); ?> *"
							   class="c-form__input c-form__input--full c-form__input--fill woocommerce-Input woocommerce-Input--text input-text"
							   name="password" id="reg_password" autocomplete="new-password"/>
					</div>

				<?php else : ?>

					<div
						class="c-form__row"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<div class="c-form__row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="c-button c-button--outline c-button--full woocommerce-Button button"
							name="register"
							value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
				</div>

				<div class="c-login__bottom">
					<div>
					</div>
					<a href="" onclick="return false;"
					   class="c-login__register js-login-form-toggle"><?php esc_html_e( 'Login', 'woocommerce' ); ?><i
							class="ip-menu-right c-login__more-icon"></i></a>
				</div>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>
		</div>

	<?php } ?>

	<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
</div>
