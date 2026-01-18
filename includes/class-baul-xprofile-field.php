<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_XProfile_Field {

    const META_ADDRESS = '_baul_address';
    const META_LAT     = '_baul_lat';
    const META_LNG     = '_baul_lng';
    const META_CITY    = '_baul_city';
    const META_POSTCODE= '_baul_postcode';
    const META_COUNTRY = '_baul_country';

    public function init() {
        add_filter( 'bp_xprofile_get_field_types', [ $this, 'register_field_type' ] );
        add_action( 'bp_xprofile_field_type_updated', [ $this, 'save_field_data' ], 10, 2 );
        add_action( 'xprofile_updated_profile', [ $this, 'save_profile_location' ], 10, 2 );
    }

    public function register_field_type( $types ) {
        $types['baul_localized_address'] = 'BAUL_XProfile_Field_Type';
        return $types;
    }

    public function save_field_data( $field_id, $field_type ) {
        // Placeholder: nothing special here, main logic is on profile update.
    }

    public function save_profile_location( $user_id, $fields ) {

        if ( empty( $_POST['baul_address'] ) ) {
            return;
        }

        $address = sanitize_text_field( wp_unslash( $_POST['baul_address'] ) );

        $geo = $this->geocode( $address );
        if ( ! $geo ) return;

        update_user_meta( $user_id, self::META_ADDRESS,  $address );
        update_user_meta( $user_id, self::META_LAT,      $geo['lat'] );
        update_user_meta( $user_id, self::META_LNG,      $geo['lng'] );
        update_user_meta( $user_id, self::META_CITY,     $geo['city'] );
        update_user_meta( $user_id, self::META_POSTCODE, $geo['postcode'] );
        update_user_meta( $user_id, self::META_COUNTRY,  $geo['country'] );
    }

    protected function geocode( $address ) {

        $url = add_query_arg([
            'q'      => rawurlencode( $address ),
            'format' => 'json',
            'limit'  => 1,
        ], 'https://nominatim.openstreetmap.org/search' );

        $response = wp_remote_get( $url, [
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'BuddyActivist-User-Location/1.0'
            ]
        ] );

        if ( is_wp_error( $response ) ) return false;

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( empty( $data[0] ) ) return false;

        $item = $data[0];

        return [
            'lat'      => isset( $item['lat'] ) ? (float) $item['lat'] : null,
            'lng'      => isset( $item['lon'] ) ? (float) $item['lon'] : null,
            'city'     => $item['display_name'] ?? '',
            'postcode' => '',
            'country'  => '',
        ];
    }
}
