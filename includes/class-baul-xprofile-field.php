<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handles the BuddyPress xProfile field logic for BuddyActivist User Location.
 * Registers the field type and processes geocoding + meta saving.
 */
class BAUL_XProfile_Field {

    const META_ADDRESS  = '_baul_address';
    const META_LAT      = '_baul_lat';
    const META_LNG      = '_baul_lng';
    const META_CITY     = '_baul_city';
    const META_POSTCODE = '_baul_postcode';
    const META_COUNTRY  = '_baul_country';

    public function init() {

        // Register custom field type
        add_filter( 'bp_xprofile_get_field_types', [ $this, 'register_field_type' ] );

        // Save location when profile is updated
        add_action( 'xprofile_updated_profile', [ $this, 'save_profile_location' ], 10, 2 );

        // Save location during registration (BuddyPress)
        add_action( 'bp_core_signup_user', [ $this, 'save_location_on_registration' ], 10, 4 );
    }

    /**
     * Register the custom xProfile field type
     */
    public function register_field_type( $types ) {

        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-xprofile-field-type.php';

        $types['baul_localized_address'] = 'BAUL_XProfile_Field_Type';
        return $types;
    }

    /**
     * Save location during BuddyPress registration
     */
    public function save_location_on_registration( $user_id, $user_login, $user_password, $user_email ) {

        if ( empty( $_POST['baul_address'] ) ) {
            return;
        }

        $address = sanitize_text_field( wp_unslash( $_POST['baul_address'] ) );
        $this->process_geocoding_and_save( $user_id, $address );
    }

    /**
     * Save location when user updates profile
     */
    public function save_profile_location( $user_id, $fields ) {

        if ( empty( $_POST['baul_address'] ) ) {
            return;
        }

        $address = sanitize_text_field( wp_unslash( $_POST['baul_address'] ) );
        $this->process_geocoding_and_save( $user_id, $address );
    }

    /**
     * Geocode address and save all location meta
     */
    protected function process_geocoding_and_save( $user_id, $address ) {

        $geo = $this->geocode( $address );
        if ( ! $geo ) {
            return;
        }

        update_user_meta( $user_id, self::META_ADDRESS,  $address );
        update_user_meta( $user_id, self::META_LAT,      $geo['lat'] );
        update_user_meta( $user_id, self::META_LNG,      $geo['lng'] );
        update_user_meta( $user_id, self::META_CITY,     $geo['city'] );
        update_user_meta( $user_id, self::META_POSTCODE, $geo['postcode'] );
        update_user_meta( $user_id, self::META_COUNTRY,  $geo['country'] );
    }

    /**
     * Geocode using Nominatim (OpenStreetMap)
     */
    protected function geocode( $address ) {

        $url = add_query_arg([
            'q'      => rawurlencode( $address ),
            'format' => 'json',
            'limit'  => 1,
            'addressdetails' => 1,
        ], 'https://nominatim.openstreetmap.org/search' );

        $response = wp_remote_get( $url, [
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'BuddyActivist-User-Location/1.0'
            ]
        ] );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( empty( $data[0] ) ) {
            return false;
        }

        $item = $data[0];
        $addr = isset( $item['address'] ) ? $item['address'] : [];

        return [
            'lat'      => isset( $item['lat'] ) ? (float) $item['lat'] : null,
            'lng'      => isset( $item['lon'] ) ? (float) $item['lon'] : null,
            'city'     => $addr['city'] ?? $addr['town'] ?? $
