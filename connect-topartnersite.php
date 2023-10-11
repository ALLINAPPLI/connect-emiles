<?php
    /**
     * Plugin Name:     Connexion EMILE'S
     * Plugin URI:      https://www.all-in-appli.com/
     * Description:     Plugin permettant d'ajouter un bouton de connexion à un site partenaire via un shortcode
     * Author:          ALL IN APPLI / Dewy Mercerais
     * Author URI:      https://dewy.fr/
     * Text Domain:     connect-emiles
     * Domain Path:     /languages
     * Version:         1.0
     *
     * @package         connect-emiles
     */
    
    /** Secure */
    defined( 'ABSPATH' ) || exit;
    
    /**
     * constantes
     */
    define( 'CONNECT_TOPARTNERSITE_WP_URL', plugin_dir_url ( __FILE__ ) );
    define( 'CONNECT_TOPARTNERSITE_WP_DIR', plugin_dir_path( __FILE__ ) );
    define( 'CONNECT_TOPARTNERSITE_WP_BASENAME', plugin_basename( __FILE__ ) );
    define( 'CONNECT_TOPARTNERSITE_WP_VERSION', '1.0' );
    
    /**
     *  Activation / Deactivation hook
     */
    function create_usermeta_activate_plugin() {
        $list_users = get_users( array( 'fields' => array( 'all' )));
        foreach ($list_users as $user) {
            $uuid = wp_generate_uuid4();
            add_user_meta( $user->ID, 'secure_id', $uuid, false );
        }
        
        add_option('ndd_partenaire');
        add_option('token_partenaire');
    }
    register_activation_hook( __FILE__, 'create_usermeta_activate_plugin' );
    
    function delete_usermeta_deactivate() {
        $list_users = get_users( array( 'fields' => array( 'all' )));
        foreach ($list_users as $user) {
            delete_user_meta($user->ID,'secure_id');
        }
    
        delete_option('ndd_partenaire');
        delete_option('token_partenaire');
    }
    register_deactivation_hook( __FILE__, 'delete_usermeta_deactivate' );
    
    require_once CONNECT_TOPARTNERSITE_WP_DIR . 'functions/wordpress/wordpress_hooks.php';
    require_once CONNECT_TOPARTNERSITE_WP_DIR . 'functions/wordpress/wordpress_filters.php';
    require_once CONNECT_TOPARTNERSITE_WP_DIR . 'functions/wordpress/wordpress_shortcodes.php';
    require_once CONNECT_TOPARTNERSITE_WP_DIR . 'functions/utils.php';
