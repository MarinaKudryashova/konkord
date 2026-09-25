<?php
/**
 * Верификация Google/Yandex + Яндекс.Метрика.
 *
 * Значения из ACF: Настройки сайта → Аналитика.
 * Метрика в HTML целиком (для проверки сервисом), type="text/plain" —
 * браузер не выполняет до согласия cookie; после «Согласен» cookie-notice.js
 * переключает на text/javascript.
 *
 * @package konkord
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param string $name ACF field name.
 * @return string
 */
function konkord_get_analytics_option( $name ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}
	$val = get_field( $name, 'option' );
	return is_string( $val ) ? trim( $val ) : '';
}

/**
 * Достаёт content из полного meta-тега или возвращает как есть.
 *
 * @param string $raw Raw admin value.
 * @return string
 */
function konkord_normalize_verification_content( $raw ) {
	$raw = trim( (string) $raw );
	if ( $raw === '' ) {
		return '';
	}
	if ( preg_match( '#content\s*=\s*["\']([^"\']+)["\']#i', $raw, $m ) ) {
		return trim( $m[1] );
	}
	return $raw;
}

/**
 * @return string digits only
 */
function konkord_normalize_metrika_id( $raw ) {
	return preg_replace( '/\D+/', '', (string) $raw );
}

/**
 * Google / Yandex site verification.
 */
function konkord_site_verification_meta() {
	$google = konkord_normalize_verification_content( konkord_get_analytics_option( 'google_site_verification' ) );
	$yandex = konkord_normalize_verification_content( konkord_get_analytics_option( 'yandex_verification' ) );

	if ( $google !== '' ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( $google ) . '" />' . "\n";
	}
	if ( $yandex !== '' ) {
		echo '<meta name="yandex-verification" content="' . esc_attr( $yandex ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'konkord_site_verification_meta', 1 );

/**
 * Яндекс.Метрика. Не исполняется без согласия на cookie.
 */
function konkord_yandex_metrika() {
	$id = konkord_normalize_metrika_id( konkord_get_analytics_option( 'yandex_metrika_id' ) );
	if ( $id === '' ) {
		return;
	}
	$id_js = esc_js( $id );
	$id_attr = esc_attr( $id );
	?>
<!-- Yandex.Metrika counter -->
<script type="text/plain" data-cookie-consent="analytics">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=<?php echo $id_js; ?>', 'ym');

    ym(<?php echo $id_js; ?>, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo $id_attr; ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
	<?php
}
add_action( 'wp_head', 'konkord_yandex_metrika', 5 );
