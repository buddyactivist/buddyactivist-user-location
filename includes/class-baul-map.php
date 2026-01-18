<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_Map {

    public function init() {
        add_shortcode( 'baul_user_map', [ $this, 'render_user_map' ] );
    }

    public function render_user_map( $atts ) {
        $atts = shortcode_atts([
            'role'     => '',
            'group_id' => '',
            'max'      => 500,
        ], $atts, 'baul_user_map' );

        ob_start();
        ?>
        <div class="baul-user-map-wrapper">
            <div class="baul-user-map-search">
                <input type="text" id="baul-user-map-search-input" placeholder="<?php esc_attr_e( 'Search address...', 'buddyactivist-user-location' ); ?>">
                <button id="baul-user-map-search-button"><?php esc_html_e( 'Search', 'buddyactivist-user-location' ); ?></button>
            </div>
            <div id="baul-user-map" style="width:100%;height:500px;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}
