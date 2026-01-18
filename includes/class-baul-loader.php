<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BAUL_Loader {

    protected $assets;
    protected $admin;
    protected $xprofile;
    protected $map;
    protected $autojoin;
    protected $rest;

    public function init() {

        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-assets.php';
        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-helpers.php';
        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-xprofile-field.php';
        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-map.php';
        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-rest.php';
        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-autojoin.php';
        require_once BAUL_PLUGIN_DIR . 'includes/class-baul-admin.php';

        $this->assets   = new BAUL_Assets();
        $this->xprofile = new BAUL_XProfile_Field();
        $this->map      = new BAUL_Map();
        $this->rest     = new BAUL_REST();
        $this->autojoin = new BAUL_AutoJoin();

        $this->assets->init();
        $this->xprofile->init();
        $this->map->init();
        $this->rest->init();
        $this->autojoin->init();

        if ( is_admin() ) {
            $this->admin = new BAUL_Admin();
            $this->admin->init();
        }
    }
}
