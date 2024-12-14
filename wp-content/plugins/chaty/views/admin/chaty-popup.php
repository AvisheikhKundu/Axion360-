<?php
/**
 * Contact form leads
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}
?>
<div class="chaty-popup" id="chaty-intro-popup" style="display: block">
    <div class="chaty-popup-box shadow-xl rounded-lg bg-white px-8 py-10 text-center bg-cover bg-no-repeat" style="background-image: url(<?php echo esc_url(CHT_PLUGIN_URL.'images/popup-bg.png'); ?>)">
        
        <button class="close-chaty-popup text-white bg-cht-gray-150 absolute right-2 top-2 hover:bg-slate-600" style="line-height: 1px">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                <path d="M15 5L5 15" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 5L15 15" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <a href="<?php echo esc_url( $this->getDashboardUrl() ) ?>">
            <img class="mx-auto mb-4 inline-block" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/logo-color.svg'); ?>" alt="Chaty" />
        </a>
        <h2 class="font-primary text-cht-gray-150 text-3xl mb-3"><?php esc_html_e("Welcome to Chaty", "chaty") ?> &#127881;</h2>
        <p class="font-primary text-base text-cht-gray-150 max-w-[558px] mb-5 mx-auto">
            <?php echo sprintf(esc_html__("Select chat channels that you'd like to add to your store, and fill out your info. For more info visit our %1\$s and check the video.", "chaty"), '<a class="text-primary hover:unde" target="_blank" href="https://premio.io/help/chaty/?utm_soruce=wordpresschaty">'.esc_html__("Help Center", "chaty").'</a>') ?>
        </p>

        <iframe class="font-primary text-sm text-cht-gray-150 mb-8 w-[530px] mx-auto bg-cht-primary/40 rounded-xl" height="300" src="https://www.youtube.com/embed/i6t05AeuyWg?rel=0&start=28"></iframe>

        <button class="btn rounded-md text-base shadow-lg inline-flex items-center space-x-2 shadow-cht-primary/60 font-normal font-primary" type="button">
            <span><?php esc_html_e("Go to Chaty", "chaty") ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <input type="hidden" id="chaty_update_popup_status" value="<?php echo esc_attr(wp_create_nonce("chaty_update_popup_status")) ?>">
    </div>
</div>
