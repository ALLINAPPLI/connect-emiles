<?php
    
    if(!empty($_POST)) {
        $urlPartenaire = $_POST['url_partenaire'];
        $tokenPartenaire = $_POST['token_partenaire'];
        update_option('url_partenaire',$urlPartenaire);
        update_option('token_partenaire',$tokenPartenaire);
    }
    
    // endpoint
    $endpoint = get_rest_url() . 'emiles/v1/endpoint/data?token=[identifiant_utilisateur]';
?>
<div class="wrap">
    <h1>Options de connexion site partenaire</h1>
    
    <?php if (isset($_POST) && !empty($_POST)) : ?>
        <div class="notice notice-success">
            <p>Les options sont correctement enregistrées !!</p>
        </div>
    <?php endif; ?>
    
    <form action="#" method="POST">
        
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row"><label for="url">Url partenaire</label></th>
                <td>
                    <p><em>Inscrivez le nom de domaine du partenaire. Exemple : monsite.com</em></p>
                    <input name="url_partenaire" type="text" id="url_partenaire" value="<?php echo get_option('url_partenaire') ? get_option('url_partenaire') : null; ?>" class="regular-text" required>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="token">Token partenaire</label></th>
                <td>
                    <p><em>Inscrivez le token du site partenaire</em></p>
                    <input name="token_partenaire" type="text" id="token_partenaire" value="<?php echo get_option('token_partenaire') ? get_option('token_partenaire') : null; ?>" class="regular-text" required>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="token">Endpoint</label></th>
                <td>
                    <p><em>Ci-dessous le endpoint du plugin</em></p>
                    <p><strong><?php echo $endpoint; ?></strong></p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit" style="margin-top: 0; padding-top: 0;"><input type="submit" class="button button-primary" value="Valider"></p>
    </form>
    
</div>

