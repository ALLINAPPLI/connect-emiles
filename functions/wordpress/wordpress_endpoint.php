<?php
  
  add_action('rest_api_init', function () {
    register_rest_route('emiles/v1', '/endpoint/data', [
        'methods'           => 'GET',
        'callback'          => 'connect_partnersite_return_json',
        /*'permission_callback' => function( \WP_REST_Request $request ) {
            if($request->get_header('host') !== get_option('url_partenaire')) {
                return new \WP_Error(
                  'url_partenaire_invalid',
                  'l\'url partenaire ne corresponds pas au paramétrage du plugin',
                  array( 'status' => 403 )
                );
                
            }
            return true;
        },*/ 'args' => [
          'token' => [
            /*'validate_callback' => function ( $value, \WP_REST_Request $request, $key ) {
                if ( ! wp_is_uuid( $value ) ) {
                    return new \WP_Error(
                      'uuid_invalid',
                      'l\'uuid de l\'utilisateur est invalide',
                      array( 'status' => 400 )
                    );
                }
        
                return true;
            },*/ 'sanitize_callback' => function ($value) {
              return trim($value);
            },
          ],
        ],
      ]);
  
    register_rest_route('emiles/v1', '/endpoint/alpha', [
      'methods'           => 'POST',
      'callback'          => 'connect_partnersite_alpha_return_json',
      'permission_callback' => function( \WP_REST_Request $request ) {
        //var_dump($request->get_header('x_forwarded_for'));
        if($request->get_header('x_forwarded_for') !== '::1') {
          return new \WP_Error(
            'ip_invalide',
            'l\'adresse ip est invalide !',
            array( 'status' => 403 )
          );
        
        }
        return true;
      },
      'args' => [
      
      ],
    ]);
  });
  
  function connect_partnersite_return_json($request)
  {
    $uuidUser = $request['token'];
    if (!empty($uuidUser) && !wp_is_uuid($uuidUser)) {
      return ['error' => 'l\'uuid de l\'utilisateur est invalide'];
    }
    
    if (!empty($uuidUser)) {
      $userQuery = get_users([
        'meta_key'   => 'secure_id',
        'meta_value' => $uuidUser,
      ]);
      
      $userData = new WP_User($userQuery[0]->data->ID);
      
      $responseCustomEndpoint = [
        'firstname' => $userData->first_name,
        'lastname'  => $userData->last_name,
        'email'     => $userData->user_email,
      ];
    } else {
      //return new WP_Error( 'empty_token', esc_html__( 'Token vide' ), array( 'status' => 403 ) );
      return ['error' => 'Token vide'];
    }
    
    return $responseCustomEndpoint;
  }
  
  function connect_partnersite_alpha_return_json($request) {
    //var_dump($request);
    $responseCustomEndpointAlpha = [
      'reponse' => 'OK'
    ];
    return $responseCustomEndpointAlpha;
  }