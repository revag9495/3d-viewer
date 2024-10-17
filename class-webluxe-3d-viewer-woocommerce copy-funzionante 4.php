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
    // Altezza Desktop
    woocommerce_wp_text_input( array(
        'id'          => 'webluxe_3d_viewer_height_desktop',
        'label'       => __( 'Altezza Desktop (vh)', 'webluxe-3d-viewer' ),
        'value'       => $height_desktop,
        'type'        => 'number',
        'placeholder' => '75',
    ));
    // Altezza Tablet
    woocommerce_wp_text_input( array(
        'id'          => 'webluxe_3d_viewer_height_tablet',
        'label'       => __( 'Altezza Tablet (vh)', 'webluxe-3d-viewer' ),
        'value'       => $height_tablet,
        'type'        => 'number',
        'placeholder' => '60',
    ));
    // Altezza Mobile
    woocommerce_wp_text_input( array(
        'id'          => 'webluxe_3d_viewer_height_mobile',
        'label'       => __( 'Altezza Mobile (vh)', 'webluxe-3d-viewer' ),
        'value'       => $height_mobile,
        'type'        => 'number',
        'placeholder' => '50',
    ));

    // Checkbox per mostrare l'icona su vari dispositivi
    woocommerce_wp_checkbox( array(
        'id'          => 'webluxe_3d_viewer_show_icon_desktop',
        'label'       => __( 'Mostra Icona su Desktop', 'webluxe-3d-viewer' ),
        'value'       => $show_icon_desktop,
    ));
    woocommerce_wp_checkbox( array(
        'id'          => 'webluxe_3d_viewer_show_icon_tablet',
        'label'       => __( 'Mostra Icona su Tablet', 'webluxe-3d-viewer' ),
        'value'       => $show_icon_tablet,
    ));
    woocommerce_wp_checkbox( array(
        'id'          => 'webluxe_3d_viewer_show_icon_mobile',
        'label'       => __( 'Mostra Icona su Mobile', 'webluxe-3d-viewer' ),
        'value'       => $show_icon_mobile,
    ));

    echo '</div>';
    echo '</div>';
}
add_action( 'woocommerce_product_data_panels', 'webluxe_3d_viewer_product_tab_content' );

// Salva i dati della scheda "3D Viewer"
function webluxe_3d_viewer_save_product_data( $post_id ) {
    $icon_id = isset( $_POST['webluxe_3d_viewer_icon_id'] ) ? intval( $_POST['webluxe_3d_viewer_icon_id'] ) : '';
    $model_url = isset( $_POST['webluxe_3d_model_url'] ) ? sanitize_text_field( $_POST['webluxe_3d_model_url'] ) : '';
    $height_desktop = isset( $_POST['webluxe_3d_viewer_height_desktop'] ) ? sanitize_text_field( $_POST['webluxe_3d_viewer_height_desktop'] ) : '75';
    $height_tablet = isset( $_POST['webluxe_3d_viewer_height_tablet'] ) ? sanitize_text_field( $_POST['webluxe_3d_viewer_height_tablet'] ) : '60';
    $height_mobile = isset( $_POST['webluxe_3d_viewer_height_mobile'] ) ? sanitize_text_field( $_POST['webluxe_3d_viewer_height_mobile'] ) : '50';
    $show_icon_desktop = isset( $_POST['webluxe_3d_viewer_show_icon_desktop'] ) ? 'yes' : 'no';
    $show_icon_tablet = isset( $_POST['webluxe_3d_viewer_show_icon_tablet'] ) ? 'yes' : 'no';
    $show_icon_mobile = isset( $_POST['webluxe_3d_viewer_show_icon_mobile'] ) ? 'yes' : 'no';

    update_post_meta( $post_id, '_webluxe_3d_viewer_icon_id', $icon_id );
    update_post_meta( $post_id, '_webluxe_3d_model_url', esc_url( $model_url ) );
    update_post_meta( $post_id, '_webluxe_3d_viewer_height_desktop', $height_desktop );
    update_post_meta( $post_id, '_webluxe_3d_viewer_height_tablet', $height_tablet );
    update_post_meta( $post_id, '_webluxe_3d_viewer_height_mobile', $height_mobile );
    update_post_meta( $post_id, '_webluxe_3d_viewer_show_icon_desktop', $show_icon_desktop );
    update_post_meta( $post_id, '_webluxe_3d_viewer_show_icon_tablet', $show_icon_tablet );
    update_post_meta( $post_id, '_webluxe_3d_viewer_show_icon_mobile', $show_icon_mobile );
}
add_action( 'woocommerce_process_product_meta', 'webluxe_3d_viewer_save_product_data' );

