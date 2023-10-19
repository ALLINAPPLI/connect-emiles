<?php
add_action('rest_api_init', function(){
   register_rest_route(
     'emiles/v1',
     '/endpoint/data',
     array(
        'methods' => 'GET',
        'callback' => 'connect_partnersite_return_json',
        'permission_callback' => function( \WP_REST_Request $request ) {
            if($request->get_header('host') !== get_option('url_partenaire')) {
                return new \WP_Error( 'url_partenaire_invalid', 'l\'url partenaire ne corresponds pas au paramétrage du plugin', array( 'status' => 403 ) );
            }
            return true;
        },
        'args' => [
          'token' => [
            'validate_callback' => function ( $value, \WP_REST_Request $request, $key ) {
                if ( ! wp_is_uuid( $value ) ) {
                    return new \WP_Error( 'uuid_invalid', 'l\'uuid de l\'utilisateur est invalide', array( 'status' => 400 ) );
                }
        
                return true;
            },
            'sanitize_callback' => function( $value ) {
                return trim( $value );
            },
          ]
        ],
     )
   );
});

function connect_partnersite_return_json( $request ) {
    $uuidUser = $request['token'];
    if(!empty($uuidUser)) {
        $userQuery = get_users(array(
          'meta_key' => 'secure_id',
          'meta_value' => $uuidUser
        ));
    
        $userData = new WP_User( $userQuery[0]->data->ID );
    
        $responseCustomEndpoint = [
          'firstname' => $userData->first_name,
          'lastname' => $userData->last_name,
          'email' => $userData->user_email,
        ];
    } else {
        return new WP_Error( 'empty_token', esc_html__( 'Token vide' ), array( 'status' => 403 ) );
    }
    
    return new \WP_REST_Response(
      [
        'code' => 'success',
        'message' => 'Success',
        'data' => $responseCustomEndpoint
        ],
      200
    );
}