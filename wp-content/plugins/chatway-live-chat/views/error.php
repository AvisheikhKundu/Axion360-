<?php
use Chatway\App\Url;
/**
 * Chatway error
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
    <div class="server-down-error">
        <div>
            <img
                src="<?php echo esc_url( Chatway::url( 'assets/images/main-logo.webp' ) ) ?>"
                width="106"
                height="123"
                loading="lazy"
            />
            <h1>An Error Occurred!</h1>
            <p>Oops! We encountered an error, please try again in a few minutes</p>
            
            <div class="button-group">
                <a href="<?php echo esc_url( Url::admin_url() ) ?>">Refresh</a>
                <a href="<?php echo esc_url( Url::contact_us() ) ?>" target="_blank">Contact Us</a>
            </div>
        </div>
    </div>
</section>