<?php
/**
 * Form markup
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'chatway' ) );
}
?>
<!-- generate markup using @wordpress/element -->
<div id="chatway-wp-plugin-root"></div>