// Aggiunge lo stile per l'icona con i controlli di visibilità
function webluxe_enqueue_custom_scripts_and_styles() {
    global $post;

    $icon_id = get_post_meta( $post->ID, '_webluxe_3d_viewer_icon_id', true );
    $icon_url = wp_get_attachment_url( $icon_id );

    $show_icon_desktop = get_post_meta( $post->ID, '_webluxe_3d_viewer_show_icon_desktop', true ) === 'yes';
    $show_icon_tablet = get_post_meta( $post->ID, '_webluxe_3d_viewer_show_icon_tablet', true ) === 'yes';
    $show_icon_mobile = get_post_meta( $post->ID, '_webluxe_3d_viewer_show_icon_mobile', true ) === 'yes';

    if ( $icon_url ) : ?>
        <style>
            .custom-icon {
                position: absolute;
                bottom: 27%;
                right: 3%;
                width: 40px;
                height: 40px;
                background: url('<?php echo esc_url( $icon_url ); ?>') no-repeat center center;
                background-size: contain;
                z-index: 10;
                border-radius: 100%;
                box-sizing: content-box;
                cursor: pointer;
                display: none;
            }
            <?php if ( $show_icon_desktop ) : ?>
            @media (min-width: 1024px) { .custom-icon { display: flex; } }
            <?php endif; ?>
            <?php if ( $show_icon_tablet ) : ?>
            @media (min-width: 768px) and (max-width: 1023px) { .custom-icon { display: flex; } }
            <?php endif; ?>
            <?php if ( $show_icon_mobile ) : ?>
            @media (max-width: 767px) { .custom-icon { display: flex; } }
            <?php endif; ?>
        </style>
        <script>
            jQuery(document).ready(function($) {
                $('.woocommerce-product-gallery').append('<div class="custom-icon"></div>');
            });
        </script>
    <?php
    endif;
}
add_action( 'wp_head', 'webluxe_enqueue_custom_scripts_and_styles' );

