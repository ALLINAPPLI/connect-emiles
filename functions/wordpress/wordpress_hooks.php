<?php
    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style('connect-topartnersite-styles', CONNECT_TOPARTNERSITE_WP_URL . 'assets/css/styles.css');
    });
    
    add_action('admin_menu', function () {
        add_submenu_page(
          'tools.php',
          'Connexion Site partenaire',
          'Connexion Site partenaire',
          'manage_options',
          'connect-topartnersite-options',
          'callback_generate_option_page'
        );
    });
    
    function callback_generate_option_page() {
        require_once CONNECT_TOPARTNERSITE_WP_DIR . 'template-parts/admin/connect-topartnersite-options.php';
    }
    
    /**
     * Add new fields above 'Update' button.
     *
     * @param WP_User $user User object.
     */
    function tm_additional_profile_fields( $user ) {
        $secure_id_profile = get_user_meta($user->ID,'secure_id');
        ?>
        <h3>Identifiant sécurisé</h3>
        
        <table class="form-table">
            <tr>
                <th><label for="birth-date-day">Identifiant</label></th>
                <td>
                    <input type="text" value="<?php echo $secure_id_profile[0]; ?>" size="55" readonly>
                </td>
            </tr>
        </table>
        <?php
    }
    
    add_action( 'show_user_profile', 'tm_additional_profile_fields' );
    add_action( 'edit_user_profile', 'tm_additional_profile_fields' );
    
    /**
     * Add uuid user meta when user created
     * @param $user_id
     */
    add_action( 'user_register', function($user_id){
        $uuid = wp_generate_uuid4();
        add_user_meta( $user_id, 'secure_id', $uuid, false );
    }, 10, 1 );