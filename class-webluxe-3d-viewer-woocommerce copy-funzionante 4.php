protected function render() {
        $settings = $this->get_settings_for_display();
        $model_url = esc_url( $settings['model_url'] );

        // Verifica che l'URL del modello GLB sia impostato
        if ( ! empty( $model_url ) ) : ?>

            <div id="root"></div>

            <script type="module">
                // Verifica se il document è pronto
                document.addEventListener("DOMContentLoaded", function() {
                    // Configurazione del progetto e opzioni del visualizzatore
                    const project = {
                        modelUrl: "<?php echo $model_url; ?>", // URL del modello GLB
                    };

                    const viewerOptions = {
                        showLogo: true,
                        showCard: true,
                        // Altre opzioni del visualizzatore
                    };

                    // Inizializzazione del visualizzatore WebLuxe
                    if (typeof WebLuxeViewer !== 'undefined') {
                        new WebLuxeViewer.Viewer(document.getElementById("root"), project, viewerOptions);
                    } else {
                        console.error("WebLuxeViewer non è definito. Assicurati che la libreria sia caricata correttamente.");
                    }
                });
            </script>

        <?php else : ?>
            <p><?php _e( 'Inserisci un URL valido per il modello GLB.', 'webluxe-3d-viewer' ); ?></p>
        <?php endif;
    }
