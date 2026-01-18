<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_Assets {

    public function init() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin' ] );
    }

    public function enqueue_front() {
        if ( ! is_singular() ) return;

        global $post;
        if ( ! $post ) return;

        if ( has_shortcode( $post->post_content, 'baul_user_map' ) ) {

            wp_enqueue_style(
                'leaflet',
                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
                [],
                '1.9.4'
            );

            wp_enqueue_style(
                'baul-map',
                BAUL_PLUGIN_URL . 'assets/css/map.css',
                [],
                BAUL_VERSION
            );

            wp_enqueue_script(
                'leaflet',
                'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                [],
                '1.9.4',
                true
            );

            wp_enqueue_script(
                'baul-user-global-map',
                BAUL_PLUGIN_URL . 'assets/js/user-global-map.js',
                [ 'leaflet' ],
                BAUL_VERSION,
                true
            );

            wp_enqueue_script(
                'baul-user-global-search',
                BAUL_PLUGIN_URL . 'assets/js/user-global-search.js',
                [ 'baul-user-global-map' ],
                BAUL_VERSION,
                true
            );

            wp_localize_script( 'baul-user-global-map', 'BAUL_User_Map', [
                'rest_url' => esc_url_raw( rest_url( 'baul/v1/users' ) ),
                'marker_icon' => BAUL_PLUGIN_URL . 'assets/images/marker-64x64.png',
            ] );
        }
    }

    public function enqueue_admin( $hook ) {
        wp_enqueue_style(
            'baul-admin',
            BAUL_PLUGIN_URL . 'assets/css/admin.css',
            [],
            BAUL_VERSION
        );

        wp_enqueue_script(
            'baul-admin-location',
            BAUL_PLUGIN_URL . 'assets/js/admin-location.js',
            [ 'jquery' ],
            BAUL_VERSION,
            true
        );
    }
}
