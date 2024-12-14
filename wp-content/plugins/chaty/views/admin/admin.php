<?php
/**
 * Admin Chaty Settings
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}

if (get_option('cht_position') == 'custom') {
    update_option('cht_position', 'right');
}

$chtLicenseKey = get_option('cht_license_key');
$proClass      = "none_pro";
?>
<div class="sr-only">
    <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="linear-gradient" x1="0.892" y1="0.192" x2="0.128" y2="0.85" gradientUnits="objectBoundingBox">
                <stop offset="0" stop-color="#4a64d5"/>
                <stop offset="0.322" stop-color="#9737bd"/>
                <stop offset="0.636" stop-color="#f15540"/>
                <stop offset="1" stop-color="#fecc69"/>
            </linearGradient>
        </defs>
        <circle class="color-element" cx="19.5" cy="19.5" r="19.5" fill="url(#linear-gradient)"/>
        <path id="Path_1923" data-name="Path 1923" d="M13.177,0H5.022A5.028,5.028,0,0,0,0,5.022v8.155A5.028,5.028,0,0,0,5.022,18.2h8.155A5.028,5.028,0,0,0,18.2,13.177V5.022A5.028,5.028,0,0,0,13.177,0Zm3.408,13.177a3.412,3.412,0,0,1-3.408,3.408H5.022a3.411,3.411,0,0,1-3.408-3.408V5.022A3.412,3.412,0,0,1,5.022,1.615h8.155a3.412,3.412,0,0,1,3.408,3.408v8.155Z" transform="translate(10 10.4)" fill="#fff"/>
        <path id="Path_1924" data-name="Path 1924" d="M45.658,40.97a4.689,4.689,0,1,0,4.69,4.69A4.695,4.695,0,0,0,45.658,40.97Zm0,7.764a3.075,3.075,0,1,1,3.075-3.075A3.078,3.078,0,0,1,45.658,48.734Z" transform="translate(-26.558 -26.159)" fill="#fff"/>
    </svg>
</div>
<div class="container <?php echo esc_attr($proClass) ?>" dir="ltr" id="chaty-container">
    <main class="main">
        <form id="cht-form" action="options.php" method="POST" enctype="multipart/form-data">
            <?php settings_fields($this->pluginSlug); ?>
            
            <!-- ---------------------
            Header Step (choose your chat channel | customize social widget launcher | triggers and targeting)
            ---------------------- -->
            <div class="chaty-header chaty-logo z-50 flex gap-3 items-center justify-between bg-white p-1.5 fixed top-0 left-0 w-full" id="chaty-header-tab-label">
                <a href="<?php echo esc_url( $this->getDashboardUrl() ) ?>">
                    <img class="max-w-[100px]" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/logo-color.svg'); ?>" alt="Chaty" class="logo">
                    <img class="w-8 small-logo" src="<?php echo esc_url(CHT_PLUGIN_URL.'admin/assets/images/logo-small.svg'); ?>" alt="Chaty" class="logo">
                </a>

                <div class="header-items flex-1">
                    <ul class="chaty-app-tabs flex items-start justify-between">
                        <li class="m-0">
                            <a href="javascript:;" class="chaty-tab <?php echo ($step == 0) ? "active" : "completed" ?>" data-tab-id="chaty-tab-social-channel" id="chaty-social-channel" data-tab="first" data-tab-index="">
                                <span class="chaty-tabs-heading"></span>
                                <span class="lg:inline hidden chaty-tabs-subheading"><?php esc_html_e("1. Select channels", "chaty") ?></span>
                                <span class="inline lg:hidden chaty-tabs-subheading"><?php esc_html_e("1. Channels", "chaty") ?></span>
                            </a>
                        </li>
                        <li class="my-0">
                            <a href="javascript:;" class="chaty-tab <?php echo ($step == 1) ? "active" : (($step == 2) ? "completed" : "") ?>" data-tab-id="chaty-tab-customize-widget" id="chaty-app-customize-widget" data-tab-index="" data-tab="middle" data-forced-save="yes">
                                <span class="chaty-tabs-heading"></span>
                                <span class="lg:inline hidden chaty-tabs-subheading"><?php esc_html_e("2. Widget customization ", "chaty") ?></span>
                                <span class="inline lg:hidden chaty-tabs-subheading"><?php esc_html_e("2. Customization", "chaty") ?></span>
                            </a>
                        </li>
                        <li class="m-0">
                            <a href="javascript:;" class="chaty-tab <?php echo ($step == 2) ? "active" : "" ?>" data-tab-id="chaty-tab-triger-targeting" id="chaty-triger-targeting" data-tab-index="middle" data-forced-save="yes">
                                <span class="chaty-tabs-heading"></span>
                                <span class="lg:inline hidden chaty-tabs-subheading"><?php esc_html_e("3. Triggers and targeting", "chaty") ?></span>
                                <span class="inline lg:hidden chaty-tabs-subheading"><?php esc_html_e("2. Triggers & targeting", "chaty") ?></span>
                            </a>
                        </li>
                        <li class="m-0">
                            <a href="javascript:;" class="chaty-tab <?php echo ($step == 3) ? "active" : "" ?>" data-tab-id="chaty-tab-chatway" id="chaty-chatway" data-tab="last" data-tab-index="" data-forced-save="yes">
                                <span class="chaty-tabs-heading"></span>
                                <span class="lg:inline chaty-tabs-subheading"><?php esc_html_e("4. Add live chat", "chaty") ?></span>
                            </a>
                        </li>
                    </ul>
                    <div class="chaty-app-steps">
                        <div class="progress-stat">
                            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="whirlPath">
                                <circle cx="50" cy="50" r="46.5" stroke-linecap="round" fill="none" stroke-width="4.5"></circle>
                            </svg>
                            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="svg-progress">
                                <circle cx="50" cy="50" r="46.5" stroke-linecap="round" fill="none" stroke-width="4.5" id="step-progress" style="stroke-dashoffset: 0;"></circle>
                            </svg>
                            <span class="current-step" id="current-step">4/4</span>
                        </div>
                        <div class="process-step" id="process-step"> Add live chat </div>
                    </div>
                </div>

                <!-- footer start -->
                <footer class="footer-buttons relative space-x-2 step-<?php echo esc_attr($step) ?>">
                
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center gap-2 next-prev-buttons">
                            <button type="button" class="flex back-button" id="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M15.8333 10H4.16668" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 15.8333L4.16668 9.99996L10 4.16663" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e("Back", "chaty") ?></span>
                            </button>
                            <button type="button" class="flex next-button" id="next-button">
                                <span><?php esc_html_e("Next", "chaty") ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M4.16677 10H15.8334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.0001 4.16663L15.8334 9.99996L10.0001 15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <span class="save-button-container">
                            <button type="submit" class="save-button whitespace-nowrap" id="save-button" name="save_button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H13.3333L17.5 6.66667V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5Z" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.1666 17.5V10.8334H5.83331V17.5" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.83331 2.5V6.66667H12.5" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php esc_html_e("Save Widget", "chaty") ?></span>
                                <span class="mobile-text"><?php esc_html_e("Save", "chaty") ?></span>
                            </button>
                            <button class="arrow-btn !px-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        <span>
                    </div>

                    <input type="submit" class="save-dashboard-button hidden" id="save-dashboard-button" name="save_and_view_dashboard" value="<?php esc_html_e('Save & View Dashboard', 'chaty'); ?>" />
                    <input type="hidden" name="current_step" value="<?php echo esc_attr($step) ?>" id="current_step">
                </footer>
                <!-- footer ends -->
            </div>
            <!-- end of header step -->

            <!-- settings and preview start -->
            <div class="js-error-message">

            </div>

            <?php
            $chatwayStatus = apply_filters('check_for_chatway_status', 'not-installed');
            $pluginClass = ($chatwayStatus != 'not-installed' && $chatwayStatus != 'not-activated')?'chatway--active':'';
            ?>
            <section class="grid <?php echo esc_attr($pluginClass) ?> grid-cols-1 lg:grid-cols-3 rounded-lg border border-gray-150/40 bg-white chaty-widget-tab step-<?php echo esc_attr($step) ?>" id="chaty-widget-body-tab" data-step="<?php echo esc_attr($step) ?>">
                <!-- settings column start -->
                <div class="settings-column col-span-2 border-r border-gray-150/40">
                    <!--/* Social channel list section */-->
                    <div id="chaty-tab-social-channel" class="social-channel-tabs <?php echo ($step == 0) ? "active" : "" ?>">
                        <h1 class="section-title font-primary text-cht-gray-150 text-2xl border-b border-gray-150/40 px-4 sm:px-8 py-5">
                            <strong><?php esc_html_e("Step 1:", "chaty") ?></strong> <?php esc_html_e("Choose your channels", "chaty") ?>
                        </h1>
                        <div class="p-3 sm:p-5 md:p-8">
                            <?php require_once 'channels-section.php'; ?>
                        </div>
                    </div>

                    <!--/* Customize widget section */-->
                    <div id="chaty-tab-customize-widget" class="social-channel-tabs <?php echo ($step == 1) ? "active" : "" ?>">
                        <h1 class="section-title font-primary text-cht-gray-150 text-2xl border-b border-gray-150/40 px-4 sm:px-8 py-5">
                            <strong><?php esc_html_e("Step 2:", "chaty") ?></strong> <?php esc_html_e("Customize your widget", "chaty") ?>
                        </h1>
                        <div class="p-3 sm:p-5 md:p-8">
                            <?php require_once 'customize-widget-section.php'; ?>
                        </div>
                    </div>

                    <!--/* Customize widget section */-->
                    <div id="chaty-tab-triger-targeting" class="social-channel-tabs <?php echo ($step == 2) ? "active" : "" ?>">
                        <h1 class="section-title font-primary text-cht-gray-150 text-2xl border-b border-gray-150/40 px-4 sm:px-8 py-5">
                            <strong><?php esc_html_e("Step 3:", "chaty") ?></strong> <?php esc_html_e("Triggers and targeting", "chaty") ?>
                        </h1>
                        <div class="p-5 md:p-8">
                            <?php require_once 'trigger-and-target.php'; ?>
                        </div>
                    </div>

                    <!--/* Chatway */-->
                    <div id="chaty-tab-chatway" class="social-channel-tabs <?php echo ($step == 3) ? "active" : "" ?>">
                        <h1 class="section-title font-primary text-cht-gray-150 text-2xl border-b border-gray-150/40 px-4 sm:px-8 py-5">
                            <strong><?php esc_html_e("Step 4:", "chaty") ?></strong> <?php esc_html_e("Add live chat", "chaty") ?>
                        </h1>
                        <div class="p-5 md:p-8 pl-0 md:pl-0">
                            <?php require_once 'chatway.php'; ?>
                        </div>
                    </div>
                </div>


                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce("chaty_plugin_nonce")) ?>">
                <?php
                $createdOn = get_option('cht_created_on');
                if ($createdOn === false) {
                    $createdOn = "";
                }

                if (get_option('cht_active') === false) {
                    $createdOn = gmdate("Y-m-d");
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                            jQuery("#toplevel_page_chaty-app li").removeClass("current");
                            jQuery("#toplevel_page_chaty-app li:eq(2)").addClass("current")
                        });
                    </script>
                    <?php
                }
                ?>
                <input type="hidden" name="cht_created_on" value="<?php echo esc_attr($createdOn) ?>" />
                <input type="hidden" name="button_type" id="button_type"  />
                <!-- settings column end -->
                <!-- preview column start -->
                <aside class="preview-section-chaty pb-20 z-0">
                    <div class="preview sticky top-28 ease-linear duration-300 mx-auto" id="admin-preview">
                        <h2 class="text-white text-center">&nbsp;</h2>
                        <div class="page border rounded-md">
                            <div class="page-header bg-cht-gray-150/10">
                                <svg width="40" height="8" viewBox="0 0 40 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="4" cy="4" r="4" fill="#C6D7E3"></circle>
                                    <circle cx="20" cy="4" r="4" fill="#C6D7E3"></circle>
                                    <circle cx="36" cy="4" r="4" fill="#C6D7E3"></circle>
                                </svg>
                            </div>
                            <div class="page-body">
                                <div class="chaty-preview border-t rounded-bl-md rounded-br-md">
                                    <div class="chaty-preview-channels"></div>
                                    <div class="chaty-preview-cta"></div>
                                </div>
                            </div>
                        </div>
                        <div class="switch-preview">
                            <input data-gramm_editor="false" type="radio" id="previewDesktop" name="switchPreview" class="js-switch-preview switch-preview__input" checked="checked">
                            <label for="previewDesktop" class="switch-preview__label switch-preview__desktop">
                                <svg class="pointer-events-none" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1"><path d="M16.667 15c.916 0 1.666-.75 1.666-1.667V5c0-.917-.75-1.667-1.666-1.667H3.333c-.916 0-1.666.75-1.666 1.667v8.333c0 .917.75 1.667 1.666 1.667h-2.5a.836.836 0 00-.833.833c0 .459.375.834.833.834h18.334a.836.836 0 00.833-.834.836.836 0 00-.833-.833h-2.5zM4.167 5h11.666c.459 0 .834.375.834.833V12.5a.836.836 0 01-.834.833H4.167a.836.836 0 01-.834-.833V5.833c0-.458.375-.833.834-.833z" fill="currentColor"></path></svg>
                            </label>
                            <input data-gramm_editor="false" type="radio" id="previewMobile" name="switchPreview" class="js-switch-preview switch-preview__input">
                            <label for="previewMobile" class="switch-preview__label switch-preview__mobile">
                                <svg class="pointer-events-none" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" focusable="false" tabindex="-1"><path d="M12.916.833H6.25c-1.15 0-2.083.934-2.083 2.084v14.166c0 1.15.933 2.084 2.083 2.084h6.666c1.15 0 2.084-.934 2.084-2.084V2.917c0-1.15-.934-2.084-2.084-2.084zm-3.333 17.5c-.691 0-1.25-.558-1.25-1.25 0-.691.559-1.25 1.25-1.25.692 0 1.25.559 1.25 1.25 0 .692-.558 1.25-1.25 1.25zM13.333 15h-7.5V3.333h7.5V15z" fill="currentColor"></path></svg>
                            </label>
                        </div>
                        <div id="custom-css"></div>
                    </div>
                </aside>
            </section>
        </form>
    </main>
</div>

<div class="chaty-sticky-buttons">
    <!-- form sticky save button -->
    <button class="btn-save-sticky">
        <span><?php esc_html_e('Save', 'chaty'); ?></span>
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M21.5 0.5H0.5V27.5H27.5V6.5L21.5 0.5ZM14 24.5C11.51 24.5 9.5 22.49 9.5 20C9.5 17.51 11.51 15.5 14 15.5C16.49 15.5 18.5 17.51 18.5 20C18.5 22.49 16.49 24.5 14 24.5ZM18.5 9.5H3.5V3.5H18.5V9.5Z"
                fill="white" />
        </svg>
    </button>

    <!-- chaty help button -->
    <a class="btn-help"><?php esc_html_e('help', 'chaty'); ?><span>?</span></a>

    <!-- chaty preview button -->
    <a href="javascript:;" class="preview-help-btn"><?php esc_html_e('Preview', 'chaty'); ?></a>
</div>
<?php require_once 'popup.php';
include_once "review-popup.php";