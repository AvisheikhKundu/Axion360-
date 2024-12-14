<?php
/**
 * Chatway dashboard
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

<section class="chatway-dashboard">
    <iframe
        class="chatway-dashboard-iframe"
        allow="clipboard-read; clipboard-write"
        src="<?php echo esc_url( \Chatway\App\Url::iframe_src( get_option( 'chatway_token', '' ) ) ) ?>">
    </iframe>
</section>