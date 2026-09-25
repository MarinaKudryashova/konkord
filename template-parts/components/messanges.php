<?php
$list     = $args['list'] ?? null;
$field    = $args['field'] ?? 'messengers_list';
$ul_class = $args['class'] ?? 'messanges';

if ( null === $list ) {
	$list = get_field( $field, 'options' );
}

if ( empty( $list ) || ! is_array( $list ) ) {
	return;
}
?>
<ul class="<?php echo esc_attr( $ul_class ); ?>">
	<?php foreach ( $list as $li ) :
		$slug = isset( $li['value'] ) ? preg_replace( '/[^a-z0-9_-]/i', '', (string) $li['value'] ) : '';
		if ( $slug === '' ) {
			continue;
		}
		$url = get_field( $slug, 'options' );
		if ( empty( $url ) ) {
			continue;
		}
		$label = isset( $li['label'] ) ? (string) $li['label'] : $slug;
		?>
	<li class="messanges__item">
		<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( konkord_messenger_link_class( $slug ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Свяжитесь с нами в %s', 'konkord' ), $label ) ); ?>">
			<img loading="lazy" src="<?php echo esc_url( get_template_directory_uri() . '/img/icon/' . $slug . '.svg' ); ?>" class="messanges__icon" width="16" height="16" alt="" aria-hidden="true">
		</a>
	</li>
	<?php endforeach; ?>
</ul>
