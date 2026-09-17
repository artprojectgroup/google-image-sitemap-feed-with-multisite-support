<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/**
 * Detecta si un plugin SEO ya está publicando un sitemap de imágenes.
 *
 * Sólo se avisa cuando consta que el sitemap del otro plugin está activo y que incluye
 * imágenes: ante la duda no se dice nada, porque un aviso equivocado llevaría a desactivar
 * el único sitemap de imágenes que tiene el sitio.
 *
 * @return string Nombre del plugin detectado, o cadena vacía.
 */
function apg_image_sitemap_detecta_seo() {
	//Rank Math: el sitemap es un módulo y las imágenes una opción suya
	if ( defined( 'RANK_MATH_VERSION' ) ) {
		$modulos    = (array) get_option( 'rank_math_modules', [] );
		$ajustes    = (array) get_option( 'rank_math_sitemap_settings', [] );
		if ( in_array( 'sitemap', $modulos, true ) && 'off' !== ( $ajustes[ 'include_images' ] ?? 'on' ) ) {
			return 'Rank Math SEO';
		}
	}

	//Yoast SEO: incluye siempre las imágenes en sus sitemaps, basta con que estén activos
	if ( defined( 'WPSEO_VERSION' ) && class_exists( 'WPSEO_Options' ) ) {
		if ( WPSEO_Options::get( 'enable_xml_sitemap', false ) ) {
			return 'Yoast SEO';
		}
	}

	//All in One SEO: la configuración viaja en un único campo JSON
	if ( defined( 'AIOSEO_VERSION' ) ) {
		$ajustes    = json_decode( (string) get_option( 'aioseo_options' ), true );
		$general    = $ajustes[ 'sitemap' ][ 'general' ] ?? [];
		if ( ! empty( $general[ 'enable' ] ) && empty( $general[ 'advancedSettings' ][ 'excludeImages' ] ) ) {
			return 'All in One SEO';
		}
	}

	//SEOPress: una opción para el sitemap y otra para las imágenes
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		if ( '1' === (string) get_option( 'seopress_xml_sitemap_general_enable' ) && '1' === (string) get_option( 'seopress_xml_sitemap_img_enable' ) ) {
			return 'SEOPress';
		}
	}

	return (string) apply_filters( 'apg_image_sitemap_seo_detectado', '' );
}

/**
 * Guarda que el aviso ya se ha descartado.
 *
 * @return void
 */
function apg_image_sitemap_descarta_aviso() {
	if ( ! isset( $_GET[ 'apg_image_sitemap_descarta' ] ) ) {
		return;
	}

	if ( ! isset( $_GET[ '_wpnonce' ] )
		|| ! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_GET[ '_wpnonce' ] ) ),
			'apg_image_sitemap_descarta_aviso'
		)
	) {
		return;
	}

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	update_user_meta( get_current_user_id(), 'apg_image_sitemap_aviso_descartado', '1' );

	wp_safe_redirect( remove_query_arg( [ 'apg_image_sitemap_descarta', '_wpnonce' ] ) );
	exit;
}
add_action( 'admin_init', 'apg_image_sitemap_descarta_aviso' );

/**
 * Avisa de que otro plugin ya publica un sitemap de imágenes.
 *
 * @return void
 */
function apg_image_sitemap_aviso_seo() {
	$pantalla   = get_current_screen();
	//Se muestra sólo donde se puede actuar, que es la propia pantalla de plugins
	if ( ! $pantalla || 0 !== strpos( $pantalla->id, 'plugins' ) ) {
		return;
	}

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( get_user_meta( get_current_user_id(), 'apg_image_sitemap_aviso_descartado', true ) ) {
		return;
	}

	$seo        = apg_image_sitemap_detecta_seo();
	if ( '' === $seo ) {
		return;
	}

	$desactivar = wp_nonce_url(
		self_admin_url( 'plugins.php?action=deactivate&plugin=' . rawurlencode( APG_IMAGE_SITEMAP_BASE ) ),
		'deactivate-plugin_' . APG_IMAGE_SITEMAP_BASE
	);
	$descartar  = wp_nonce_url(
		add_query_arg( 'apg_image_sitemap_descarta', '1' ),
		'apg_image_sitemap_descarta_aviso'
	);
	?>
	<div class="notice notice-warning">
		<p>
			<strong>APG Google Image Sitemap Feed</strong>:
			<?php
			printf(
				/* translators: %s: name of the detected SEO plugin. */
				esc_html__( '%s is already publishing a sitemap that includes your images, so this plugin is probably redundant. Keeping both is harmless, but you only need one.', 'google-image-sitemap-feed-with-multisite-support' ),
				'<strong>' . esc_html( $seo ) . '</strong>'
			);
			?>
		</p>
		<p>
			<a href="<?php echo esc_url( $desactivar ); ?>" class="button button-secondary"><?php esc_html_e( 'Deactivate this plugin', 'google-image-sitemap-feed-with-multisite-support' ); ?></a>
			<a href="<?php echo esc_url( $descartar ); ?>" class="button button-link"><?php esc_html_e( 'Keep it and hide this notice', 'google-image-sitemap-feed-with-multisite-support' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'apg_image_sitemap_aviso_seo' );
