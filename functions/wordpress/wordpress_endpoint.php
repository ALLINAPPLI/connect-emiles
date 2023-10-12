<?php
add_action('rest_api_init', function(){
   register_rest_route(
     'connect-topartnersite/v1',
     '/connect-partnersite/data',
     array(
        'methods' => 'GET',
        'callback' => 'connect_partnersite_return_json',
        'permission_callback' => '__return_true',
     )
   );
});

function connect_partnersite_return_json( $request ) {
    $uuidUser = $request['token'];
    $userQuery = get_users(array(
      'meta_key' => 'secure_id',
      'meta_value' => $uuidUser
    ));
    
    $userData = new WP_User( $userQuery[0]->data->ID );
    
    $dataUserArray = [
        'nickname' => $userData->nickname,
        'first_name' => $userData->first_name,
        'last_name' => $userData->last_name,
        'uuid' => $uuidUser
    ];
    
    return $dataUserArray;
}