<?php
  
  /**
   * construction du lien de connexion sans les messages d'erreur et sans de
   * balise HTML
   *
   * @return string
   */
  function getLinkConnect()
  {
    $current_user          = wp_get_current_user();
    $logged_userid_wp      = $current_user->ID;
    $secure_id             = get_user_meta($logged_userid_wp, 'secure_id');
    $optionUrlPartenaire   = get_option('url_partenaire');
    $optionTokenPartenaire = get_option('token_partenaire');
    
    $error = [];
    $url   = '';
    
    $tabUrl = [
      'user_id'          => $logged_userid_wp,
      'secure_id'        => $secure_id,
      'url_partenaire'   => $optionUrlPartenaire,
      'token_partenaire' => $optionTokenPartenaire,
    ];
    
    foreach ($tabUrl as $key => $line) {
      if (!empty($line)) {
        $url = 'https://' . $optionUrlPartenaire . '/?token=' . $optionTokenPartenaire . '&referal_user=' . $secure_id[0];
      }
    }
    
    return $url;
  }
  
  /**
   * @return \WP_REST_Response
   * fonction permettant de paramétrer une url dynamique pour une redirection vers un site spécifique
   */
  function redirectionToSpecificUrl($message,$blog_id,$user_id)
  {
    $secure_id           = get_user_option('secure_id',$user_id);
    $optionUrlPartenaire   = get_blog_option($blog_id,'url_partenaire');
    $optionTokenPartenaire = get_blog_option($blog_id,'token_partenaire');
    
    $url = 'https://' . $optionUrlPartenaire . '/?token=' . $optionTokenPartenaire . '&referal_user=' . $secure_id;
    
    $restReponse = new WP_REST_Response(null,303,array(
      'Location' => $url, // Redirect to success page.
      'body_response' => $message
    ));
    
    return $restReponse;
  }
  
  function updateDataUser($email,$parameters) {
    $user_exist_id = get_user_by('email',$email);
    $user_exist_data = get_userdata($user_exist_id->data->ID);
    
    if($user_exist_data->first_name !== $parameters['firstname']) {
      wp_update_user([
        'ID' => $user_exist_id->data->ID,
        'first_name' => $parameters['firstname'],
      ]);
    }
    
    if($user_exist_data->last_name !== $parameters['lastname']) {
      wp_update_user([
        'ID' => $user_exist_id->data->ID,
        'last_name' => $parameters['lastname'],
      ]);
    }
    
  }