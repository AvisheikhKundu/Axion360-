<?php
/**
 * Chaty Popups
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

if (defined('ABSPATH') === false) {
    exit;
}
?>
<div class="chaty-popup" id="custom-message-popup">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header font-medium font-primary">
                    <?php esc_html_e("No channel was selected", 'chaty'); ?>
                </div>
                <div class="chaty-popup-body">
                    <?php esc_html_e("Please select at least one chat channel before publishing your widget", 'chaty'); ?>
                </div>
                <input type="hidden" id="delete_widget_id" value="">
                <div class="chaty-popup-footer">
                    <button type="button" class="btn btn-default check-for-numbers"><?php esc_html_e("Save Anyway", 'chaty'); ?></button>
                    <button type="button" class="close-chaty-popup-btn channel-setting-btn btn btn-primary"><?php esc_html_e("Change Number", 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="chaty-popup" id="no-device-popup">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header text-cht-gray-150 py-4 font-medium text-left px-5">
                    <?php esc_html_e("No channel was selected", 'chaty'); ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("Please select at least one chat channel before publishing your widget", 'chaty'); ?>
                </div>
                <input type="hidden" id="delete_widget_id" value="">
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="close-chaty-popup-btn channel-setting-btn btn btn-primary rounded-lg mr-5"><?php esc_html_e("Select Channel", 'chaty'); ?></button>
                    <button type="button" class="btn btn-default check-for-triggers btn btn-primary btn rounded-lg btn-primary bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150"><?php esc_html_e("Save Anyway", 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="chaty-popup" id="no-device-value">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header text-cht-gray-150 font-medium py-4 text-left px-5">
                    <?php esc_html_e("Fill out at least one channel details", 'chaty'); ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("You need to fill out at least one channel details for Chaty to show up on your website", 'chaty'); ?>
                </div>
                <input type="hidden" id="delete_widget_id" value="">
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="btn rounded-lg btn-default check-for-triggers  bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150 mr-5"><?php esc_html_e("Save Anyway", 'chaty'); ?></button>
                    <button type="button" class="close-chaty-popup-btn channel-setting-btn btn rounded-lg btn-primary"><?php esc_html_e("Fill channel details", 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="chaty-popup" id="device-popup">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header text-cht-gray-150 font-medium py-4 text-left px-5">
                    <?php esc_html_e("No device was selected", 'chaty'); ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("Please select mobile/desktop before publishing your widget", 'chaty'); ?>
                </div>
                <input type="hidden" id="delete_widget_id" value="">
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="btn btn-default check-for-triggers rounded-lg bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150 mr-5"><?php esc_html_e("Save Anyway", 'chaty'); ?></button>
                    <button type="button" class="close-chaty-popup-btn channel-setting-btn btn rounded-lg btn-primary"><?php esc_html_e("Select Device", 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="chaty-popup" id="trigger-popup">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"></path></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header font-medium text-cht-gray-150 py-4 text-left px-5">
                    <?php esc_html_e("No trigger was selected", 'chaty'); ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("Please select a trigger before publishing your widget", 'chaty'); ?>
                </div>
                <input type="hidden" id="delete_widget_id" value="">
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="btn-default check-for-status btn rounded-lg btn-primary bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150 mr-5"><?php esc_html_e("Save Anyway", 'chaty'); ?></button>
                    <button type="button" class="close-chaty-popup-btn select-trigger-btn btn btn-primary rounded-lg"><?php esc_html_e("Select Trigger", 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="chaty-popup" id="status-popup">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header font-medium text-cht-gray-150 py-4 text-left px-5">
                    <?php esc_html_e("Chaty is currently off", 'chaty'); ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("Chaty is currently turned off, would you like to save and show it on your site?", 'chaty'); ?>
                </div>
                <input type="hidden" id="delete_widget_id" value="">
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="btn-default status-and-save btn-primary btn rounded-lg bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150 mr-5"><?php esc_html_e("Just save and keep it off", 'chaty'); ?></button>
                    <button type="button" class="btn-primary change-status-btn change-status-and-save btn rounded-lg"><?php esc_html_e("Save & Show on my site", 'chaty'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="first-chaty-popup" id="agent-popup">
    <div class="first-chaty-popup-overlay"></div>
    <div class="upgrade-to-premium bg-white relative overflow-hidden">
        <div class="first-chaty-popup-data">
            <a href="#" class="close-first-popup close-popup">
                <img src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/x.svg" alt="chaty" />
            </a>
            <img class="mx-auto max-w-[200px]" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/agent-list.png" alt="chaty" />
            <div class="text-[#49687E] mb-4 mt-2 font-primary text-2xl sm:text-3xl"><?php esc_html_e("👑 Multiple Agents is a Premium Feature", "chaty"); ?></div>
            <div class="text-base text-center font-normal font-primary max-w-[452px] mx-auto text-[#49687E] p-25">
                Show <b class="font-medium">multiple agents</b> under a single channel. <b class="font-medium">For example</b>, allow visitors to reach for pre-sales info or support with different channels on WhatsApp or any other channel.
            </div>
            <div class="mt-10 relative z-10">
                <a class="text-white border border-cht-primary bg-cht-primary focus:text-white hover:bg-[#9455e1] ease-linear duration-200 hover:text-white px-10 py-2.5 inline-flex items-center space-x-3 rounded-lg mx-auto text-base font-primary drop-shadow-3xl" target="_blank" href="<?php echo esc_url(admin_url("admin.php?page=chaty-app-upgrade")) ?>">
                    <?php esc_html_e("Upgrade to Pro", "chaty"); ?>
                    <svg width="17" height="16" viewBox="0 0 17 16" fill="none">
                        <path d="M6.5 12L10.5 8L6.5 4" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <img class="absolute z-0 left-0 bottom-0" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/particle.png" alt="chaty" />
            <img class="absolute z-0 right-0 top-[30%] drop-shadow-xl" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/particle2.png" alt="chaty" />
            <img class="absolute z-0 left-5 drop-shadow-xl top-8" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/particle3.png" alt="chaty" />
        </div>
    </div>
</div>


<div class="chaty-popup" id="chat-view-popup" >
    <div class="chat-view-popup-overlay"></div>
    <div class="chat-view-popup-content">
        <div class="chat-view-popup-data">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"></path></svg>
                </a>
            </div>
            <div class="chat-view-data">
                <div class="chat-view-data-left">
                    <div class="chat-view-content">
                        <img class="chaty-logo" alt="<?php esc_html_e("Chaty logo", "chaty"); ?>" src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/logo-color.svg">
                        <div class="view-pro-title"><?php esc_html_e("Upgrade to Pro", "chaty"); ?> 🎉</div>
                        <ul class="text-left text-[#49687E] mt-8">
                            <li class="text-base flex text-[#49687E] mb-4">
                                <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                                <?php esc_html_e("Dynamic chat pop-up", "chaty"); ?>
                            </li>
                            <li class="text-base flex text-[#49687E] mb-4">
                                <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                                <?php esc_html_e("Create Multiple widgets and show multiple agents", "chaty"); ?>
                            </li>
                            <li class="text-base flex text-[#49687E] mb-4">
                                <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                                <?php esc_html_e("Country and Page targeting", "chaty"); ?>
                            </li>
                            <li class="text-base flex text-[#49687E] mb-4">
                                <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                                <?php esc_html_e("Get Notified via email when new a lead comes in", "chaty"); ?>
                            </li>
                            <li class="text-base flex text-[#49687E] mb-4">
                                <span class="flex-none inline-flex items-center w-6 h-6 bg-[#e4fff5] mr-2 rounded-full text-center"><svg class="mx-auto" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" svg-inline="" role="presentation" focusable="false" tabindex="-1"><path d="M13.333 4l-7.334 7.333L2.666 8" stroke="#68CB9B" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                                <?php esc_html_e("Custom visibility of the widget", "chaty"); ?>
                            </li>
                        </ul>
                        <div class="view-pro-btn mt-12">
                            <a class="flex rounded-md text-base text-white py-3 bg-[#B78DEB] hover:bg-[#8f59d3] hover:text-white" target="_blank" href="<?php echo esc_url(admin_url("admin.php?page=chaty-app-upgrade")) ?>">
                                <?php esc_html_e("Upgrade to Pro", "chaty"); ?>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 12L10 8L6 4" stroke="white" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                        <div class="view-pro-bottom text-center">
                            <span class="block text-[#83A1B7] text-xs mb-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e("Cancel anytime. No strings attached", "chaty"); ?></span>
                            <span class="block text-[#83A1B7] text-xs"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e("30 days refund", "chaty"); ?></span>
                        </div>
                    </div>
                </div>
                <div class="chat-view-data-right relative flex">
                    <div class="chat-view-content">
                        <div class="chat-slider">
                            <div class="chat-slides">
                                <div class="chat-slide chat-slide-1 active" data-slide="1">
                                    <span class="text-top text-[#49687E]"><?php esc_html_e("Chat view for all your channels!", "chaty"); ?></span>
                                    <img src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/chat-view-preview.png" alt="chaty" />
                                </div>
                                <div class="chat-slide chat-slide-2" data-slide="2">
                                    <span class="text-top text-[#49687E]"><?php esc_html_e("Customize Pop-ups for product pages!", "chaty"); ?></span>
                                    <img src="<?php echo esc_url(CHT_PLUGIN_URL) ?>admin/assets/images/woo-commerce-preview.png" alt="chaty" />
                                </div>
                            </div>
                        </div>
                        <div class="chat-slider-options">
                            <ul >
                                <li><a href="javascript:;" class="prev-slide">❮</a></li>
                                <li><a href="javascript:;" class="slide-option slide-1 active" data-slide="1"><span></span></a></li>
                                <li><a href="javascript:;" class="slide-option slide-2" data-slide="2"><span></span></a></li>
                                <li><a href="javascript:;" class="next-slide">❯</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="chaty-popup" id="whatsapp-message-popup">
    <div class="chaty-popup-outer"></div>
    <div class="chaty-popup-inner popup-pos-bottom">
        <div class="chaty-popup-content">
            <div class="chaty-popup-close">
                <a href="javascript:void(0)" class="close-delete-pop close-chaty-popup-btn relative top-2 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"/></svg>
                </a>
            </div>
            <div class="a-card a-card--normal">
                <div class="chaty-popup-header font-medium text-cht-gray-150 py-4 text-left px-5">
                    <?php esc_html_e("Leading zero in WhatsApp number", "chaty") ?>
                </div>
                <div class="text-cht-gray-150 text-base px-5 py-6">
                    <?php esc_html_e("You've entered your WhatsApp number with a leading zero. Are you sure the number is correct?", "chaty") ?>
                    <div class="phone-number-list" data-label="<?php esc_html_e("Phone number", "chaty"); ?>" data-action="<?php esc_html_e("Remove Zero", "chaty"); ?>" data-test="<?php esc_html_e("Test", "chaty"); ?>" >

                    </div>
                </div>
                <div class="chaty-popup-footer flex px-5 justify-end">
                    <button type="button" class="remove-zero-btn channel-setting-btn btn btn-primary bg-transparent text-cht-gray-150 border-cht-gray-150 hover:bg-transparent hover:text-cht-gray-150 rounded-lg mr-5"><?php esc_html_e("Remove Zero", "chaty") ?></button>
                    <button type="button" class="btn btn-default check-for-numbers btn btn-primary btn rounded-lg btn-primary "><?php esc_html_e("Proceed", "chaty") ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "chatyway-info-popup.php" ?>