<?php
add_action('rest_api_init', function(){
   register_rest_route(
     'connect-topartnersite/v1',
     '/connect-partnersite/token=(?P<token>[a-zA-Z0-9-]+)',
     array(
        'methods' => 'GET',
        'callback' => 'connect_partnersite_return_json',
        'permission_callback' => '__return_true',
     )
   );
});

function connect_partnersite_return_json( $request ) {
    $uuidUser = $request['token'];
    $user = get_users(array(
      'meta_key' => 'secure_id',
      'meta_value' => $uuidUser
    ));
    return $user;
}