// Aggiungi il visualizzatore 3D come prima immagine della galleria
function webluxe_add_3d_viewer_to_gallery( $html, $attachment_id ) {
    global $product;

    $enable_3d_viewer = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_enabled', true );
    $model_url = get_post_meta( $product->get_id(), '_webluxe_3d_model_url', true );
    $height_desktop = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_height_desktop', true ) ?: '75';
    $height_tablet = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_height_tablet', true ) ?: '60';
    $height_mobile = get_post_meta( $product->get_id(), '_webluxe_3d_viewer_height_mobile', true ) ?: '50';

    if ( $enable_3d_viewer === 'yes' && $attachment_id == get_post_thumbnail_id( $product->get_id() ) ) {
        ob_start();
        ?>
        <div class="woocommerce-product-gallery__image woocommerce-product-gallery__image--3d">
            <div id="webluxe-3d-viewer-container" style="width: 100%; height: <?php echo esc_attr( $height_desktop ); ?>vh;">
                <script type="module">
                    document.addEventListener("DOMContentLoaded", function() {
                        const viewerContainer = document.getElementById('webluxe-3d-viewer-container');
                        if (!viewerContainer.dataset.initialized) {
                            viewerContainer.dataset.initialized = true;
                            const height = window.innerWidth >= 1024 ? "<?php echo esc_attr( $height_desktop ); ?>" :
                                           window.innerWidth >= 768 ? "<?php echo esc_attr( $height_tablet ); ?>" :
                                           "<?php echo esc_attr( $height_mobile ); ?>";
                            viewerContainer.style.height = height + "vh";

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

// Script jQuery per aggiungere l'azione di clic sull'icona
add_action('wp_footer', 'webluxe_add_3d_viewer_icon_script');
function webluxe_add_3d_viewer_icon_script() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Aggiungi l'icona al contenitore della galleria
            $('.woocommerce-product-gallery').append('<div class="custom-icon"></div>');
            
            $(document).on('click', '.custom-icon', function() {
                console.log("Icona '3D' cliccata!");
                const galleryThumbs = $('.flex-control-nav.flex-control-thumbs li img');
                
                if (galleryThumbs.length > 0) {
                    galleryThumbs.removeClass('flex-active');
                    $(galleryThumbs[0]).addClass('flex-active');
                    const mainGalleryImages = $('.woocommerce-product-gallery__image');
                    mainGalleryImages.removeClass('flex-active-slide');
                    $(mainGalleryImages[0]).addClass('flex-active-slide');

                    // Reimposta l'altezza del contenitore .flex-viewport
                    setTimeout(function() {
                        $('.woocommerce-product-gallery__wrapper').css('transform', 'translate3d(0px, 0px, 0px)');
                        setViewerAndViewportHeight(); // Chiama la funzione per reimpostare l'altezza
                    }, 200);
                } else {
                    console.error("Non sono state trovate miniature della galleria.");
                }
            });

            // Funzione per impostare l'altezza del visualizzatore 3D e del contenitore flex-viewport in base al dispositivo
            function setViewerAndViewportHeight() {
                const viewerContainer = $('#webluxe-3d-viewer-container');
                const flexViewport = $('.flex-viewport');
                
                if (!viewerContainer.length || !flexViewport.length) return;

                let heightValue;
                
                // Imposta l'altezza in base al dispositivo
                if (window.innerWidth >= 1024) { // Desktop
                    heightValue = '<?php echo esc_js(get_post_meta(get_the_ID(), "_webluxe_viewer_height_desktop", true)); ?>vh';
                } else if (window.innerWidth >= 768) { // Tablet
                    heightValue = '<?php echo esc_js(get_post_meta(get_the_ID(), "_webluxe_viewer_height_tablet", true)); ?>vh';
                } else { // Mobile
                    heightValue = '<?php echo esc_js(get_post_meta(get_the_ID(), "_webluxe_viewer_height_mobile", true)); ?>vh';
                }

                // Applica l’altezza al visualizzatore e alla flex-viewport
                viewerContainer.css('height', heightValue);
                flexViewport.css('height', heightValue);
            }

            // Osserva il cambiamento delle classi sulle immagini della galleria
            const targetNode = document.querySelector('.woocommerce-product-gallery__wrapper');
            if (targetNode) {
                const config = { attributes: true, subtree: true, attributeFilter: ['class'] };
                
                const callback = function(mutationsList) {
                    for (let mutation of mutationsList) {
                        if (mutation.type === 'attributes' && mutation.target.classList.contains('flex-active-slide')) {
                            setViewerAndViewportHeight();
                        }
                    }
                };

                const observer = new MutationObserver(callback);
                observer.observe(targetNode, config);
            }

            // Inizializza l'altezza corretta al caricamento della pagina
            setViewerAndViewportHeight();
        });
    </script>
    <?php
}


// Script jQuery per disabilitare completamente il trascinamento della galleria su mobile durante l'interazione con il modello 3D
add_action('wp_footer', 'webluxe_disable_gallery_drag_on_3d_interaction');
function webluxe_disable_gallery_drag_on_3d_interaction() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            const galleryWrapper = $('.woocommerce-product-gallery__wrapper');

            // Funzione per disabilitare eventi di trascinamento e tocco
            function disableGalleryTouch() {
                galleryWrapper.on('touchstart touchmove touchend mousedown mousemove mouseup', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                });
            }

            // Funzione per riabilitare eventi di trascinamento e tocco
            function enableGalleryTouch() {
                galleryWrapper.off('touchstart touchmove touchend mousedown mousemove mouseup');
            }

            // Disabilita eventi di trascinamento e tocco della galleria durante l'interazione con il modello 3D
            $('#webluxe-3d-viewer-container').on('touchstart mousedown', function() {
                disableGalleryTouch();
            });

            // Riabilita eventi di trascinamento e tocco della galleria al termine dell'interazione con il modello 3D
            $('#webluxe-3d-viewer-container').on('touchend mouseup', function() {
                enableGalleryTouch();
            });
        });
    </script>
    <?php
}
