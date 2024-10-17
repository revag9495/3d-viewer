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

// Contenuto della scheda "3D Viewer"
function webluxe_3d_viewer_product_tab_content() {
    global $post;

    $model_url = get_post_meta( $post->ID, '_webluxe_3d_model_url', true );
    $enable_3d_viewer = get_post_meta( $post->ID, '_webluxe_3d_viewer_enabled', true );
    $icon_id = get_post_meta( $post->ID, '_webluxe_3d_viewer_icon_id', true );
    $icon_css = get_post_meta( $post->ID, '_webluxe_3d_viewer_icon_css', true );

    echo '<div id="webluxe_3d_viewer_options" class="panel woocommerce_options_panel">';
    echo '<div class="options_group">';

    // Input per l'URL del modello GLB
    woocommerce_wp_text_input( array(
        'id'          => 'webluxe_3d_model_url',
        'label'       => __( 'Model GLB URL', 'webluxe-3d-viewer' ),
        'description' => __( 'URL del file GLB per il visualizzatore 3D', 'webluxe-3d-viewer' ),
        'value'       => $model_url,
        'desc_tip'    => true,
    ));

    // Checkbox per abilitare il visualizzatore 3D
    woocommerce_wp_checkbox( array(
        'id'          => 'webluxe_3d_viewer_enabled',
        'label'       => __( 'Abilita 3D Viewer', 'webluxe-3d-viewer' ),
        'description' => __( 'Mostra il visualizzatore 3D come immagine principale del prodotto', 'webluxe-3d-viewer' ),
        'value'       => $enable_3d_viewer,
        'desc_tip'    => true,
    ));

    // Pulsante per selezionare l'icona del visualizzatore 3D
    ?>
    <p class="form-field">
        <label><?php esc_html_e( '3D Viewer Icon', 'webluxe-3d-viewer' ); ?></label>
        <button type="button" class="button" id="webluxe_3d_viewer_icon_button"><?php esc_html_e( 'Scegli Icona', 'webluxe-3d-viewer' ); ?></button>
        <button type="button" class="button" id="webluxe_3d_viewer_icon_remove"><?php esc_html_e( 'Rimuovi Icona', 'webluxe-3d-viewer' ); ?></button>
        <img id="webluxe_3d_viewer_icon_preview" src="<?php echo esc_url( wp_get_attachment_url( $icon_id ) ); ?>" style="max-width: 50px; display: <?php echo empty( $icon_id ) ? 'none' : 'block'; ?>;" />
        <input type="hidden" id="webluxe_3d_viewer_icon_id" name="webluxe_3d_viewer_icon_id" value="<?php echo esc_attr( $icon_id ); ?>">
    </p>
    <script>
        jQuery(document).ready(function($) {
            let frame;
            $('#webluxe_3d_viewer_icon_button').on('click', function(e) {
                e.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: '<?php esc_html_e( 'Seleziona o Carica Icona', 'webluxe-3d-viewer' ); ?>',
                    button: {
                        text: '<?php esc_html_e( 'Usa questa immagine', 'webluxe-3d-viewer' ); ?>',
                    },
                    multiple: false
                });
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    $('#webluxe_3d_viewer_icon_id').val(attachment.id);
                    $('#webluxe_3d_viewer_icon_preview').attr('src', attachment.url).show();
                });
                frame.open();
            });

            $('#webluxe_3d_viewer_icon_remove').on('click', function() {
                $('#webluxe_3d_viewer_icon_id').val('');
                $('#webluxe_3d_viewer_icon_preview').hide();
            });
        });
    </script>

    <?php
    // Campo di testo per CSS personalizzato dell'icona
    woocommerce_wp_textarea_input( array(
        'id'          => 'webluxe_3d_viewer_icon_css',
        'label'       => __( 'CSS Personalizzato Icona', 'webluxe-3d-viewer' ),
        'description' => __( 'Inserisci CSS personalizzato per il bordo, la posizione e altre proprietà dell\'icona del visualizzatore.', 'webluxe-3d-viewer' ),
        'value'       => $icon_css,
        'desc_tip'    => true,
    ));

    echo '</div>';
    echo '</div>';
}
add_action( 'woocommerce_product_data_panels', 'webluxe_3d_viewer_product_tab_content' );

// Salva i dati della scheda "3D Viewer"
function webluxe_3d_viewer_save_product_data( $post_id ) {
    $model_url = isset( $_POST['webluxe_3d_model_url'] ) ? sanitize_text_field( $_POST['webluxe_3d_model_url'] ) : '';
    $enable_3d_viewer = isset( $_POST['webluxe_3d_viewer_enabled'] ) ? 'yes' : 'no';
    $icon_id = isset( $_POST['webluxe_3d_viewer_icon_id'] ) ? intval( $_POST['webluxe_3d_viewer_icon_id'] ) : '';
    $icon_css = isset( $_POST['webluxe_3d_viewer_icon_css'] ) ? wp_strip_all_tags( $_POST['webluxe_3d_viewer_icon_css'] ) : '';

    update_post_meta( $post_id, '_webluxe_3d_model_url', esc_url( $model_url ) );
    update_post_meta( $post_id, '_webluxe_3d_viewer_enabled', $enable_3d_viewer );
    update_post_meta( $post_id, '_webluxe_3d_viewer_icon_id', $icon_id );
    update_post_meta( $post_id, '_webluxe_3d_viewer_icon_css', $icon_css );
}
add_action( 'woocommerce_process_product_meta', 'webluxe_3d_viewer_save_product_data' );

