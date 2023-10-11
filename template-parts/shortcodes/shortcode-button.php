<?php
    $current_user = wp_get_current_user();
    $logged_userid_wp = $current_user->ID;
    $secure_id = get_user_meta( $logged_userid_wp, 'secure_id');
    $optionUrlPartenaire = get_option('url_partenaire');
    $optionTokenPartenaire = get_option('token_partenaire');
    
    $error = [];
    $url = '';
    
    $tabUrl = [
        'user_id' => '',
        'secure_id' => $secure_id,
        'url_partenaire' => '',
        'token_partenaire' => ''
    ];
    
    foreach ($tabUrl as $key => $line) {
        if (!empty($line)) {
            $url = 'https://' . $optionUrlPartenaire . '/?token=' . $optionTokenPartenaire . '&referal_user=' . $secure_id[0];
        } else {
            $error[] = $key;
        }
    }
?>

<?php if(!empty($error)) : ?>
    <div class="error-msg">
        Erreur : nous pouvons pas vous connecter au site partenaire. Des erreurs empêchent la connexion :
        <?php echo implode(',',$error); ?> manquant(s).
    </div>
<?php endif; ?>

<a href="<?php if(!empty($error)){echo '#';}else{echo $url;} ?>" class="btn-connexion">Connexion</a>
