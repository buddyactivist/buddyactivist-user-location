<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_Admin {

    public function init() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_menu() {
        add_options_page(
            __( 'BuddyActivist User Location', 'buddyactivist-user-location' ),
            __( 'BuddyActivist User Location', 'buddyactivist-user-location' ),
            'manage_options',
            'baul-settings',
            [ $this, 'render_page' ]
        );
    }

    public function register_settings() {

        register_setting( 'baul_settings_group', 'baul_autojoin_enabled' );

        add_settings_section(
            'baul_main_section',
            __( 'User Location Settings', 'buddyactivist-user-location' ),
            '__return_false',
            'baul_settings'
        );

        if ( class_exists( 'BAGL_Loader' ) ) {
            add_settings_field(
                'baul_autojoin_enabled',
                __( 'Auto-join nearest group', 'buddyactivist-user-location' ),
                [ $this, 'render_autojoin_field' ],
                'baul_settings',
                'baul_main_section'
            );
        }
    }

    public function render_autojoin_field() {
        $value = get_option( 'baul_autojoin_enabled', 0 );
        ?>
        <label>
            <input type="checkbox" name="baul_autojoin_enabled" value="1" <?php checked( $value, 1 ); ?>>
            <?php esc_html_e( 'Automatically subscribe user to nearest BuddyPress group', 'buddyactivist-user-location' ); ?>
        </label>
        <?php if ( ! class_exists( 'BAGL_Loader' ) ) : ?>
            <p class="description">
                <?php esc_html_e( 'BuddyActivist Group Location is required for this feature.', 'buddyactivist-user-location' ); ?>
            </p>
        <?php endif;
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'BuddyActivist User Location', 'buddyactivist-user-location' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'baul_settings_group' );
                do_settings_sections( 'baul_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
