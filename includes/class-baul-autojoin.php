<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_AutoJoin {

    public function init() {

        if ( ! class_exists( 'BAGL_Loader' ) ) {
            return;
        }

        if ( ! get_option( 'baul_autojoin_enabled' ) ) {
            return;
        }

        add_action( 'bp_core_activated_user', [ $this, 'maybe_autojoin_group' ], 20, 3 );
        add_action( 'xprofile_updated_profile', [ $this, 'maybe_autojoin_group_on_update' ], 20, 2 );
    }

    public function maybe_autojoin_group( $user_id, $key, $user ) {
        $this->process_autojoin( $user_id );
    }

    public function maybe_autojoin_group_on_update( $user_id, $fields ) {
        $this->process_autojoin( $user_id );
    }

    protected function process_autojoin( $user_id ) {

        $lat = get_user_meta( $user_id, BAUL_XProfile_Field::META_LAT, true );
        $lng = get_user_meta( $user_id, BAUL_XProfile_Field::META_LNG, true );

        if ( empty( $lat ) || empty( $lng ) ) return;

        if ( ! class_exists( 'BAGL_Group_Location' ) ) return;

        $groups = BAGL_Group_Location::get_all_groups_with_coordinates();
        if ( empty( $groups ) ) return;

        $nearest_id = false;
        $nearest_distance = PHP_INT_MAX;

        foreach ( $groups as $group ) {
            if ( empty( $group['lat'] ) || empty( $group['lng'] ) ) continue;

            $distance = BAUL_Helpers::haversine_distance(
                (float) $lat,
                (float) $lng,
                (float) $group['lat'],
                (float) $group['lng']
            );

            if ( $distance < $nearest_distance ) {
                $nearest_distance = $distance;
                $nearest_id = $group['id'];
            }
        }

        if ( $nearest_id && function_exists( 'groups_join_group' ) ) {
            groups_join_group( $nearest_id, $user_id );
        }
    }
}
