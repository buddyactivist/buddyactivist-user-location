<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_REST {

    public function init() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes() {
        register_rest_route( 'baul/v1', '/users', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_users' ],
            'permission_callback' => '__return_true',
        ] );
    }

    public function get_users( WP_REST_Request $request ) {

        $args = [
            'number' => 500,
            'meta_query' => [
                [
                    'key'     => BAUL_XProfile_Field::META_LAT,
                    'compare' => 'EXISTS',
                ],
                [
                    'key'     => BAUL_XProfile_Field::META_LNG,
                    'compare' => 'EXISTS',
                ],
            ],
            'fields' => [ 'ID', 'display_name' ],
        ];

        $users = get_users( $args );
        $data  = [];

        foreach ( $users as $user ) {

            $lat = get_user_meta( $user->ID, BAUL_XProfile_Field::META_LAT, true );
            $lng = get_user_meta( $user->ID, BAUL_XProfile_Field::META_LNG, true );

            if ( ! $lat || ! $lng ) continue;

            $data[] = [
                'id'          => $user->ID,
                'name'        => $user->display_name,
                'lat'         => (float) $lat,
                'lng'         => (float) $lng,
                'avatar'      => get_avatar_url( $user->ID ),
                'profile_url' => function_exists( 'bp_core_get_user_domain' )
                    ? bp_core_get_user_domain( $user->ID )
                    : get_author_posts_url( $user->ID ),
            ];
        }

        return rest_ensure_response( $data );
    }
}
