<?php
/**
 * Chaty Popups for widget and contact form lead
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

$showFirstChatyBox = get_option("show_first_chaty_box");
if ($showFirstChatyBox == 1) {
    update_option("show_first_chaty_box", 2);
    ?>
    <div class="first-chaty-popup">
        <div class="first-chaty-popup-overlay"></div>
        <div class="first-chaty-popup-content w-[440px]">
            <div class="first-chaty-popup-data text-center">
                <a href="#" class="close-first-popup">
                    <img src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/x.svg" alt="chaty" />
                </a>
                <div class="text-[#49687E] text-2xl font-medium pb-3"><?php esc_html_e("Congratulations! 🎉", "chaty"); ?></div>
                <div class="text-[#49687E] text-base px-8"><?php esc_html_e("Your first widget is now up and running on your website", "chaty"); ?></div>
                <div class="bg-[#fbf8fe] p-4 my-4 text-left rounded-xl">
                    <div class="text-[#B78DEB] text-base font-normal mb-4"><?php esc_html_e("Consider upgrading to Pro", "chaty"); ?></div>
                    <ul class="text-left text-[#49687E]">
                        <li class="text-sm flex items-center text-[#49687E] mb-3">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#f4edfc] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#B78DEB" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Dynamic chat pop-up", "chaty"); ?>
                        </li>
                        <li class="text-sm flex items-center text-[#49687E] mb-3">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#f4edfc] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#B78DEB" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Create Multiple widgets and show multiple agents", "chaty"); ?>
                        </li>
                        <li class="text-sm flex items-center text-[#49687E] mb-3">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#f4edfc] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#B78DEB" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Country and Page targeting", "chaty"); ?>
                        </li>
                        <li class="text-sm flex items-center text-[#49687E] mb-3">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#f4edfc] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#B78DEB" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Get Notified via email when new a lead comes in", "chaty"); ?>
                        </li>
                        <li class="text-sm flex items-center text-[#49687E]">
                            <span class="flex-none inline-flex items-center w-6 h-6 bg-[#f4edfc] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#B78DEB" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <?php esc_html_e("Custom visibility of the widget", "chaty"); ?>
                        </li>
                    </ul>
                </div>
                <?php if(isset($_GET['widget'])) { ?>
                    <a class="block rounded-md mb-2.5 text-[#B78DEB] border border-[#B78DEB] py-2 text-center text-base hover:bg-[#B78DEB] hover:text-white focus:bg-[#B78DEB] focus:text-white" href="<?php echo admin_url('admin.php?page=chaty-app') ?>"><?php esc_html_e('Back to Dashboard', 'chaty') ?></a>
                <?php } else { ?>
                    <a class="block rounded-md mb-2.5 text-[#B78DEB] border border-[#B78DEB] py-2 text-center text-base hover:bg-[#B78DEB] hover:text-white focus:bg-[#B78DEB] focus:text-white" href="<?php echo admin_url('admin.php?page=chaty-app') ?>"><?php esc_html_e('Close', 'chaty') ?></a>
                <?php } ?>
                <a class="chaty-primary-btn flex rounded-md items-center justify-center text-base text-white py-2 bg-[#B78DEB] hover:bg-[#8f59d3] hover:text-white focus:text-white" href="<?php echo esc_url(admin_url("admin.php?page=chaty-app-upgrade")) ?>">
                    <?php esc_html_e("Upgrade to Pro", "chaty"); ?>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 12L10 8L6 4" stroke="white" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="view-pro-bottom text-center mt-3">
                    <span class="block text-[#83A1B7] text-xs mb-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e("Cancel anytime. No strings attached", "chaty"); ?></span>
                    <span class="block text-[#83A1B7] text-xs"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e("30 days refund", "chaty"); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    $showFirstChatyLeadBox = get_option("show_first_chaty_lead_box");
    if ($showFirstChatyLeadBox == 1) {
        update_option("show_first_chaty_lead_box", 2);
        ?>
        <div class="first-chaty-popup">
            <div class="first-chaty-popup-overlay"></div>
            <div class="first-chaty-popup-content chaty-lead">
                <div class="first-chaty-popup-data">
                    <a href="#" class="close-first-popup">
                        <img src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/x.svg" alt="chaty" />
                    </a>
                    <a href="<?php echo esc_url( $this->getDashboardUrl() ) ?>">
                        <img class="mx-auto" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/logo-color.svg" alt="chaty" />
                    </a>
                    <div class="first-title"><?php esc_html_e("Congratulations", "chaty"); ?> 👏</div>
                    <div class="first-des p-50">
                        <p><?php echo sprintf(esc_html__("You just got your first lead from Chaty. Click on the %1\$s button to display your contact form leads", "chaty"), "<b>".esc_html__("Show me", "chaty")."</b>") ?></p>
                        <p><?php echo sprintf(esc_html__("%1\$s to get leads to your email along with advanced triggers & targeting & more cool features", "chaty"), "<b>".esc_html__("Upgrade to Chaty Pro 🚀", "chaty")."</b>") ?></p>
                    </div>
                    <div class="show-lead-btn">
                        <a href="<?php echo esc_url(admin_url("admin.php?page=chaty-contact-form-feed")) ?>" class=""><?php esc_html_e("Show me the new lead", "chaty") ?></a><span class="dashicons dashicons-arrow-right"></span>
                    </div>
                    <div class="first-button lead-btn">
                        <a target="_blank" href="<?php echo esc_url(admin_url("admin.php?page=chaty-app-upgrade")) ?>"><?php esc_html_e("Upgrade to Pro", "chaty"); ?><span>🚀</span></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }//end if
}//end if
?>
<script>
    jQuery(document).ready(function(){
        jQuery(document).on("click", ".close-first-popup", function(e){
            e.preventDefault();
            jQuery(this).closest(".first-chaty-popup").hide();
        });
        jQuery(document).on("click", ".first-chaty-popup-overlay", function(e){
            jQuery(".first-chaty-popup").hide();
        });
        jQuery(document).on("click", ".first-chaty-popup-content", function(e){
            e.stopPropagation();
        });
    })
</script>
