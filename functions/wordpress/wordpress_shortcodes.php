<?php
    add_shortcode('connect-button', function () {
        ob_start();
        require_once CONNECT_TOPARTNERSITE_WP_DIR . 'template-parts/shortcodes/shortcode-button.php';
        return ob_get_clean();
    });
    
    add_shortcode('link-connect', function () {
        $link = getLinkConnect();
        return $link;
    });