// Aggiungi il visualizzatore 3D come prima immagine della galleria senza alterare la galleria stessa
function webluxe_add_3d_viewer_to_gallery( $html, $attachment_id ) {
    global $product;

    $enable_3d_viewer = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_enabled', true );
    $model_url = get_post_meta( $product->get_id(), '_webluxe_3d_model_url', true );

    if ( $enable_3d_viewer === 'yes' && $attachment_id == get_post_thumbnail_id( $product->get_id() ) ) {
        ob_start();
        ?>
        <div class="woocommerce-product-gallery__image woocommerce-product-gallery__image--3d">
            <div id="webluxe-3d-viewer-container" style="width: 100%; height: auto;">
                <!-- Il tuo script del visualizzatore 3D -->
                <script type="module">
                    document.addEventListener("DOMContentLoaded", function() {
                        const viewerContainer = document.getElementById('webluxe-3d-viewer-container');
                        if (!viewerContainer.dataset.initialized) {
                            viewerContainer.dataset.initialized = true;

                            const project = { modelUrl: "<?php echo esc_url( $model_url ); ?>" };
                            const viewerOptions = { showLogo: true, showCard: true };

                            if (typeof WebLuxeViewer !== 'undefined') {
                                window.webluxeViewerInstance = new WebLuxeViewer.Viewer(viewerContainer, project, viewerOptions);
                            } else {
                                console.error("WebLuxeViewer non è definito. Assicurati che la libreria sia caricata correttamente.");
                            }
                        }
                    });
                </script>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
    }
    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'webluxe_add_3d_viewer_to_gallery', 10, 2 );

// Aggiungi l'icona sulle immagini della galleria per richiamare il visualizzatore 3D
function webluxe_add_3d_viewer_icon_to_gallery_images( $html, $attachment_id ) {
    global $product;

    $enable_3d_viewer = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_enabled', true );
    $icon_id = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_icon_id', true );
    $icon_url = wp_get_attachment_url( $icon_id );
    $icon_css = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_icon_css', true );

    if ( $enable_3d_viewer === 'yes' && ! empty( $icon_url ) && $attachment_id != get_post_thumbnail_id( $product->get_id() ) ) {
        // Aggiungi l'icona come overlay sull'immagine
        $html = '<div class="webluxe-3d-viewer-thumbnail" style="position: relative;">';
        $html .= wp_get_attachment_image( $attachment_id, 'woocommerce_single' );
        $html .= '<div class="webluxe-3d-viewer-icon-overlay" style="' . esc_attr( $icon_css ) . '">';
        $html .= '<img src="' . esc_url( $icon_url ) . '" alt="3D Viewer Icon">';
        $html .= '</div>';
        $html .= '</div>';
    }
    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'webluxe_add_3d_viewer_icon_to_gallery_images', 20, 2 );

// Aggiungi stili CSS per l'icona e il visualizzatore
function webluxe_enqueue_custom_styles() {
    if ( is_product() ) {
        ?>
        <style>
            .webluxe-3d-viewer-thumbnail {
                position: relative;
            }
            .webluxe-3d-viewer-icon-overlay {
                position: absolute;
                top: 10px;
                right: 10px;
                z-index: 10;
                cursor: pointer;
            }
            .webluxe-3d-viewer-icon-overlay img {
                max-width: 50px;
            }
            .woocommerce-product-gallery__image--3d {
                position: relative;
            }
        </style>
        <?php
    }
}
add_action( 'wp_head', 'webluxe_enqueue_custom_styles' );

// Gestisci il comportamento con JavaScript senza modificare il modo in cui il visualizzatore viene caricato
function webluxe_enqueue_custom_scripts() {
    if ( is_product() ) {
        ?>
        <script>
            jQuery(document).ready(function($) {
                // Trova l'indice del visualizzatore 3D
                var viewerIndex = 0;

                // Gestisci il click sull'icona per tornare al visualizzatore 3D
                $('.webluxe-3d-viewer-icon-overlay').on('click', function(e) {
                    e.preventDefault();
                    // Simula il click sulla prima miniatura
                    $('.woocommerce-product-gallery__thumbs .flex-control-nav li:eq(0) a').trigger('click');
                });
            });
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'webluxe_enqueue_custom_scripts' );

// Non modifichiamo il modo in cui il visualizzatore viene caricato tramite enqueue, poiché è già gestito altrove
