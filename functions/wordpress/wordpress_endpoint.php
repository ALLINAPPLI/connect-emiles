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
    
    register_rest_route('euralpha/v1', '/endpoint/data', [
      'methods'           => 'POST',
      'callback'          => 'connect_partnersite_alpha_return_json',
      'permission_callback' => '__return_true',
    ]);
    
    register_rest_route('airalpha/v1', '/endpoint/data', [
      'methods'           => 'POST',
      'callback'          => 'connect_partnersite_alpha_return_json',
      'permission_callback' => '__return_true',
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
    $parameters = $request->get_json_params();
    $url = $request->get_header('host');
    $blog_id = get_blog_id_from_url( $url );
    $userData = [
      'first_name' => $parameters['firstname'],
      'last_name'  => $parameters['lastname'],
      'user_email' => $parameters['email'],
      'user_login' => $parameters['email']
    ];
    
    $responseCustomEndpointAlpha = [];
    
    $email = $parameters['email'];
    $exists = email_exists( $email );
    if ( $exists && filter_var($email,FILTER_VALIDATE_EMAIL)) {
      $responseCustomEndpointAlpha = [
        'reponse' => 'Utilisateur present en bdd'
      ];
      
      updateDataUser($email,$parameters);
      
      redirectionToSpecificUrl();
      
    } else {
      
      $user_id = wp_insert_user( $userData );
      
      if ( ! is_wp_error( $user_id ) ) {
        $uuid = wp_generate_uuid4();
        add_user_meta( $user_id, 'secure_id', $uuid, false );
        
        // add user in blog site of multisite
        add_user_to_blog($blog_id,$user_id,'subscriber');
        
        $responseCustomEndpointAlpha = [
          'reponse' => 'Utilisateur cree en bdd ' . $user_id
        ];
        
        redirectionToSpecificUrl();
        
      } else {
        $responseCustomEndpointAlpha = [
          'reponse' => 'probleme lors de la creation en bdd de l\'utilisateur. Verifiez l\'email de l\'utilisateur !'
        ];
      }
    }
    
    return $responseCustomEndpointAlpha;
  }