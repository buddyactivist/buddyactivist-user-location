<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Custom xProfile field type: Localized Address (BuddyActivist User Location)
 */
class BAUL_XProfile_Field_Type extends BP_XProfile_Field_Type {

    public function __construct() {

        parent::__construct();

        $this->category = _x( 'BuddyActivist', 'xprofile field type category', 'buddyactivist-user-location' );
        $this->name     = __( 'Localized Address (BuddyActivist User Location)', 'buddyactivist-user-location' );

        $this->supports_options = false;
        $this->supports_richtext = false;
        $this->accepts_null_value = true;

        $this->set_format( '/.*/', 'replace' );
    }

    /**
     * Render field in profile edit / registration
     */
    public function edit_field_html( array $raw_properties = [] ) {

        $user_id = bp_displayed_user_id() ?: bp_loggedin_user_id();
        $address = '';

        if ( $user_id ) {
            $address = get_user_meta( $user_id, BAUL_XProfile_Field::META_ADDRESS, true );
        }

        $properties = bp_parse_args( $raw_properties, [
            'type'  => 'text',
            'value' => $address,
            'class' => 'baul-address-input',
            'id'    => bp_get_the_profile_field_input_name(),
            'name'  => 'baul_address',
        ] );

        ?>

        <label for="<?php echo esc_attr( $properties['id'] ); ?>">
            <?php echo esc_html( bp_get_the_profile_field_name() ); ?>
        </label>

        <input <?php echo $this->get_edit_field_html_elements( $properties ); ?> />

        <p class="description">
            <?php esc_html_e( 'Enter your full address (street, city, postal code). It will be geocoded automatically.', 'buddyactivist-user-location' ); ?>
        </p>

        <?php
    }

    /**
     * Render field in profile view
     */
    public function display_filter( $field_value, $field_id = '' ) {

        $user_id = bp_displayed_user_id();
        if ( ! $user_id ) return '';

        $address = get_user_meta( $user_id, BAUL_XProfile_Field::META_ADDRESS, true );
        if ( empty( $address ) ) return '';

        return esc_html( $address );
    }

    /**
     * Validate before saving
     */
    public function is_valid( $values ) {
        return true;
    }
}
