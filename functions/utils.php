<?php
    
    /**
     * construction du lien de connexion sans les messages d'erreur et sans de balise HTML
     * @return string
     */
    function getLinkConnect() {
    $current_user = wp_get_current_user();
    $logged_userid_wp = $current_user->ID;
    $secure_id = get_user_meta( $logged_userid_wp, 'secure_id');
    $optionUrlPartenaire = get_option('url_partenaire');
    $optionTokenPartenaire = get_option('token_partenaire');
    
    $error = [];
    $url = '';
    
    $tabUrl = [
      'user_id' => $logged_userid_wp,
      'secure_id' => $secure_id,
      'url_partenaire' => $optionUrlPartenaire,
      'token_partenaire' => $optionTokenPartenaire
    ];
    
    foreach ($tabUrl as $key => $line) {
        if (!empty($line)) {
            $url = 'https://' . $optionUrlPartenaire . '/?token=' . $optionTokenPartenaire . '&referal_user=' . $secure_id[0];
        }
    }
    
    return $url;
}