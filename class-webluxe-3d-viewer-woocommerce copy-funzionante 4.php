<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Aggiunge la scheda "3D Viewer" nella pagina del prodotto WooCommerce
function webluxe_3d_viewer_add_product_tab( $tabs ) {
    $tabs['webluxe_3d_viewer'] = array(
        'label'    => __( '3D Viewer', 'webluxe-3d-viewer' ),
        'target'   => 'webluxe_3d_viewer_options',
        'class'    => array( 'show_if_simple', 'show_if_variable' ),
        'priority' => 50,
    );
    return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'webluxe_3d_viewer_add_product_tab' );

// Contenuto della scheda "3D Viewer" con selezione dell'icona, altezza e visibilità su dispositivi
function webluxe_3d_viewer_product_tab_content() {
    global $post;

    $icon_id = get_post_meta( $post->ID, '_webluxe_3d_viewer_icon_id', true );
    $model_url = get_post_meta( $post->ID, '_webluxe_3d_model_url', true );
    $thumbnail_id = get_post_meta( $post->ID, '_webluxe_3d_viewer_thumbnail_id', true ); // Thumbnail per la galleria
    $height_desktop = get_post_meta( $post->ID, '_webluxe_3d_viewer_height_desktop', true );
    $height_tablet = get_post_meta( $post->ID, '_webluxe_3d_viewer_height_tablet', true );
    $height_mobile = get_post_meta( $post->ID, '_webluxe_3d_viewer_height_mobile', true );
    $show_icon_desktop = get_post_meta( $post->ID, '_webluxe_3d_viewer_show_icon_desktop', true );
    $show_icon_tablet = get_post_meta( $post->ID, '_webluxe_3d_viewer_show_icon_tablet', true );
    $show_icon_mobile = get_post_meta( $post->ID, '_webluxe_3d_viewer_show_icon_mobile', true );

    echo '<div id="webluxe_3d_viewer_options" class="panel woocommerce_options_panel">';
    echo '<div class="options_group">';

    // Campo per l'URL del modello GLB
    woocommerce_wp_text_input( array(
        'id'          => 'webluxe_3d_model_url',
        'label'       => __( 'Model GLB URL', 'webluxe-3d-viewer' ),
        'description' => __( 'URL del file GLB per il visualizzatore 3D', 'webluxe-3d-viewer' ),
        'value'       => $model_url,
        'desc_tip'    => true,
    ));

    // Pulsante per selezionare l'icona del visualizzatore 3D
    ?>
    <p class="form-field">
        <label><?php esc_html_e( '3D Viewer Thumbnail Icon', 'webluxe-3d-viewer' ); ?></label>
        <button type="button" class="button" id="webluxe_3d_viewer_icon_button"><?php esc_html_e( 'Scegli Icona', 'webluxe-3d-viewer' ); ?></button>
        <button type="button" class="button" id="webluxe_3d_viewer_icon_remove"><?php esc_html_e( 'Rimuovi Icona', 'webluxe-3d-viewer' ); ?></button>
        <img id="webluxe_3d_viewer_icon_preview" src="<?php echo esc_url( wp_get_attachment_url( $icon_id ) ); ?>" style="max-width: 50px; display: <?php echo empty( $icon_id ) ? 'none' : 'block'; ?>;" />
        <input type="hidden" id="webluxe_3d_viewer_icon_id" name="webluxe_3d_viewer_icon_id" value="<?php echo esc_attr( $icon_id ); ?>">
    </p>

    <!-- Selezione della thumbnail specifica per il visualizzatore -->
    <p class="form-field">
        <label><?php esc_html_e( 'Thumbnail per Visualizzatore 3D', 'webluxe-3d-viewer' ); ?></label>
        <button type="button" class="button" id="webluxe_3d_viewer_thumbnail_button"><?php esc_html_e( 'Scegli Immagine', 'webluxe-3d-viewer' ); ?></button>
        <button type="button" class="button" id="webluxe_3d_viewer_thumbnail_remove"><?php esc_html_e( 'Rimuovi Immagine', 'webluxe-3d-viewer' ); ?></button>
        <img id="webluxe_3d_viewer_thumbnail_preview" src="<?php echo esc_url( wp_get_attachment_url( $thumbnail_id ) ); ?>" style="max-width: 50px; display: <?php echo empty( $thumbnail_id ) ? 'none' : 'block'; ?>;" />
        <input type="hidden" id="webluxe_3d_viewer_thumbnail_id" name="webluxe_3d_viewer_thumbnail_id" value="<?php echo esc_attr( $thumbnail_id ); ?>">
    </p>
